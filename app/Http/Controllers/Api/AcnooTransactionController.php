<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use App\Http\Controllers\Controller;

class AcnooTransactionController extends Controller
{

    public function index()
    {
        $transaction = Transaction::with('payment_type:id,name')->where('business_id', auth()->user()->business_id)
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
                        ->when(request()->has('type'), function ($query) {
                            $query->where('type', request('type'));
                        })
                        ->latest()
                        ->paginate(10);

        return response()->json([
            'message' => 'Transaction fetched successfully',
            'data' => $transaction
        ]);

    }
}
