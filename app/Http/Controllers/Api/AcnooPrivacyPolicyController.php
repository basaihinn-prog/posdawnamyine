<?php

namespace App\Http\Controllers\Api;

use App\Models\Option;
use App\Http\Controllers\Controller;

class AcnooPrivacyPolicyController extends Controller
{
    public function index()
    {
        $privacy_policy = Option::where('key', 'privacy-policy')->first();
        return response()->json([
            'message' => 'Privacy Policy fetched successfully',
            'data' => $privacy_policy
        ]);
    }
}
