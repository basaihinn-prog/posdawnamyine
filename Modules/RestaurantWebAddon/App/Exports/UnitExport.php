<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UnitExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::units.excel-csv', [
            'units' => Unit::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
