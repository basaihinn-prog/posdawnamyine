<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Party;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerDueReportExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::reports.due.excel-csv', [
            'due_lists' => Party::where('business_id', auth()->user()->business_id)->where('type', '!=', 'supplier')->latest()->get()
        ]);
    }
}
