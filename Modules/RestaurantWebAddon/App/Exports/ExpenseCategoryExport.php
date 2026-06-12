<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\ExpenseCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseCategoryExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::expense-categories.excel-csv', [
            'expense_categories' => ExpenseCategory::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
