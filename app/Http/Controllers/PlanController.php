<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $page_data = get_option('manage-pages');
        $plans = Plan::where('status',1)->latest()->get();
        $business_categories = BusinessCategory::whereStatus(1)->latest()->get();

        return view('web.plan.index',compact('page_data', 'plans', 'business_categories'));
    }
}
