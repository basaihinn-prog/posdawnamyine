<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CategoryExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::categories.excel-csv', [
            'categories' => Category::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
