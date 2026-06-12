<?php

namespace App\Http\Controllers\Api;

use App\Models\Party;
use App\Models\Business;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Purchase::select('id', 'party_id', 'payment_type_id', 'invoiceNumber', 'purchaseDate', 'totalAmount', 'dueAmount', 'paidAmount')
                ->with('party:id,name,phone,type','payment_type:id,name')
                ->when(request('search'), function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('invoiceNumber', 'like', '%' . request('search') . '%')
                            ->orWhereHas('party', function ($query) {
                                $query->where('name', 'like', '%' . request('search') . '%')
                                    ->orWhere('phone', 'like', '%' . request('search') . '%');
                            })
                            ->orWhereHas('payment_type', function ($query) {
                                $query->where('name', 'like', '%' . request('search') . '%');
                            });
                    });
                })
                ->when(request('from_date') || request('to_date'), function ($query) {
                    $query->whereDate('purchaseDate', '>=', request('from_date'))
                          ->whereDate('purchaseDate', '<=', request('to_date'));
                })
                ->when(request('payment_status'), function ($query) {
                    if(request('payment_status') == 'paid') {
                        $query->where('dueAmount', 0);
                    } elseif(request('payment_status') == 'due') {
                        $query->where('dueAmount', '>', 0);
                    }
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
            'ingredients' => 'required|array',
            'purchaseDate' => 'required|string',
            'party_id' => 'required|exists:parties,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric|min:0|max:99999999.99',
            'discount_type' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'tax_percentage' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'dueAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'ingredients.*.ingredient_id' => 'nullable|exists:ingredients,id',
            'ingredients.*.unit_id' => 'nullable|exists:units,id',
            'ingredients.*.unit_price' => 'nullable|numeric|min:0|max:99999999.99',
            'ingredients.*.quantities' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {

            $business_id = auth()->user()->business_id;

            if ($request->dueAmount) {
                $party = Party::findOrFail($request->party_id);
                $party->update([
                    'due' => $party->due + $request->dueAmount
                ]);
            }

            $business = Business::findOrFail($business_id);
            $business->update([
                'remainingShopBalance' => $business->remainingShopBalance - $request->paidAmount
            ]);

            $purchase = Purchase::create($request->all() + [
                            'user_id' => auth()->id(),
                            'business_id' => $business_id,
                        ]);

            $purchaseDetails = [];
            foreach ($request->ingredients as $key => $ingredient_data) {
                $purchaseDetails[$key] = [
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $ingredient_data['ingredient_id'],
                    'unit_id' => $ingredient_data['unit_id'],
                    'unit_price' => $ingredient_data['unit_price'] ?? 0,
                    'quantities' => $ingredient_data['quantities'] ?? 0,
                ];
            }

            PurchaseDetails::insert($purchaseDetails);

            Transaction::create([
                'business_id' => $business_id,
                'purchase_id' => $purchase->id,
                'payment_type_id' => $request->payment_type_id,
                'date' => $request->purchaseDate,
                'total_amount' => $purchase->totalAmount,
                'paid_amount' => $purchase->paidAmount,
                'due_amount' => $purchase->dueAmount,
                'type' => 'debit',
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Data saved successfully.'),
                'data' => $purchase->load([
                                'payment_type:id,name',
                                'user:id,name',
                                'party:id,name,phone',
                                'details.ingredient:id,name',
                                'details.unit:id,unitName',
                                'details:id,purchase_id,ingredient_id,unit_id,unit_price,quantities',
                            ]),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }

    public function show(string $id)
    {
        $data = Purchase::with([
                    'payment_type:id,name',
                    'user:id,name',
                    'party:id,name,phone,type',
                    'details.ingredient:id,name',
                    'details.unit:id,unitName',
                    'details:id,purchase_id,ingredient_id,unit_id,unit_price,quantities',
                ])
                ->findOrFail($id);

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }


    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'ingredients' => 'required|array',
            'purchaseDate' => 'required|string',
            'party_id' => 'required|exists:parties,id',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'discountAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'discountPercentage' => 'nullable|numeric|min:0|max:99999999.99',
            'discount_type' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'tax_percentage' => 'nullable|numeric|min:0|max:99999999.99',
            'totalAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'dueAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'paidAmount' => 'nullable|numeric|min:0|max:99999999.99',
            'ingredients.*.ingredient_id' => 'nullable|exists:ingredients,id',
            'ingredients.*.unit_id' => 'nullable|exists:units,id',
            'ingredients.*.unit_price' => 'nullable|numeric|min:0|max:99999999.99',
            'ingredients.*.quantities' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {

            $business_id = auth()->user()->business_id;

            $purchaseDetails = [];
            foreach ($request->ingredients as $key => $ingredient_data) {
                $purchaseDetails[$key] = [
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $ingredient_data['ingredient_id'],
                    'unit_id' => $ingredient_data['unit_id'],
                    'unit_price' => $ingredient_data['unit_price'] ?? 0,
                    'quantities' => $ingredient_data['quantities'] ?? 0,
                ];
            }

            if ($purchase->dueAmount || $request->dueAmount) {
                $party = Party::findOrFail($request->party_id);
                $party->update([
                    'due' => $request->party_id == $purchase->party_id ? (($party->due - $purchase->dueAmount) + $request->dueAmount) : ($party->due + $request->dueAmount)
                ]);

                if ($request->party_id != $purchase->party_id) {
                    $prev_party = Party::findOrFail($purchase->party_id);
                    $prev_party->update([
                        'due' => $prev_party->due - $purchase->dueAmount
                    ]);
                }
            }

            $business = Business::findOrFail(auth()->user()->business_id);
            $business->update([
                'remainingShopBalance' => ($business->remainingShopBalance + $purchase->paidAmount) - $request->paidAmount
            ]);

            $purchase->update($request->all() + [
                'user_id' => auth()->id(),
            ]);

            PurchaseDetails::where('purchase_id', $purchase->id)->delete();
            PurchaseDetails::insert($purchaseDetails);

            $transaction = Transaction::where('purchase_id', $purchase->id)->first();

            $transaction->update([
                'business_id' => $business_id,
                'purchase_id' => $purchase->id,
                'payment_type_id' => $request->payment_type_id,
                'date' => $request->purchaseDate,
                'total_amount' => $purchase->totalAmount,
                'paid_amount' => $purchase->paidAmount,
                'due_amount' => $purchase->dueAmount,
                'type' => 'debit',
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Data saved successfully.'),
                'data' => $purchase->load([
                                'payment_type:id,name',
                                'user:id,name',
                                'party:id,name,phone',
                                'details.ingredient:id,name',
                                'details.unit:id,unitName',
                                'details:id,purchase_id,ingredient_id,unit_id,unit_price,quantities',
                            ]),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }


    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        try {

            if ($purchase->dueAmount) {
                $party = Party::findOrFail($purchase->party_id);
                $party->update([
                    'due' => $party->due - $purchase->dueAmount
                ]);
            }

            $business = Business::findOrFail(auth()->user()->business_id);
            $business->update([
                'remainingShopBalance' => $business->remainingShopBalance + $purchase->paidAmount
            ]);

            $purchase->delete();
            DB::commit();

            return response()->json([
                'message' => __('Data deleted successfully.'),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => __('Something was wrong.'),
            ], 406);
        }
    }
}
