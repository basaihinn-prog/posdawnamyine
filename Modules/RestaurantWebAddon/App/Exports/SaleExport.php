<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaleExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::sales.excel-csv', [
            'sales' => Sale::with('party:id,name', 'payment_type:id,name')
                ->where('business_id', auth()->user()->business_id)
                ->latest()
                ->get()
        ]);
    }
}
