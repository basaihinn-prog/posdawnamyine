<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransactionExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::transactions.excel-csv', [
            'transactions' => Transaction::with('payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
