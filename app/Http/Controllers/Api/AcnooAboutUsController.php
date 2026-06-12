<?php

namespace App\Http\Controllers\Api;

use App\Models\Option;
use App\Http\Controllers\Controller;

class AcnooAboutUsController extends Controller
{
    public function index()
    {
        $about_us = Option::where('key', 'about-us')->first();

        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => $about_us
        ]);
    }
}
