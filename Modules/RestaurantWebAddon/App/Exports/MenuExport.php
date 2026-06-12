<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MenuExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::menus.excel-csv', [
            'menus' => Menu::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
