<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use App\Models\Currency;
use App\Http\Controllers\Controller;

class AcnooSubscriptionsController extends Controller
{
    public function index()
    {
        $plans = Plan::whereStatus(1)->latest()->get();
        $currency = Currency::where('is_default', 1)->first();

        $data = $plans->map(function ($plan) use ($currency) {
                    return array_merge(
                        $plan->toArray(),
                        ['symbol' => $currency->symbol]
                    );
                });

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }
}
