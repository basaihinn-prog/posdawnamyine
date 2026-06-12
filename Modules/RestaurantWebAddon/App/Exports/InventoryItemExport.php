<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Ingredient;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventoryItemExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::items.excel-csv', [
            'items' => Ingredient::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
