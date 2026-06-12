<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\DueCollect;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DueCollectReportExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::reports.due-collect.excel-csv', [
            'due_collections' => DueCollect::with('payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
