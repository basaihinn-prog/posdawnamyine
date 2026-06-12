<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        $data = BusinessCategory::whereStatus(1)->latest()->get();

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data,
        ]);
    }
}
