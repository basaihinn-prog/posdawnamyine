<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Income;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::incomes.excel-csv', [
            'incomes' => Income::with('category:id,categoryName', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
