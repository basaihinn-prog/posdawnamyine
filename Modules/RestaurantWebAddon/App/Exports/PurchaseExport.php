<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::purchases.excel-csv', [
            'purchases' => Purchase::with('details', 'party', 'details.product', 'details.product.category', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
