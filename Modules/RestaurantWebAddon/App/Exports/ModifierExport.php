<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Modifier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ModifierExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::modifiers.excel-csv', [
            'modifiers' => Modifier::with('product:id,productName', 'modifier_group:id,name')
                ->where('business_id', auth()->user()->business_id)
                ->latest()
                ->get()
        ]);
    }
}
