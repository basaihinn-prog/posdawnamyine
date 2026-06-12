<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Area;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AreaExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::areas.excel-csv', [
            'areas' => Area::withCount('tables as total_table')
                             ->where('business_id', auth()->user()->business_id)
                             ->latest()->get()
        ]);
    }
}
