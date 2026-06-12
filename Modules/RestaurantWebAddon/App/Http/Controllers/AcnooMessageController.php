<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:messages.view')->only('index');
        $this->middleware('check.permission:messages.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $messages = Message::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                        ->orWhere('phone', 'like', '%' . request('search') . '%')
                        ->orWhere('email', 'like', '%' . request('search') . '%')
                        ->orWhere('company_name', 'like', '%' . request('search') . '%')
                        ->orWhere('message', 'like', '%' . request('search') . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10)
            ->appends($request->query());

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::messages.datas', compact('messages'))->render()
            ]);
        }

        return view('restaurantwebaddon::messages.index', compact('messages'));
    }

    public function destroy(string $id)
    {
        Message::where('id', $id)->delete();

        return response()->json([
            'message' => __('Message deleted successfully'),
            'redirect' => route('business.messages.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Message::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected message deleted successfully'),
            'redirect' => route('business.messages.index')
        ]);
    }
}
