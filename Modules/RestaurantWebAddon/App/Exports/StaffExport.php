<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Staff;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StaffExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::staffs.excel-csv', [
            'staffs' => Staff::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
