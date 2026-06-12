<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Party;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DueExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::dues.excel-csv', [
            'dues' => Party::where('business_id', auth()->user()->business_id)->where('due', '>', 0)->latest()->get()
        ]);
    }
}
