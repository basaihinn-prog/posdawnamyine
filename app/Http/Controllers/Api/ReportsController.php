<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\DueCollect;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function purchaseReport(Request $request)
    {
        $purchases = Purchase::select('id', 'payment_type_id', 'invoiceNumber', 'purchaseDate', 'totalAmount', 'dueAmount', 'paidAmount')
                        ->with('payment_type:id,name')
                        ->when(request('search'), function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('invoiceNumber', 'like', '%' . request('search') . '%')
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
                        ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $purchases = $purchases->get();
            $responseData = [
                'data' => $purchases,
            ];
        } else {
            $purchases = $purchases->paginate(10);
            $responseData = $purchases;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function salesReport(Request $request)
    {
        $sales = Sale::select('id', 'payment_type_id', 'invoiceNumber', 'saleDate', 'totalAmount', 'paidAmount')
                    ->with('payment_type:id,name')
                    ->when(request('search'), function ($query) {
                        $query->where(function ($subQuery) {
                            $subQuery->where('invoiceNumber', 'like', '%' . request('search') . '%')
                                ->orWhere('meta', 'like', '%' . request('search') . '%')
                                ->orWhereHas('payment_type', function ($query) {
                                    $query->where('name', 'like', '%' . request('search') . '%');
                                });
                        });
                    })
                    ->when(request('from_date') || request('to_date'), function ($query) {
                        $query->whereDate('saleDate', '>=', request('from_date'))
                            ->whereDate('saleDate', '<=', request('to_date'));
                    })
                    ->where('business_id', auth()->user()->business_id)
                    ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $sales = $sales->get();
            $responseData = [
                'data' => $sales,
            ];
        } else {
            $sales = $sales->paginate(10);
            $responseData = $sales;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function quotationReports(Request $request) {

        $quotations = Quotation::select('id', 'business_id', 'party_id', 'payment_type_id', 'invoiceNumber', 'totalAmount', 'quotationDate')
                        ->with('party:id,name,type', 'payment_type:id,name')
                        ->where('business_id', auth()->user()->business_id)
                        ->when(request('search'), function($query) {
                            $query->where('invoiceNumber', 'like', '%'. request('search'). '%')
                                    ->orWhereHas('party', function($q) {
                                        $q->where('name', 'like', '%'.request('search').'%');
                                    })
                                    ->orWhereHas('payment_type', function($q) {
                                        $q->where('name', 'like', '%'.request('search').'%');
                                    });
                        })
                        ->when(request('from_date') || request('to_date'), function ($query) {
                            $query->whereDate('quotationDate', '>=', request('from_date'))
                                ->whereDate('quotationDate', '<=', request('to_date'));
                        })
                        ->latest();

            if ($request->has('no_paginate') && $request->no_paginate == true) {
                $quotations = $quotations->get();
                $responseData = [
                    'data' => $quotations,
                ];
            } else {
                $quotations = $quotations->paginate(10);
                $responseData = $quotations;
            }

            return response()->json([
                'message' => __('Data fetched successfully.'),
                'data' => $responseData,
            ]);
    }

    public function dueCollectsReport(Request $request)
    {
        $due_collects = DueCollect::select('id', 'payment_type_id', 'invoiceNumber', 'paymentDate' ,'totalDue', 'payDueAmount', 'dueAmountAfterPay')
                            ->with('payment_type:id,name')
                            ->when(request('search'), function ($query) {
                                $query->where('invoiceNumber', 'like', '%' . request('search') . '%')
                                    ->orWhere('totalAmount', 'like', '%' . request('search') . '%')
                                    ->orWhere('dueAmountAfterPay', 'like', '%' . request('search') . '%')
                                    ->orWhereHas('payment_type', function ($query) {
                                        $query->where('name', 'like', '%' . request('search') . '%');
                                    });
                            })
                            ->when(request('from_date') || request('to_date'), function ($query) {
                                $query->whereDate('paymentDate', '>=', request('from_date'))
                                    ->whereDate('paymentDate', '<=', request('to_date'));
                            })
                            ->where('business_id', auth()->user()->business_id)
                            ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $due_collects = $due_collects->get();
            $responseData = [
                'data' => $due_collects,
            ];
        } else {
            $due_collects = $due_collects->paginate(10);
            $responseData = $due_collects;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function dueReports(Request $request)
    {

        $businessId = auth()->user()->business_id;
        $search = request('search');
        $from = request('from_date');
        $to = request('to_date');
        $page = request('page', 1);
        $perPage = 10;
        $noPaginate = $request->boolean('no_paginate', false);

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

        if ($noPaginate) {
            // ✅ Return all data without pagination
            return response()->json([
                'message' => __('Data fetched successfully.'),
                'data' => [
                    'data' => $combined->values()
                ],
            ]);
        }

        $paginated = $combined->forPage($page, $perPage);
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

    public function transactionReport(Request $request) {

        $transactions = Transaction::where('business_id', auth()->user()->business_id)
                        ->with('payment_type:id,name')
                        ->when(request('search'), function ($query) {
                            $query->where('invoiceNumber', 'like', '%' . request('search') . '%')
                                ->orWhere('total_amount', 'like', '%' . request('search') . '%')
                                ->orWhere('paid_amount', 'like', '%' . request('search') . '%')
                                ->orWhere('due_amount', 'like', '%' . request('search') . '%')
                                ->orWhereHas('payment_type', function ($query) {
                                    $query->where('name', 'like', '%' . request('search') . '%');
                                });
                        })
                        ->when(request('from_date') || request('to_date'), function ($query) {
                            $query->whereDate('date', '>=', request('from_date'))
                                ->whereDate('date', '<=', request('to_date'));
                        })
                        ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $transactions = $transactions->get();
            $responseData = [
                'data' => $transactions,
            ];
        } else {
            $transactions = $transactions->paginate(10);
            $responseData = $transactions;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function incomeReport(Request $request)
    {
        $income_reports = Income::select('id', 'income_category_id', 'incomeFor', 'amount', 'incomeDate')->with('category:id,categoryName')
                    ->when(request('search'), function ($query) {
                        $query->where(function ($subQuery) {
                            $subQuery->where('amount', 'like', '%' . request('search') . '%')
                                ->orWhere('incomeFor', 'like', '%' . request('search') . '%')
                                ->orWhereHas('category', function ($query) {
                                    $query->where('categoryName', 'like', '%' . request('search') . '%');
                                });
                        });
                    })
                    ->when(request('from_date') || request('to_date'), function ($query) {
                        $query->whereDate('incomeDate', '>=', request('from_date'))
                              ->whereDate('incomeDate', '<=', request('to_date'));
                    })
                    ->where('business_id', auth()->user()->business_id)
                    ->latest();
                    if ($request->has('no_paginate') && $request->no_paginate == true) {
                        $income_reports = $income_reports->get();
                        $responseData = [
                            'data' => $income_reports,
                        ];
                    } else {
                        $income_reports = $income_reports->paginate(10);
                        $responseData = $income_reports;
                    }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function expenseReport()
    {
        $query = Expense::select('id', 'expense_category_id', 'expanseFor', 'amount', 'expenseDate')->with('category:id,categoryName')
                    ->when(request('search'), function ($query) {
                        $query->where(function ($subQuery) {
                            $subQuery->where('amount', 'like', '%' . request('search') . '%')
                                ->orWhere('expanseFor', 'like', '%' . request('search') . '%')
                                ->orWhereHas('category', function ($query) {
                                    $query->where('categoryName', 'like', '%' . request('search') . '%');
                                });
                        });
                    })
                    ->when(request('from_date') || request('to_date'), function ($query) {
                        $query->whereDate('expenseDate', '>=', request('from_date'))
                              ->whereDate('expenseDate', '<=', request('to_date'));
                    })
                    ->where('business_id', auth()->user()->business_id);

        $data = (clone $query)->latest()->paginate(10);

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }

}
