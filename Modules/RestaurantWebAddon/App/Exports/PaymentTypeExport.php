<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\PaymentType;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentTypeExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::payment-types.excel-csv', [
            'paymentTypes' => PaymentType::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
