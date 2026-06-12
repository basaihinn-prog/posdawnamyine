<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\User;
use App\Models\Party;
use App\Models\Table;
use App\Models\Business;
use App\Models\KotTicket;
use App\Models\Quotation;
use App\Models\SaleDetails;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class AcnooSaleController extends Controller
{
    public function index()
    {
        $data = Sale::select('id', 'party_id', 'table_id', 'payment_type_id', 'invoiceNumber', 'saleDate', 'totalAmount', 'paidAmount', 'dueAmount', 'status', 'sales_type')
                ->with('party:id,name,phone', 'table:id,name', 'payment_type:id,name')
                ->withCount(['details as total_item'])
                ->when(request('search'), function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('invoiceNumber', 'like', '%' . request('search') . '%')
                            ->orWhere('meta', 'like', '%' . request('search') . '%')
                            ->orWhereHas('party', function ($query) {
                                $query->where('name', 'like', '%' . request('search') . '%')
                                    ->orWhere('phone', 'like', '%' . request('search') . '%');
                            })
                            ->orWhereHas('payment_type', function ($query) {
                                $query->where('name', 'like', '%' . request('search') . '%');
                            });
                    });
                })
                ->when(request('status'), function($query) {
                    $query->where('status', request('status'));
                })
                ->when(request('sales_type'), function($query) {
                    $query->where('sales_type', request('sales_type'));
                })
                ->when(request('payment_status') === 'paid', function ($query) {
                    $query->where('dueAmount', '<=', 0);
                })
                ->when(request('payment_status') === 'unpaid', function ($query) {
                    $query->where('dueAmount', '>', 0);
                })
                ->when(request('from_date') || request('to_date'), function ($query) {
                    $query->whereDate('saleDate', '>=', request('from_date'))
                          ->whereDate('saleDate', '<=', request('to_date'));
                })
                ->where('business_id', auth()->user()->business_id)
                ->latest()
                ->paginate(10);

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'products' => 'required|array',
            'saleDate' => 'required|string',
            'party_id' => 'nullable|exists:parties,id',
            'address_id' => 'nullable|exists:delivery_addresses,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'staff_id' => 'nullable|exists:staff,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric',
            'coupon_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'coupon_percentage' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.sales_price' => 'required|numeric|min:0|max:99999999.99',
            'products.*.quantities' => 'required|integer',
            'products.*.variation_id' => 'nullable|integer|exists:product_variations,id',
            'products.*.detail_options.*.option_id' => 'nullable|integer|exists:modifier_group_options,id',
            'products.*.detail_options.*.modifier_id' => 'nullable|integer|exists:modifiers,id',
        ]);

        DB::beginTransaction();
        try {

            $business_id = auth()->user()->business_id;

            $sale_invoice = '';
            if($request->is_kot == 1) {
                $saleCount = Sale::where('business_id', $business_id)->count();
                $totalCount = $saleCount  + 1;
                $sale_invoice = "#" . $totalCount;

                $kot = KotTicket::create([
                    'business_id' => auth()->user()->business_id,
                    'table_id' => $request->table_id,
                    'status' => 'pending',
                    'bill_no' => $sale_invoice,
                ]);
            }else{
                $saleCount = Sale::where('business_id', $business_id)->count();
                $totalCount = $saleCount + 1;
                $sale_invoice = "#" . $totalCount;
            }

            if($request->table_id && $request->sales_type == 'dine_in') {
                Table::where('id', $request->table_id)->update(['is_booked' => 1]);
            }

            if ($request->party_id) {
                $party = Party::findOrFail($request->party_id);
                $party->update([
                    'due' => $party->due + $request->dueAmount
                ]);
            }

            $business = Business::findOrFail($business_id);
            $business->update([
                'remainingShopBalance' => $business->remainingShopBalance + $request->paidAmount ?? 0
            ]);

            $sale = Sale::create($request->except('totalAmount', 'invoiceNumber', 'kot_id', 'status') + [
                        'kot_id' => $request->is_kot == 1 ? $kot->id : null,
                        'status' => $request->is_kot == 1 ? "pending" : "completed",
                        'order_status' => 'pending',
                        'invoiceNumber' => $sale_invoice,
                        'totalAmount' =>  $request->totalAmount,
                        'user_id' => auth()->id(),
                        'business_id' => $business_id,
                        'meta' => [
                            'delivery_charge' => $request->delivery_charge,
                            'tip' => $request->tip,
                            'payment_method' => $request->payment_method,
                        ],
                    ]);

                $saleDetails = [];

                foreach ($request->products as $key => $productData) {
                    $saleDetail = SaleDetails::create([
                        'sale_id' => $sale->id,
                        'product_id' => $productData['product_id'],
                        'variation_id' => $productData['variation_id'] ?? NULL,
                        'price' => $productData['sales_price'],
                        'quantities' => $productData['quantities'] ?? 0,
                        'instructions' => $productData['instructions'],
                    ]);

                    // Insert sale_detail_options if exists
                    if (!empty($productData['detail_options'])) {
                        $detailOptionData = [];
                        foreach ($productData['detail_options'] as $option) {
                            $detailOptionData[] = [
                                'sale_detail_id' => $saleDetail->id,
                                'option_id' => $option['option_id'],
                                'modifier_id' => $option['modifier_id'],
                            ];
                        }
                        DB::table('sale_detail_options')->insert($detailOptionData);
                    }

                    $saleDetails[] = $saleDetail;
                }

                //only for quotation convert to sale
                if($request->quotation_id) {
                    $quotation = Quotation::findOrFail($request->quotation_id);
                    $quotation->delete();
                }

                Transaction::create([
                    'business_id' => $business_id,
                    'sale_id' => $sale->id,
                    'payment_type_id' => $request->payment_type_id,
                    'date' => $sale->saleDate,
                    'total_amount' => $sale->totalAmount,
                    'paid_amount' => $sale->paidAmount,
                    'due_amount' => $sale->dueAmount,
                    'type' => 'credit',
                ]);

                if ($request->is_kot == 1) {
                $notification = new NotificationService();
                $tokens = User::where('business_id', $business_id)
                                ->whereIn('role', ['chef', 'kitchen'])
                                ->whereNotNull('fcm_token')
                                ->get()
                                ->flatMap(fn ($user) => $user->fcm_token)
                                ->unique()
                                ->values()
                                ->toArray();
                $icon = asset(get_business_option('business-settings')['notification_favicon'] ?? '');
                $notification->sendPushNotification(
                        $tokens,
                        'New Order Received',
                        'Order has arrived',
                        $icon,
                        [
                            'type' => 'online',
                        ]
                    );
                }

            sendNotifyToUser($sale->id, route('business.sales.index', ['id' => $sale->id]), __('New order arrived.'), $business_id);

            DB::commit();

            $sale->load([
                'coupon',
                'tax:id,name,rate',
                'table:id,name',
                'party:id,name,phone',
                'payment_type:id,name',
                'details:id,sale_id,product_id,variation_id,price,quantities,instructions',
                'details.product:id,productName,sales_price,price_type',
                'details.variation:id,name,price',
                'details.detail_options:id,sale_detail_id,option_id,modifier_id',
                'details.detail_options.modifier_group_option:id,name,price',
            ]);

            if ($request->is_kot == 1) {
                $kot->load('table:id,name');
                $data = array_merge($kot->toArray(), $sale->toArray());
            } else {
                $data = $sale->toArray();
            }
            $data['quotation_id'] = $request->quotation_id ?? null;

            return response()->json([
                'message' => 'Sale created successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }

    //just kot sale payment update
    public function kot_pay(Request $request, $id)
    {
        $request->validate([
            'paidAmount' => 'required|numeric|min:0|max:99999999.99',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric',
            'coupon_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'coupon_percentage' => 'nullable|numeric',
            'tax_id' => 'nullable|exists:taxes,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($id);

            if (!$sale->kot_id) {
                return response()->json([
                    'message' => __('This sale is not linked with a KOT ticket.')
                ], 400);
            }

            $kot = KotTicket::findOrFail($sale->kot_id);
            $business_id = auth()->user()->business_id;

            // Update Sale payment info
            $sale->tax_id = $request->tax_id;
            $sale->payment_type_id = $request->payment_type_id;
            $sale->coupon_id = $request->coupon_id;
            $sale->tax_amount = $request->tax_amount ?? 0;
            $sale->discount_type = $request->discount_type;
            $sale->discountAmount = $request->discountAmount ?? 0;
            $sale->discountPercentage = $request->discountPercentage ?? 0;
            $sale->coupon_amount = $request->coupon_amount ?? 0;
            $sale->coupon_percentage = $request->coupon_percentage ?? 0;

            $sale->totalAmount = $request->totalAmount;
            $sale->dueAmount = $request->totalAmount;
            $sale->paidAmount = $request->paidAmount;

            // Validate against total
            if ($sale->paidAmount > $sale->dueAmount) {
                return response()->json([
                    'message' => __('Paid amount exceeds due amount.')
                ], 400);
            }

            // Update payment info
            $sale->dueAmount = $sale->totalAmount - $request->paidAmount;
            $sale->status = "completed";
            $sale->meta = $request->meta;
            $sale->save();

            // Update business balance
            $business = Business::findOrFail($business_id);
            $business->remainingShopBalance += $request->paidAmount;
            $business->save();

            // Update party due (if applicable)
            if ($sale->party_id && $request->paidAmount > 0) {
                $party = Party::find($sale->party_id);
                if ($party) {
                    $party->due = max(0, $party->due - $request->paidAmount);
                    $party->save();
                }
            }

            if ($kot->table_id) {
                $table_id = $kot->table_id;
                Table::where('id', $table_id)->update(['is_booked' => 0]);
            }

            $transaction = Transaction::where('sale_id', $sale->id)->first();

            $transaction->update([
                'business_id' => $business_id,
                'sale_id' => $sale->id,
                'payment_type_id' => $request->payment_type_id,
                'date' => $sale->saleDate,
                'total_amount' => $sale->totalAmount,
                'paid_amount' => $sale->paidAmount,
                'due_amount' => $sale->dueAmount,
                'type' => 'credit',
            ]);

            DB::commit();

            $sale->load([
                'coupon',
                'tax:id,name,rate',
                'party:id,name,phone',
                'table:id,name',
                'payment_type:id,name',
                'details:id,sale_id,product_id,variation_id,price,quantities,instructions',
                'details.product:id,productName,sales_price,price_type',
                'details.variation:id,name,price',
                'details.detail_options:id,sale_detail_id,option_id,modifier_id',
                'details.detail_options.modifier_group_option:id,name,price',
            ]);

            return response()->json([
                'message' => 'Payment received successfully and KOT finalized.',
                'data' => $sale
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something went wrong while processing payment.'),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $sale = Sale::with([
                    'coupon',
                    'tax',
                    'party',
                    'user:id,name',
                    'delivery_address:id,name,phone,address',
                    'payment_type:id,name',
                    'table:id,name',
                    'details:id,sale_id,product_id,variation_id,price,quantities,instructions',
                    'details.product:id,productName,sales_price,price_type,images',
                    'details.variation:id,name,price',
                    'details.detail_options:id,sale_detail_id,option_id,modifier_id',
                    'details.detail_options.modifier_group_option:id,name,price',
                ])
                ->findOrFail($id);

                return response()->json([
                    'message' => __('Data saved successfully.'),
                    'data' => $sale
                ]);
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'products' => 'required|array',
            'saleDate' => 'required|string',
            'party_id' => 'nullable|exists:parties,id',
            'address_id' => 'nullable|exists:delivery_addresses,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'staff_id' => 'nullable|exists:staff,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric',
            'coupon_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'coupon_percentage' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.sales_price' => 'required|numeric|min:0|max:99999999.99',
            'products.*.quantities' => 'required|integer',
            'products.*.variation_id' => 'nullable|integer|exists:product_variations,id',
            'products.*.detail_options.*.option_id' => 'nullable|integer|exists:modifier_group_options,id',
            'products.*.detail_options.*.modifier_id' => 'nullable|integer|exists:modifiers,id',
        ]);

        $business_id = auth()->user()->business_id;

        DB::beginTransaction();
        try {

            $prevDetails = SaleDetails::where('sale_id', $sale->id)->get();

            $prevDetails->each->delete();

            if ($request->table_id) {
                $kot = KotTicket::findOrFail($sale->kot_id);
                $prevTableId = $kot->table_id;
                $newTableId = $request->table_id;

                // If table changed
                if ($prevTableId != $newTableId) {
                    $otherActiveKot = KotTicket::where('table_id', $prevTableId)
                        ->where('id', '!=', $kot->id)
                        ->exists();

                    if (!$otherActiveKot) {
                        Table::where('id', $prevTableId)->update(['is_booked' => 0]);
                    }

                    Table::where('id', $newTableId)->update(['is_booked' => 1]);

                    $kot->update(['table_id' => $newTableId]);
                } else {
                    // If same table, ensure it's booked
                    Table::where('id', $prevTableId)->update(['is_booked' => 1]);
                }
            }

            // Save new sale details
            $saleDetails = [];

            foreach ($request->products as $key => $productData) {
                $saleDetail = SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'variation_id' => $productData['variation_id'] ?? NULL,
                    'price' => $productData['sales_price'],
                    'quantities' => $productData['quantities'] ?? 0,
                    'instructions' => $productData['instructions'],
                ]);

                // Insert sale_detail_options if exists
                if (!empty($productData['detail_options'])) {
                    $detailOptionData = [];
                    foreach ($productData['detail_options'] as $option) {
                        $detailOptionData[] = [
                            'sale_detail_id' => $saleDetail->id,
                            'option_id' => $option['option_id'],
                            'modifier_id' => $option['modifier_id'],
                        ];
                    }
                    DB::table('sale_detail_options')->insert($detailOptionData);
                }

                $saleDetails[] = $saleDetail;
            }

             // Update financial and business logic
             if ($request->party_id) {
                if ($sale->dueAmount || $request->dueAmount) {
                        $party = Party::findOrFail($request->party_id);
                        $party->update([
                            'due' => $request->party_id == $sale->party_id ?
                                (($party->due - $sale->dueAmount) + $request->dueAmount) :
                                ($party->due + $request->dueAmount)
                        ]);

                        if ($request->party_id != $sale->party_id && $sale->party_id) {
                            $prevParty = Party::findOrFail($sale->party_id);
                            $prevParty->update([
                                'due' => $prevParty->due - $sale->dueAmount
                            ]);
                        }
                    }
               }

            $business = Business::findOrFail($business_id);
            $business->update([
                'remainingShopBalance' => ($business->remainingShopBalance - $sale->paidAmount) + $request->paidAmount
            ]);

            $sale->update($request->except('totalAmount') + [
                'totalAmount' =>  $request->totalAmount,
                'user_id' => auth()->id(),
                'business_id' => $business_id,
                'meta' => [
                    'delivery_charge' => $request->delivery_charge,
                    'tip' => $request->tip,
                    'payment_method' => $request->payment_method,
                ],
            ]);

            $transaction = Transaction::where('sale_id', $sale->id)->first();

            $transaction->update([
                'business_id' => $business_id,
                'sale_id' => $sale->id,
                'payment_type_id' => $request->payment_type_id,
                'date' => $sale->saleDate,
                'total_amount' => $sale->totalAmount,
                'paid_amount' => $sale->paidAmount,
                'due_amount' => $sale->dueAmount,
                'type' => 'credit',
            ]);

            DB::commit();

            $sale->load([
                'coupon',
                'tax:id,name,rate',
                'party:id,name,phone',
                'payment_type:id,name',
                'table:id,name',
                'details:id,sale_id,product_id,variation_id,price,quantities,instructions',
                'details.product:id,productName,sales_price,price_type,images',
                'details.variation:id,name,price',
                'details.detail_options:id,sale_detail_id,option_id,modifier_id',
                'details.detail_options.modifier_group_option:id,name,price',
            ]);

            return response()->json([
                'message' => __('Data saved successfully.'),
                'data' => $sale
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }

    public function destroy(Sale $sale)
    {
        if ($sale->party_id && $sale->dueAmount) {
            $party = Party::findOrFail($sale->party_id);
            $party->decrement('due', $sale->dueAmount);
        }

        $business = Business::findOrFail(auth()->user()->business_id);
        $business->decrement('remainingShopBalance', $sale->paidAmount);

        $table_id = null;

        if($sale->kot_id) {
            $kot = KotTicket::findOrFail($sale->kot_id);
            //load table id for data pass
            $table_id = $kot->table_id;
            // Unbook table if delete order
            if ($kot->table_id) {
              Table::where('id', $kot->table_id)->update(['is_booked' => false]);
            }

            $kot->delete();
        }

        $sale->delete();

        return response()->json([
            'message' => __('Data deleted successfully.'),
            'data' => [
            'table_id' => $table_id,
           ],
        ]);
    }

}
