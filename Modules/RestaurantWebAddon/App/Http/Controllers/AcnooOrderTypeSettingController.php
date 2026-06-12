<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Option;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AcnooOrderTypeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order_type = Option::where('key', 'order-type')
                                ->whereJsonContains('value->business_id', auth()->user()->business_id)
                                ->first();
        return view('restaurantwebaddon::orderTypeSetting.index', compact('order_type'));
    }


   public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'nullable|array',
            'type' => 'nullable|array',
            'status' => 'nullable|array',
        ]);

        $business = Business::findOrFail(auth()->user()->business_id);

        $order_type = Option::find($id);

        if ($order_type) {
            $order_type->update([
                'value' =>  [
                    'business_id' => $business->id,
                    'name' => $request->name,
                    'type' => $request->type,
                    'status' => $request->status,
                ],
            ]);
        } else {
            Option::insert([
                'key' => 'order-type',
                'value' => json_encode([
                    'business_id' => $business->id,
                    'name' => $request->name,
                    'type' => $request->type,
                    'status' => $request->status,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget("order_type_{$business->id}");

        return response()->json([
            'message' => __('Order Type updated successfully'),
            'redirect' => route('business.order-type-setting.index'),
        ]);
    }
}
