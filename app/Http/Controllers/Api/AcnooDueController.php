<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Party;
use App\Models\Business;
use App\Models\Purchase;
use App\Models\DueCollect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcnooDueController extends Controller
{
    public function index()
    {
        $latestPerPurchase = DueCollect::selectRaw('MAX(id) as id')
        ->where('business_id', auth()->user()->business_id)
        ->whereNotNull('purchase_id')
        ->groupBy('purchase_id')
        ->pluck('id')
        ->toArray();

        $latestPerSale = DueCollect::selectRaw('MAX(id) as id')
            ->where('business_id', auth()->user()->business_id)
            ->whereNotNull('sale_id')
            ->groupBy('sale_id')
            ->pluck('id')
            ->toArray();

        $latestIds = array_merge($latestPerPurchase, $latestPerSale);

        $data = DueCollect::with(
            'user:id,name',
            'party:id,name,email,phone,type',
            'payment_type:id,name',
            'sale:id,invoiceNumber',
            'purchase:id,invoiceNumber'
           )
                ->when(request('search'), function ($query) {
                    $query->where('invoiceNumber', 'like', '%' . request('search') . '%')
                        ->orWhere('totalDue', 'like', '%' . request('search') . '%')
                        ->orWhereHas('payment_type', function ($query) {
                            $query->where('name', 'like', '%' . request('search') . '%');
                        });
                })
                ->when(request('from_date') || request('to_date'), function ($query) {
                    $query->whereDate('paymentDate', '>=', request('from_date'))
                          ->whereDate('paymentDate', '<=', request('to_date'));
                })
                ->where('business_id', auth()->user()->business_id)
                ->latest()
                ->paginate(10);

               $data->getCollection()->transform(function ($item) use ($latestIds) {
                    // Add common invoice_number field
                    $item->invoice_number = $item->sale->invoiceNumber ?? $item->purchase->invoiceNumber ?? null;
                    $item->is_last = in_array($item->id, $latestIds);
                    return $item;
                });
        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }

    public function duesList()
    {
        $businessId = auth()->user()->business_id;
        $search = request('search');
        $from = request('from_date');
        $to = request('to_date');
        $page = request('page', 1);
        $perPage = 10;

        // Purchase dues
        $purchaseDues = Purchase::select('id', 'party_id', 'payment_type_id', 'invoiceNumber', 'totalAmount', 'paidAmount', 'dueAmount', 'purchaseDate as date')
            ->with('payment_type:id,name', 'party:id,name,type')
            ->where('business_id', $businessId)
            ->where('dueAmount', '>', 0)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('invoiceNumber', 'like', "%$search%")
                        ->orWhere('totalAmount', 'like', "%$search%")
                        ->orWhere('dueAmount', 'like', "%$search%");
                });
            })
            ->when($from, fn($q) => $q->whereDate('purchaseDate', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('purchaseDate', '<=', $to))
            ->get()
            ->map(fn($item) => $item->setAttribute('type', 'purchase'));

        // Sale dues
        $saleDues = Sale::select('id', 'party_id', 'payment_type_id', 'invoiceNumber', 'totalAmount', 'paidAmount', 'dueAmount', 'saleDate as date')
            ->with('payment_type:id,name', 'party:id,name,type')
            ->where('business_id', $businessId)
            ->whereStatus('completed')
            ->where('dueAmount', '>', 0)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('invoiceNumber', 'like', "%$search%")
                        ->orWhere('totalAmount', 'like', "%$search%")
                        ->orWhere('dueAmount', 'like', "%$search%");
                });
            })
            ->when($from, fn($q) => $q->whereDate('saleDate', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('saleDate', '<=', $to))
            ->get()
            ->map(fn($item) => $item->setAttribute('type', 'sale'));

        // Combine and paginate
        $combined = $purchaseDues->merge($saleDues)->sortByDesc('date')->values();
        $paginated = collect($combined)->forPage($page, $perPage);
        $total = $combined->count();

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => [
                'current_page' => (int) $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) ceil($total / $perPage),
                'data' => $paginated->values(),
            ]
        ]);
    }

    public function store(Request $request) {
        $business_id = auth()->user()->business_id;

        $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'paymentDate' => 'required|string',
            'payDueAmount' => 'required|numeric',
            'party_id' => 'nullable|exists:parties,id',
            'invoiceNumber' => 'required_without:party_id|nullable|string',
        ]);

        $party = $request->filled('party_id') ? Party::find($request->party_id) : null;

        // when request invoice
        if ($request->invoiceNumber) {
            if ($party) {
                $request->validate(
                    [
                        'invoiceNumber' => 'nullable|exists:' . ($party->type == 'supplier' ? 'purchases' : 'sales') . ',invoiceNumber',
                    ]
                );
                if ($party->type == 'supplier') {
                    $invoice = Purchase::where('invoiceNumber', $request->invoiceNumber)->where('party_id', $request->party_id)->first();
                } else {
                    $invoice = Sale::where('invoiceNumber', $request->invoiceNumber)->where('party_id', $request->party_id)->first();
                }
            } else {
                $invoice = Sale::where('invoiceNumber', $request->invoiceNumber)->whereNull('party_id')->first();
            }

            if (!$invoice) {
                return response()->json([
                    'message' => 'Invoice Not Found.'
                ], 404);
            }

            if ($invoice->dueAmount < $request->payDueAmount) {
                return response()->json([
                    'message' => 'Invoice due is ' . $invoice->dueAmount . '. You cannot pay more than the invoice due amount.'
                ], 400);
            }
        }

        // No invoice, but party exists: check against party opening balance
        if (!$request->invoiceNumber) {
            if ($request->payDueAmount > $party->opening_balance) {
                return response()->json([
                    'message' => __('You can pay only ' . $party->opening_balance . ', without selecting an invoice.')
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            $data = DueCollect::create([
                'user_id' => auth()->id(),
                'business_id' => $business_id,
                'party_id' => $party?->id,
                'sale_id' => isset($invoice) && ((isset($party) && $party->type !== 'supplier') || !isset($party)) ? $invoice->id : null,
                'purchase_id' => $party && $party->type === 'supplier' && isset($invoice) ? $invoice->id : null,
                'invoiceNumber' => $request->invoiceNumber,
                'totalDue' => isset($invoice) ? $invoice->dueAmount : ($party?->due ?? 0),
                'dueAmountAfterPay' => isset($invoice)
                    ? ($invoice->dueAmount - $request->payDueAmount)
                    : (($party?->due ?? 0) - $request->payDueAmount),
                'payDueAmount' => $request->payDueAmount ?? 0,
                'payment_type_id' => $request->payment_type_id,
                'paymentDate' => Carbon::parse($request->paymentDate)->setTimeFrom(Carbon::now()),
            ]);

            // Update invoice due
            if (isset($invoice)) {
                $invoice->update([
                    'dueAmount' => $invoice->dueAmount - $request->payDueAmount,
                    'paidAmount' => $invoice->paidAmount + $request->payDueAmount
                ]);
            }

            // Update shop balance
            $business = Business::findOrFail($business_id);
            if ($party) {
                $business->update([
                    'remainingShopBalance' => $party->type === 'supplier'
                        ? ($business->remainingShopBalance - $request->payDueAmount)
                        : ($business->remainingShopBalance + $request->payDueAmount),
                ]);

                // Update party dues
                $party->update([
                    'due' => $party->due - $request->payDueAmount,
                    'opening_balance' => $request->invoiceNumber
                        ? $party->opening_balance
                        : $party->opening_balance - $request->payDueAmount,
                ]);
            } else {
                $business->update([
                    'remainingShopBalance' => $business->remainingShopBalance - $request->payDueAmount,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => __('Collect Due saved successfully'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $business_id = auth()->user()->business_id;

        $request->validate([
            'payment_type_id' => 'required|exists:payment_types,id',
            'paymentDate' => 'required|string',
            'payDueAmount' => 'required|numeric',
            'party_id' => 'nullable|exists:parties,id',
            'invoiceNumber' => 'required_without:party_id|nullable|string',
        ]);

        $dueCollect = DueCollect::findOrFail($id);
        $party = $request->filled('party_id') ? Party::find($dueCollect->party_id) : null;
        $oldPayAmount = $dueCollect->payDueAmount;

        // when request invoice
        if ($request->invoiceNumber) {
            if ($party) {
                $request->validate(
                    [
                        'invoiceNumber' => 'nullable|exists:' . ($party->type == 'supplier' ? 'purchases' : 'sales') . ',invoiceNumber',
                    ]
                );
                if ($party->type == 'supplier') {
                    $invoice = Purchase::where('invoiceNumber', $request->invoiceNumber)->where('party_id', $request->party_id)->first();
                } else {
                    $invoice = Sale::where('invoiceNumber', $request->invoiceNumber)->where('party_id', $request->party_id)->first();
                }
            } else {
                $invoice = Sale::where('invoiceNumber', $request->invoiceNumber)->whereNull('party_id')->first();
            }

            if (!$invoice) {
                return response()->json([
                    'message' => 'Invoice Not Found.'
                ], 404);
            }

            $availableDue = $invoice?->dueAmount + $oldPayAmount ?? ($party?->due + $oldPayAmount);
            if ($request->payDueAmount > $availableDue) {
                return response()->json([
                    'message' => 'You cannot pay more than the available due amount: ' . $availableDue,
                ], 400);
            }
        }

       // No invoice, but party exists: check against party opening balance
       if (!$request->invoiceNumber) {
            if ($request->payDueAmount > $party->opening_balance + $oldPayAmount) {
                return response()->json([
                    'message' => __('You can pay only ' . $party->opening_balance . ', without selecting an invoice.')
                ], 400);
            }
         }

        DB::beginTransaction();
        try {
            // Reverse previous updates
            if ($invoice) {
                $invoice->update([
                    'dueAmount' => $invoice->dueAmount + $oldPayAmount,
                    'paidAmount' => $invoice->paidAmount - $oldPayAmount
                ]);
            }

            if ($party) {
                $party->update([
                    'due' => $party->due + $oldPayAmount,
                    'opening_balance' => $dueCollect->invoiceNumber
                        ? $party->opening_balance
                        : $party->opening_balance + $oldPayAmount,
                ]);
            }

            $business = Business::findOrFail($business_id);
            $business->update([
                'remainingShopBalance' => $party
                    ? ($party->type === 'supplier'
                        ? $business->remainingShopBalance + $oldPayAmount
                        : $business->remainingShopBalance - $oldPayAmount)
                    : $business->remainingShopBalance + $oldPayAmount,
            ]);

            // Apply new changes
            $dueCollect->update([
                'party_id' => $party?->id,
                'sale_id' => isset($invoice) && ((isset($party) && $party->type !== 'supplier') || !isset($party)) ? $invoice->id : null,
                'purchase_id' => $party && $party->type === 'supplier' && isset($invoice) ? $invoice->id : null,
                'payment_type_id' => $request->payment_type_id,
                'paymentDate' => Carbon::parse($request->paymentDate)->setTimeFrom(Carbon::now()),
                'payDueAmount' => $request->payDueAmount,
                'dueAmountAfterPay' => $invoice
                    ? ($invoice->dueAmount - $request->payDueAmount)
                    : (($party?->due) - $request->payDueAmount),
                'totalDue' => $invoice
                    ? $invoice->dueAmount
                    : ($party?->due ?? 0),
            ]);

            if ($invoice) {
                $invoice->update([
                    'dueAmount' => ($invoice->dueAmount - $request->payDueAmount),
                ]);
            }

            if ($party) {
                $party->update([
                    'due' => $party->due - $request->payDueAmount,
                    'opening_balance' => $dueCollect->invoiceNumber
                        ? $party->opening_balance
                        : $party->opening_balance - $request->payDueAmount,
                ]);
            }

            $business->update([
                'remainingShopBalance' => $party
                    ? ($party->type === 'supplier'
                        ? $business->remainingShopBalance - $request->payDueAmount
                        : $business->remainingShopBalance + $request->payDueAmount)
                    : $business->remainingShopBalance - $request->payDueAmount,
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Due Collection updated successfully'),
                'data' => $dueCollect,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

}
