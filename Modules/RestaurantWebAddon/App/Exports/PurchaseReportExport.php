<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseReportExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::reports.purchase.excel-csv', [
            'purchases' => Purchase::with('party:id,name', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
