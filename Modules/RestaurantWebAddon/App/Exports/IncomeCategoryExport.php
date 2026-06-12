<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\IncomeCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeCategoryExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::income-categories.excel-csv', [
            'income_categories' => IncomeCategory::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
