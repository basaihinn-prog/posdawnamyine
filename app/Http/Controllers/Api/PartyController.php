<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Party;
use App\Models\Purchase;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PartyController extends Controller
{
    use HasUploader;

    public function index(Request $request)
    {
        $parties = Party::where('business_id', auth()->user()->business_id)
                    ->when(request('type'), function($query) {
                        $query->where('type', request('type'));
                    })
                    ->when(request('search'), function($query) {
                        $query->where('name', 'like', '%'.request('search'). '%')
                            ->orWhere('opening_balance', 'like', '%'.request('search'). '%')
                            ->orWhere('type', 'like', '%'.request('search'). '%');

                    })
                    ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $parties = $parties->select('id', 'name')->with('delivery_addresses:id,party_id,address')->get();
            $responseData = [
                'data' => $parties,
            ];
        } else {
            $parties = $parties->paginate(10);
            $responseData = $parties;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|max:20|unique:parties,phone',
            'opening_balance' => 'nullable|numeric|min:0|max:99999999.99',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);


         DB::beginTransaction();
         try {

            $party = Party::create($request->except('image', 'opening_balance') + [
                        'opening_balance' => $request->opening_balance ?? 0,
                        'due' => $request->opening_balance ?? 0,
                        'business_id' => auth()->user()->business_id,
                        'user_id' => auth()->id(),
                        'image' => $request->image ? $this->upload($request, 'image') : NULL,
                    ]);

            if ($party->type === 'customer' && is_array($request->delivery_name)) {
                foreach ($request->delivery_name as $index => $name) {
                    $address = $request->delivery_address[$index] ?? null;

                    if (!empty($name) || !empty($request->delivery_phone[$index]) || !empty($address)) {
                        DeliveryAddress::create([
                            'party_id' => $party->id,
                            'name' => $name,
                            'phone' => $request->delivery_phone[$index] ?? null,
                            'address' => $address,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => __('Data saved successfully.'),
                'data' => $party,
            ]);

         } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Something went wrong.'),
            ], 500);
        }
    }

    public function show(string $id) {
        $party = Party::with('delivery_addresses')->findOrFail($id);

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $party,
        ]);
    }

    public function view_ledger(Request $request, string $id) {
        $sale_query = Sale::where('party_id', $id)
                        ->when(request('from_date') || request('to_date'), function ($query) {
                            $query->whereDate('saleDate', '>=', request('from_date'))
                                    ->whereDate('saleDate', '<=', request('to_date'));
                        });

        $purchase_query = Purchase::where('party_id', $id)
                            ->when(request('from_date') || request('to_date'), function ($query) {
                                $query->whereDate('purchaseDate', '>=', request('from_date'))
                                    ->whereDate('purchaseDate', '<=', request('to_date'));
                            });

        if(request('type') == 'customer') {
            $view_all = $sale_query->select('id', 'paidAmount', 'totalAmount', 'invoiceNumber', 'sales_type', 'saleDate', 'payment_type_id')
                        ->with('payment_type:id,name')
                        ->latest();

            $total_sale = (clone $sale_query)->sum('totalAmount');
            if ($request->has('no_paginate') && $request->no_paginate == true) {
                $view_all = $view_all->select('id', 'paidAmount', 'totalAmount', 'invoiceNumber', 'sales_type', 'saleDate', 'payment_type_id')
                                ->with('payment_type:id,name')
                                ->get();

                $responseData = [
                    'data' => $view_all,
                ];
            } else {
                $view_all = $view_all->paginate(10);
                $responseData = $view_all;
            }

            return response()->json([
                'message' => __('Data fetched successfully.'),
                'data' => $responseData,
                'total_sale' => $total_sale,
            ]);
        }

        if(request('type') == 'supplier') {
            $view_all = $purchase_query->select('id', 'paidAmount', 'totalAmount', 'dueAmount', 'invoiceNumber', 'purchaseDate', 'payment_type_id')
                            ->with('payment_type:id,name')
                            ->latest();

            $total_purchase = (clone $purchase_query)->sum('totalAmount');
            if ($request->has('no_paginate') && $request->no_paginate == true) {
                $view_all = $view_all->select('id', 'paidAmount', 'totalAmount', 'dueAmount', 'invoiceNumber', 'purchaseDate', 'payment_type_id')
                                ->with('payment_type:id,name')
                                ->get();

                $responseData = [
                    'data' => $view_all,
                ];

            } else {
                $view_all = $view_all->paginate(10);
                $responseData = $view_all;
            }

            return response()->json([
                'message' => __('Data fetched successfully.'),
                'data' => $responseData,
                'total_purchase' => $total_purchase,
            ]);
        }
    }

    public function update(Request $request, Party $party)
    {
        $request->validate([
            'phone' => 'required|max:20|unique:parties,phone,' . $party->id,
        ]);

        DB::beginTransaction();

        try {

            $openingBalance = $request->opening_balance ?? 0;

            if ($party->type === 'supplier') {
                $pendingDues = $party->purchases_dues()->sum('dueAmount');
                // Prevent reducing due below pending dues
                if ($openingBalance < $pendingDues) {
                    return response()->json([
                        'message' => __('You cannot reduce the due amount below the currently pending Purchase dues (' . $pendingDues . '). Please settle the dues first.')
                    ], 406);
                }

                $party->opening_balance = $openingBalance;
                $party->due = $pendingDues + $openingBalance;
            } else {
                // For customers or other types
                $party->opening_balance = $openingBalance;
                $party->due = $openingBalance;
            }

            $party->update($request->except('image', 'opening_balance') + [
                    'user_id' => auth()->id(),
                    'opening_balance' => $party->opening_balance,
                    'due' => $party->due,
                    'image' => $request->image ? $this->upload($request, 'image', $party->image) : $party->image,
                ]);


            // Update or insert delivery addresses if type is customer
            if ($party->type === 'customer' && is_array($request->delivery_name)) {
                $existingIds = [];
                foreach ($request->delivery_name as $index => $name) {
                    $address_id = $request->address_id[$index] ?? null;
                    $phone = $request->delivery_phone[$index] ?? null;
                    $address = $request->delivery_address[$index] ?? null;

                    // Skip if all fields are empty
                    if (empty($name) && empty($phone) && empty($address)) {
                        continue;
                    }

                    $delivery =  DeliveryAddress::updateOrCreate(
                        ['id' => $address_id, 'party_id' => $party->id],
                        [
                            'name' => $name,
                            'phone' => $phone,
                            'address' => $address,
                        ]
                    );

                    $existingIds[] = $delivery->id;
                }

                DeliveryAddress::where('party_id', $party->id)
                                ->whereNotIn('id', $existingIds)
                                ->delete();
            }

            DB::commit();

            return response()->json([
                'message' => __('Data saved successfully.'),
                'data' => $party,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('Something went wrong.'),
            ], 500);
        }
    }

    public function destroy(Party $party)
    {
        $party->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
