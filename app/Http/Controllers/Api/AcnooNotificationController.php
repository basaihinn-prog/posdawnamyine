<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AcnooNotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->whereDate('created_at', today())
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $notifications
        ]);
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json([
            'message' => 'All notifications read'
        ]);
    }
}
