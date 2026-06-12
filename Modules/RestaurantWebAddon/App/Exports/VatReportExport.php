<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VatReportExport implements FromView
{
    protected $sales, $purchases, $vats;

    public function __construct($sales, $purchases, $vats)
    {
        $this->sales = $sales;
        $this->purchases = $purchases;
        $this->vats = $vats;
    }

    public function view(): View
    {
        return view('restaurantwebaddon::reports.vats.excel-csv', [
            'sales' => $this->sales,
            'purchases' => $this->purchases,
            'vats' => $this->vats,
        ]);
    }
}
