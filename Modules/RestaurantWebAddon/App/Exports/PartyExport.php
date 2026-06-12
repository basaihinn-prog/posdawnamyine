<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Party;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PartyExport implements FromView
{
    public function view(): View
    {
        $query = Party::where('business_id', auth()->user()->business_id);

        if (request('type') === 'customer') {
            $query->where('type', 'customer');
        } elseif (request('type') === 'supplier') {
            $query->where('type', 'supplier');
        }

        $parties = $query->latest()->get();

        return view('restaurantwebaddon::parties.excel-csv', compact('parties'));
    }
}
