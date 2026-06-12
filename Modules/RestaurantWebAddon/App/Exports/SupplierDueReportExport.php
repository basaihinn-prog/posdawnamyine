<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Party;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SupplierDueReportExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::reports.supplier-due.excel-csv', [
            'due_lists' => Party::where('business_id', auth()->user()->business_id)->where('type', 'supplier')->latest()->get()
        ]);
    }
}
