<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Table;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TableExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::tables.excel-csv', [
            'tables' => Table::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
