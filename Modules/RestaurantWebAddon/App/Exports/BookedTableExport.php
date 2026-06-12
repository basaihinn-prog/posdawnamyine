<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Table;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BookedTableExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::booked-tables.excel-csv', [
            'booked_tables' => Table::where('business_id', auth()->user()->business_id)->whereIsBooked(1)->latest()->get()
        ]);
    }
}
