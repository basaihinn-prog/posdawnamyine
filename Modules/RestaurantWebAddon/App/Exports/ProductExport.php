<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::products.excel-csv', [
            'products' => Product::with('menu:id,name', 'category:id,categoryName')
                ->withCount('variations')
                ->where('business_id', auth()->user()->business_id)
                ->latest()
                ->get()
        ]);
    }
}
