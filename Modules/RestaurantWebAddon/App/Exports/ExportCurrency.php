<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Currency;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportCurrency implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::currencies.excel-csv', [
            'currencies' => Currency::whereStatus(1)->orderBy('is_default', 'desc')->latest()->get()
        ]);
    }
}
