<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaleReportExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::reports.sales.excel', [
            'sales' => Sale::with('party:id,name', 'payment_type:id,name')
                ->where('business_id', auth()->user()->business_id)
                ->when(request('filter') === 'todays', function ($q) {
                    $q->whereDate('saleDate', Carbon::today());
                })
                ->when(request('filter') === 'yesterdays', function ($q) {
                    $q->whereDate('saleDate', Carbon::yesterday());
                })
                ->when(request('filter') === 'current_months', function ($q) {
                    $q->whereMonth('saleDate', Carbon::now()->month)
                        ->whereYear('saleDate', Carbon::now()->year);
                })
                ->latest()
                ->get()
        ]);
    }
}
