<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Tax;
use App\Models\Sale;
use App\Models\User;
use App\Models\Party;
use App\Models\Staff;
use App\Models\Table;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Business;
use App\Models\Category;
use App\Models\KotTicket;
use App\Models\Quotation;
use App\Models\PaymentType;
use App\Models\SaleDetails;
use App\Models\Transaction;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\NotificationService;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Modules\RestaurantWebAddon\App\Exports\SaleExport;

class AcnooSaleController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('check.permission:sales.view')->only('index');
        $this->middleware('check.permission:sales.create')->only('create', 'store');
        $this->middleware('check.permission:sales.update')->only('edit', 'update', 'updateStatus');
        $this->middleware('check.permission:sales.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $sales = Sale::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->when($request->type == 'online-sale', function ($query) {
                $query->whereNotNull('business_gateway_id');
            })
            ->when($request->id, function ($q) use ($request) {
                $q->where('id', $request->id);
            })
            ->whereDate('saleDate', Carbon::today())
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::sales.index', compact('sales'));
    }

    public function acnooFilter(Request $request)
    {
        $business_id = auth()->user()->business_id;

        $saleQuery = Sale::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $business_id)
            ->when($request->type === 'online-sale', function ($q) {
                $q->whereNotNull('business_gateway_id');
            });

        $saleQuery->when($request->search, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('invoiceNumber', 'like', '%' . $request->search . '%')
                    ->orWhere('saleDate', 'like', '%' . $request->search . '%')
                    ->orWhere('totalAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('discountAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('paidAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('dueAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%')
                    ->orWhereHas('party', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('payment_type', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        });

        // Default to today
        $startDate = Carbon::today()->format('Y-m-d');
        $endDate = Carbon::today()->format('Y-m-d');

        if ($request->custom_days === 'yesterday') {
            $startDate = Carbon::yesterday()->format('Y-m-d');
            $endDate = Carbon::yesterday()->format('Y-m-d');
        } elseif ($request->custom_days === 'last_seven_days') {
            $startDate = Carbon::today()->subDays(6)->format('Y-m-d');
        } elseif ($request->custom_days === 'last_thirty_days') {
            $startDate = Carbon::today()->subDays(29)->format('Y-m-d');
        } elseif ($request->custom_days === 'current_month') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($request->custom_days === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        } elseif ($request->custom_days === 'current_year') {
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
        } elseif ($request->custom_days === 'custom_date' && $request->from_date && $request->to_date) {
            $startDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $endDate = Carbon::parse($request->to_date)->format('Y-m-d');
        }

        $saleQuery->whereDate('saleDate', '>=', $startDate)
            ->whereDate('saleDate', '<=', $endDate);

        $saleQuery->when($request->payment_status, function ($q) use ($request) {
            if ($request->payment_status === 'paid') {
                $q->where('dueAmount', '=', 0);
            } elseif ($request->payment_status === 'partial') {
                $q->whereColumn('dueAmount', '<', 'totalAmount')
                    ->where('dueAmount', '>', 0);
            } elseif ($request->payment_status === 'unpaid') {
                $q->whereColumn('dueAmount', '=', 'totalAmount');
            }
        });

        $saleQuery->when($request->sales_type, function ($q) use ($request) {
            $q->where('sales_type', $request->sales_type);
        });

        $sales = $saleQuery->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::sales.datas', compact('sales'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function productFilter(Request $request)
    {
        $products = Product::where('business_id', auth()->user()->business_id)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('productName', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->latest()
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::sales.products', compact('products'))->render(),
            ]);
        }

        return redirect(url()->previous());
    }

    public function getDeliveryAddress(Request $request)
    {

        $customers = DeliveryAddress::where('party_id', $request->customer_id)
            ->latest()
            ->get();
        return response()->json($customers);
    }

    public function getSaleCoupon(Request $request)
    {

        $today = today()->format('Y-m-d');

        $coupons = Coupon::where('business_id', auth()->user()->business_id)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->latest()
            ->get();

        return response()->json($coupons);
    }

    public function create(Request $request)
    {
        $business_id = auth()->user()->business_id;

        // Clears all cart items
        Cart::destroy();

        $customers = Party::where('type', '!=', 'supplier')
            ->where('business_id', $business_id)
            ->latest()
            ->get();
        $products = Product::with(
            'menu:id,name',
            'category:id,categoryName',
            'variations:product_id,id,name,price',
            'modifiers:id,product_id,modifier_group_id,is_required,is_multiple',
            'modifiers.modifier_group:id,name',
            'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price'
        )
            ->where('business_id', $business_id)
            ->latest()
            ->get();

        $staffs = Staff::where('business_id', $business_id)
            ->where('designation', 'waiter')
            ->latest()
            ->get();
        $tables = Table::where('business_id', $business_id)
            ->whereStatus(1)
            ->latest()
            ->get();
        $categories = Category::where('business_id', $business_id)
            ->latest()
            ->get();
        $payment_types = PaymentType::where('business_id', $business_id)
            ->whereStatus(1)
            ->latest()
            ->get();
        $vat_on_sale = Tax::where('business_id', $business_id)
            ->where('vat_on_sale', 1)
            ->first();
        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        return view('restaurantwebaddon::sales.create', compact('customers', 'products', 'staffs', 'tables', 'categories', 'payment_types', 'vat_on_sale', 'cart_contents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'nullable|exists:parties,id',
            'address_id' => 'nullable|exists:delivery_addresses,id',
            'table_id' =>  'nullable|exists:tables,id',
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
        ]);

        $business_id = auth()->user()->business_id;

        // Get only 'sale' type items from cart
        $carts = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        if ($carts->count() < 1) {
            return response()->json(['message' => __('Cart is empty. Add items first!')], 400);
        }

        if ($request->is_kot == 1 && $request->sales_type == 'dine_in' && empty($request->table_id)) {
            return response()->json([
                'message' => __('Please select a table to create a KOT!')
            ], 400);
        }

        DB::beginTransaction();
        try {

            $sale_invoice = '';
            if ($request->is_kot == 1) {
                $saleCount = Sale::where('business_id', $business_id)->count();
                $totalCount = $saleCount  + 1;
                $sale_invoice = "#" . $totalCount;

                $kot = KotTicket::create([
                    'business_id' => auth()->user()->business_id,
                    'table_id' => $request->table_id,
                    'status' => 'pending',
                    'bill_no' => $sale_invoice,
                ]);
            } else {
                $saleCount = Sale::where('business_id', $business_id)->count();
                $totalCount = $saleCount + 1;
                $sale_invoice = "#" . $totalCount;
            }

            if ($request->table_id && $request->sales_type == 'dine_in') {
                Table::where('id', $request->table_id)->update(['is_booked' => true]);
            }

            // Calculation: subtotal, vat, discount,coupon
            $subtotal = $carts->sum(fn($item) => (float) $item->subtotal);
            $vat = Tax::find($request->vat_id);
            $vatAmount = $vat ? ($subtotal * $vat->rate) / 100 : 0;

            $discountAmount = $request->discountAmount ?? 0;
            $couponAmount = $request->coupon_amount ?? 0;

            if ($discountAmount > $subtotal) {
                return response()->json(['message' => __('Discount cannot be more than subtotal with VAT!')], 400);
            }
            if ($couponAmount > $subtotal) {
                return response()->json(['message' => __('Coupon cannot be more than subtotal with VAT!')], 400);
            }

            $tipAmount = $request->tip ?? 0;
            $deliveryAmount = $request->delivery_charge ?? 0;
            $actualTotalAmount = ($subtotal - $discountAmount - $couponAmount) + $vatAmount + $tipAmount + $deliveryAmount;


            $paidAmount = $request->paidAmount ?? 0;
            $changeAmount = $paidAmount > $actualTotalAmount ? $paidAmount - $actualTotalAmount : 0;
            $dueAmount = max($actualTotalAmount - $paidAmount, 0);
            $paidAmount = $paidAmount - $changeAmount;

            // Update business balance
            $business = Business::findOrFail($business_id);
            $business->update(['remainingShopBalance' => $business->remainingShopBalance + $paidAmount]);

            // Create Sale record
            $sale = Sale::create([
                'address_id' => $request->address_id,
                'staff_id' => $request->staff_id,
                'coupon_id' => $request->coupon_id,
                'invoiceNumber' => $sale_invoice,
                'kot_id' => $request->is_kot == 1 ? $kot->id : null,
                'table_id' => $request->table_id,
                'status' => $request->is_kot == 1 ? "pending" : "completed",
                'order_status' => 'pending',
                'user_id' => auth()->id(),
                'business_id' => $business_id,
                'party_id' => $request->party_id,
                'saleDate' => $request->saleDate ?? now(),
                'tax_id' => $request->vat_id,
                'tax_amount' => $vatAmount,
                'discountAmount' => $discountAmount,
                'discountPercentage' => $request->discountPercentage ?? 0,
                'coupon_amount' => $request->coupon_amount ?? 0,
                'coupon_percentage' => $request->coupon_percentage ?? 0,
                'totalAmount' => $actualTotalAmount,
                'paidAmount' => $paidAmount > $actualTotalAmount ? $actualTotalAmount : $paidAmount,
                'dueAmount' => $dueAmount,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'sales_type' => $request->sales_type,
                'meta' => [
                    'tip' => $request->tip ?? 0,
                    'delivery_charge' => $request->delivery_charge ?? 0,
                    'payment_method' => $paidAmount >= $actualTotalAmount ? "fullPayment" : "duePayment"
                ]
            ]);

            // Prepare sale details and update
            $saleDetails = [];
            foreach ($carts as $cartItem) {

                $saleDetail = SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cartItem->id,
                    'variation_id' => $cartItem->options->variation_id ?? null,
                    'price' => $cartItem->price,
                    'quantities' => $cartItem->qty,
                ]);

                // Insert sale_detail_options if exists
                if (!empty($cartItem->options['modifiers'])) {
                    $modifierData = [];
                    foreach ($cartItem->options['modifiers'] as $modifier) {
                        $modifierData[] = [
                            'sale_detail_id' => $saleDetail->id,
                            'option_id'      => $modifier['option_id'],
                            'modifier_id'    => $modifier['modifier_id'],
                        ];
                    }

                    if (!empty($modifierData)) {
                        DB::table('sale_detail_options')->insert($modifierData);
                    }
                }

                $saleDetails[] = $saleDetail;
            }


            // Handle due for party
            if ($dueAmount > 0 && $request->party_id) {
                $party = Party::find($request->party_id);
                if ($party) {
                    $party->update(['due' => $party->due + $dueAmount]);
                }
            }

            //only for quotation convert to sale
            if ($request->quotation_id) {
                $quotation = Quotation::findOrFail($request->quotation_id);
                $quotation->delete();
            }

            Transaction::create([
                'business_id' => $business_id,
                'sale_id' => $sale->id,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
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

            if ($request->is_payment == 1) {
                return response()->json([
                    'message' => __('Sales saved successfully.'),
                    'is_payment' => true,
                ]);
            }

            // Clear the cart
            $carts = Cart::content()->filter(fn($item) => $item->options->type == 'sale');
            foreach ($carts as $cartItem) {
                Cart::remove($cartItem->rowId);
            }

            return response()->json([
                'message' => __('Sales saved successfully.'),
                'redirect' => route('business.sales.index'),
                'secondary_redirect_url' => $request->is_kot == 1 ? route('business.sales.kotTicket', $sale->id) : route('business.sales.invoice', $sale->id),
            ]);
        } catch (\Exception) {
            DB::rollback();
            return response()->json(['message' => __('Somethings went wrong!')], 404);
        }
    }

    public function edit(string $id)
    {

        $business_id = auth()->user()->business_id;

        // Clears all cart items
        Cart::destroy();

        $sale = Sale::with('user:id,name', 'coupon:id,discount,discount_type', 'party:id,name,email,phone,type', 'details', 'details.product:id,productName', 'details.detail_options:id,option_id,modifier_id')
            ->where('business_id', $business_id)
            ->findOrFail($id);

        $customers = Party::where('type', '!=', 'supplier')
            ->where('business_id', $business_id)
            ->latest()
            ->get();
        $products = Product::with(
            'menu:id,name',
            'category:id,categoryName',
            'variations:product_id,id,name,price',
            'modifiers:id,product_id,modifier_group_id,is_required,is_multiple',
            'modifiers.modifier_group:id,name',
            'modifiers.modifier_group.modifier_group_option:id,modifier_group_id,is_available,name,price'
        )
            ->where('business_id', $business_id)
            ->latest()
            ->get();

        $staffs = Staff::where('business_id', $business_id)
            ->where('designation', 'waiter')
            ->latest()
            ->get();
        $tables = Table::where('business_id', $business_id)
            ->whereStatus(1)
            ->latest()
            ->get();
        $categories = Category::where('business_id', $business_id)
            ->latest()
            ->get();
        $payment_types = PaymentType::where('business_id', $business_id)
            ->whereStatus(1)
            ->latest()
            ->get();
        $vat_on_sale = Tax::where('business_id', $business_id)
            ->where('vat_on_sale', 1)
            ->first();
        // Add sale details to the cart
        foreach ($sale->details as $detail) {
            $modifiers = [];
            if (!empty($detail->detail_options)) {
                foreach ($detail->detail_options as $detail_option) {
                    $modifiers[] = [
                        'option_id'   => $detail_option->option_id,
                        'modifier_id' => $detail_option->modifier_id,
                    ];
                }
            }
            // Add to cart
            Cart::add([
                'id' => $detail->product_id,
                'name' => $detail->product->productName ?? '',
                'qty' => $detail->quantities,
                'price' => $detail->price ?? 0,
                'options' => [
                    'variation_id' => $detail->variation_id,
                    'modifiers' => $modifiers,
                    'type' => 'sale',
                ]
            ]);
        }
        $cart_contents = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        return view('restaurantwebaddon::sales.edit', compact('sale', 'customers', 'products', 'staffs', 'tables', 'categories', 'payment_types', 'vat_on_sale', 'cart_contents'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'party_id' => 'nullable|exists:parties,id',
            'address_id' => 'nullable|exists:delivery_addresses,id',
            'table_id' =>  'nullable|exists:tables,id',
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
        ]);

        $business_id = auth()->user()->business_id;
        $carts = Cart::content()->filter(fn($item) => $item->options->type == 'sale');

        if ($carts->count() < 1) {
            return response()->json(['message' => __('Cart is empty. Add items first!')], 400);
        }

        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($id);
            $prevDetails = $sale->details;

            // Delete old sale details after restoring stock
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

            // Calculation: subtotal, vat, discount,coupon
            $subtotal = $carts->sum(fn($item) => (float) $item->subtotal);

            // Tax
            $vat = Tax::find($request->vat_id);
            $vatAmount = 0;
            if ($vat) {
                $vatAmount = ($subtotal * $vat->rate) / 100;
            }

            //Discount
            $discountAmount = $request->discountAmount ?? 0;
            if ($discountAmount > $subtotal) {
                return response()->json([
                    'message' => __('Discount cannot be more than subtotal with VAT!')
                ], 400);
            }

            //coupon
            $couponAmount = $request->coupon_amount ?? 0;
            if ($couponAmount > $subtotal) {
                return response()->json(['message' => __('Coupon cannot be more than subtotal with VAT!')], 400);
            }

            $tipAmount = $request->tip ?? 0;
            //total amount
            $actualTotalAmount = (($subtotal - $discountAmount - $couponAmount) + $vatAmount) + $tipAmount;

            $paidAmount = $request->paidAmount ?? 0;
            $changeAmount = $paidAmount > $actualTotalAmount ? $paidAmount - $actualTotalAmount : 0;
            $dueAmount = max($actualTotalAmount - $paidAmount, 0);
            $paidAmount = $paidAmount - $changeAmount;

            // Save new sale details
            $saleDetails = [];
            foreach ($carts as $cartItem) {

                $saleDetail = SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cartItem->id,
                    'variation_id' => $cartItem->options->variation_id ?? null,
                    'price' => $cartItem->price,
                    'quantities' => $cartItem->qty,
                ]);

                // Insert sale_detail_options if exists
                if (!empty($cartItem->options['modifiers'])) {
                    $modifierData = [];
                    foreach ($cartItem->options['modifiers'] as $modifier) {
                        $modifierData[] = [
                            'sale_detail_id' => $saleDetail->id,
                            'option_id'      => $modifier['option_id'],
                            'modifier_id'    => $modifier['modifier_id'],
                        ];
                    }

                    if (!empty($modifierData)) {
                        DB::table('sale_detail_options')->insert($modifierData);
                    }
                }

                $saleDetails[] = $saleDetail;
            }

            // Update financial and business logic
            $business = Business::findOrFail($business_id);
            $business->update([
                'shopOpeningBalance' => ($business->shopOpeningBalance - $sale->paidAmount) + $paidAmount
            ]);

            if (($sale->dueAmount || $request->dueAmount) && $request->party_id != null) {
                $party = Party::findOrFail($request->party_id);
                $party->update([
                    'due' => $request->party_id == $sale->party_id ? (($party->due - $sale->dueAmount) + $dueAmount) : ($party->due + $dueAmount)
                ]);

                if ($request->party_id != $sale->party_id) {
                    $prevParty = Party::findOrFail($sale->party_id);
                    $prevParty->update([
                        'due' => $prevParty->due - $sale->dueAmount
                    ]);
                }
            }

            // Update Sale record
            $sale->update([
                'address_id' => $request->address_id,
                'staff_id' => $request->staff_id,
                'coupon_id' => $request->coupon_id,
                'user_id' => auth()->id(),
                'business_id' => $business_id,
                'party_id' => $request->party_id,
                'saleDate' => $request->saleDate ?? now(),
                'tax_id' => $request->vat_id,
                'tax_amount' => $vatAmount,
                'discountAmount' => $discountAmount,
                'discountPercentage' => $request->discountPercentage ?? 0,
                'coupon_amount' => $request->coupon_amount ?? 0,
                'coupon_percentage' => $request->coupon_percentage ?? 0,
                'totalAmount' => $actualTotalAmount,
                'paidAmount' => $paidAmount > $actualTotalAmount ? $actualTotalAmount : $paidAmount,
                'dueAmount' => $dueAmount,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'sales_type' => $request->type != 'online-sale' ? $request->sales_type : $sale->sales_type,
                'meta' => [
                    'tip' => $request->tip ?? 0,
                    'delivery_charge' => $request->delivery_charge ?? 0,
                    'payment_method' => $paidAmount >= $actualTotalAmount ? "fullPayment" : "duePayment"
                ]
            ]);

            $transaction = Transaction::where('sale_id', $sale->id)->first();

            $transaction->update([
                'business_id' => $business_id,
                'sale_id' => $sale->id,
                'payment_type_id' => ($paidAmount > 0) ? $request->payment_type_id : null,
                'date' => $sale->saleDate,
                'total_amount' => $sale->totalAmount,
                'paid_amount' => $sale->paidAmount,
                'due_amount' => $sale->dueAmount,
                'type' => 'credit',
            ]);

            sendNotifyToUser($sale->id, route('business.sales.index', ['id' => $sale->id]), __('Sale has been updated.'), $business_id);

            DB::commit();

            // Clear the cart
            foreach ($carts as $cartItem) {
                Cart::remove($cartItem->rowId);
            }

            return response()->json([
                'message' => __('Sales updated successfully.'),
                'redirect' => route('business.sales.index'),
                'secondary_redirect_url' => route('business.sales.invoice', $sale->id),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function updateStatus(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $sale = Sale::findOrFail($id);

            if ($sale->kot_id) {
                $kot = KotTicket::find($sale->kot_id);

                if ($kot) {
                    $table_id = $kot->table_id;
                    Table::where('id', $table_id)->update(['is_booked' => 0]);
                }
            } else {
                Table::where('id', $sale->table_id)->update(['is_booked' => 0]);
            }

            $sale->update([
                'status' => $request->status
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Status updated successfully.'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Something went wrong!'),
            ], 500);
        }
    }

    public function orderStatus(Request $request, string $id)
    {
        $order_status = Sale::findOrFail($id);
        $order_status->update(['order_status' => $request->status]);

        return response()->json([
            'message' => __('Status updated successfully.'),
        ], 200);
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($id);

            if ($sale->party_id) {
                $party = Party::findOrFail($sale->party_id);
                $party->update(['due' => $party->due - $sale->dueAmount]);
            }
            if ($sale->kot_id) {
                $kot = KotTicket::findOrFail($sale->kot_id);
                // Unbook table if delete order
                if ($kot->table_id) {
                    Table::where('id', $kot->table_id)->update(['is_booked' => false]);
                }

                $kot->delete();
            }

            sendNotifyToUser($sale->id, route('business.sales.index', ['id' => $sale->id]), __('Sale has been deleted.'), $sale->business_id);

            $sale->delete();

            DB::commit();

            return response()->json([
                'message' => __('Sale deleted successfully.'),
                'redirect' => url()->previous()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function deleteAll(Request $request)
    {
        DB::beginTransaction();

        try {
            $sales = Sale::whereIn('id', $request->ids)->get();

            foreach ($sales as $sale) {

                if ($sale->party_id) {
                    $party = Party::findOrFail($sale->party_id);
                    $party->update(['due' => $party->due - $sale->dueAmount]);
                }

                if ($sale->kot_id) {
                    $kot = KotTicket::findOrFail($sale->kot_id);
                    // Unbook table if delete order
                    if ($kot->table_id) {
                        Table::where('id', $kot->table_id)->update(['is_booked' => false]);
                    }

                    $kot->delete();
                }

                sendNotifyToUser($sale->id, route('business.sales.index', ['id' => $sale->id]), __('Selected sale has been deleted.'), $sale->business_id);
                $sale->delete();
            }

            // Clears all cart items
            Cart::destroy();

            DB::commit();

            return response()->json([
                'message' => __('Selected sale deleted successfully.'),
                'redirect' => route('business.sales.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function getInvoice(string $sale_id)
    {

        $sale = Sale::where('business_id', auth()->user()->business_id)
            ->with(
                'user:id,name,role',
                'party:id,name,phone,address',
                'business:id,phoneNumber,companyName,address,vat_name,vat_no',
                'details:id,price,quantities,product_id,sale_id',
                'details.product:id,productName',
                'details.detail_options:id,sale_detail_id,option_id',
                'details.detail_options.modifier_group_option:id,name,price',
                'table:id,name',
                'payment_type:id,name',
                'business_gateway:id,name'
            )->findOrFail($sale_id);

        return view('restaurantwebaddon::sales.order-invoice', compact('sale'));
    }

    public function getKotTicket(string $sale_id)
    {

        $sale = Sale::where('business_id', auth()->user()->business_id)
            ->with(
                'business:id,companyName',
                'details:id,price,quantities,product_id,sale_id',
                'details.product:id,productName',
                'details.detail_options:id,sale_detail_id,option_id',
                'details.detail_options.modifier_group_option:id,name,price',
                'table:id,name'
            )->findOrFail($sale_id);

        return view('restaurantwebaddon::sales.kot-ticket', compact('sale'));
    }

    public function createCustomer(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|max:20|' . Rule::unique('parties')->where('business_id', auth()->user()->business_id),
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Party::create($request->except('image', 'type') + [
            'type' => 'customer',
            'business_id' => auth()->user()->business_id,
            'image' => $request->image ? $this->upload($request, 'image') : NULL,
        ]);

        return response()->json([
            'message'   => __('Customer created successfully'),
            'redirect'  => url()->previous()
        ]);
    }

    public function generatePDF()
    {
        $businessId = auth()->user()->business_id;

        $sales = Sale::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $currency = business_currency($businessId);

        $pdf = Pdf::loadView('restaurantwebaddon::sales.pdf', compact('sales', 'currency'));
        return $pdf->download('sale-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new SaleExport, 'sale-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new SaleExport, 'sale-list.csv');
    }
}
