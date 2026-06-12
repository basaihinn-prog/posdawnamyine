<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseReportExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::reports.expense.excel-csv', [
            'expense_reports' => Expense::with('category:id,categoryName', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
