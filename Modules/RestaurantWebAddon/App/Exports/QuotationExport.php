<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QuotationExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::quotations.excel-csv', [
            'quotations' => Quotation::with('party:id,name', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
