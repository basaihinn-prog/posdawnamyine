<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::expenses.excel-csv', [
            'expenses' => Expense::with('category:id,categoryName', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
