<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Plan;
use App\Http\Controllers\Controller;

class AcnooSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:subscription.view')->only('index');
    }

    public function index()
    {
        $plans = Plan::where('status', 1)->latest()->get();
        return view('restaurantwebaddon::subscriptions.index', compact('plans'));
    }
}
