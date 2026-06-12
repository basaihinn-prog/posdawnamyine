<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\PlanSubscribe;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SubscriptionReportExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::reports.subscription-reports.excel-csv', [
            'subscribers' => PlanSubscribe::with(['plan:id,subscriptionName', 'gateway:id,name'])->where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
