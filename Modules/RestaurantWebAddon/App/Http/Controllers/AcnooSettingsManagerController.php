<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Http\Controllers\Controller;

class AcnooSettingsManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:settings.view')->only('index');
    }

    public function index()
    {
        return view('restaurantwebaddon::manage-settings.index');
    }

}
