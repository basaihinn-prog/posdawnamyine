<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Tax;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VatExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::vats.excel-csv', [
            'vats' => Tax::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
