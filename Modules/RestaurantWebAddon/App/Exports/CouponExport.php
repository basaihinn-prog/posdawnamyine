<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CouponExport implements FromView
{
    public function view(): View
    {
        return view('restaurantwebaddon::coupons.excel-csv', [
            'coupons' => Coupon::where('business_id', auth()->user()->business_id)->latest()->get()
        ]);
    }
}
