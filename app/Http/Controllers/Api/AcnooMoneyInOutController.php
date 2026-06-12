<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Purchase;
use App\Http\Controllers\Controller;

class AcnooMoneyInOutController extends Controller
{
    public function index()
    {
        $business_id = auth()->user()->business_id;

        $sale_query = Sale::where('business_id', $business_id)
        ->when(request('from_date') || request('to_date'), function ($query) {
            $query->whereDate('saleDate', '>=', request('from_date'))
                ->whereDate('saleDate', '<=', request('to_date'));
        });
        $purchase_query = Purchase::where('business_id', $business_id)
        ->when(request('from_date') || request('to_date'), function ($query) {
            $query->whereDate('purchaseDate', '>=', request('from_date'))
                ->whereDate('purchaseDate', '<=', request('to_date'));
        });

        if(request('type') == 'money_in') {
            $totalPaidAmount = (clone $sale_query)->sum('paidAmount');
            $data = $sale_query->select('id', 'paidAmount', 'totalAmount', 'invoiceNumber', 'sales_type', 'saleDate', 'payment_type_id')
                    ->with('payment_type:id,name')
                    ->when(request('search'), function($query) {
                        $query->where('paidAmount', 'like', '%'.request('search'). '%')
                            ->orWhere('totalAmount', 'like', '%'.request('search'). '%')
                            ->orWhere('invoiceNumber', 'like', '%'.request('search'). '%')
                            ->orWhereHas('payment_type', function ($query) {
                                $query->where('name', 'like', '%' . request('search') . '%');
                            });
                    })
                    ->when(request('sales_type'), function ($query) {
                        $query->where('sales_type', request('sales_type'));
                    })
                    ->latest()
                    ->paginate(10);

        } elseif(request('type') == 'money_out') {
            $totalPaidAmount = (clone $purchase_query)->sum('paidAmount');
            $data = $purchase_query->select('id', 'paidAmount', 'totalAmount', 'dueAmount', 'invoiceNumber', 'purchaseDate', 'payment_type_id')
                    ->with('payment_type:id,name')
                    ->when(request('search'), function($query) {
                        $query->where('paidAmount', 'like', '%'.request('search'). '%')
                            ->orWhere('totalAmount', 'like', '%'.request('search'). '%')
                            ->orWhere('invoiceNumber', 'like', '%'.request('search'). '%')
                            ->orWhereHas('payment_type', function ($query) {
                                $query->where('name', 'like', '%' . request('search') . '%');
                            });
                    })
                    ->latest()
                    ->paginate(10);
        }

        return response()->json([
            'message' => 'Data fetched successfully',
            'amount' =>  (double) $totalPaidAmount,
            'data' => $data
        ]);
    }
}
