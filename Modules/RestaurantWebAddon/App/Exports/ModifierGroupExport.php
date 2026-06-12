<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\ModifierGroups;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ModifierGroupExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::modifier-groups.excel-csv', [
            'modifier_groups' => ModifierGroups::where('business_id', auth()->user()->business_id)
                ->with('modifier_group_option')
                ->latest()
                ->get()
        ]);
    }
}
