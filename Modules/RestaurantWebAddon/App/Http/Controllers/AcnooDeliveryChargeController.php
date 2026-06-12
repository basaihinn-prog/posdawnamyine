<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Option;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AcnooDeliveryChargeController extends Controller
{

    public function index()
    {
        $delivery_charge = Option::where('key', 'delivery-charge')
            ->whereJsonContains('value->business_id', auth()->user()->business_id)
            ->first();

        return view('restaurantwebaddon::delivery-charge.index', compact('delivery_charge'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'amount' => 'required|max:250',
        ]);

        $business = Business::findOrFail(auth()->user()->business_id);

        $delivery_charge = Option::find($id);

        if ($delivery_charge) {
            $delivery_charge->update([
                'value' =>  [
                    'business_id' => $business->id,
                    'amount' => $request->amount,

                ],
            ]);
        } else {
            Option::insert([
                'key' => 'delivery-charge',
                'value' => json_encode([
                    'business_id' => $business->id,
                    'amount' => $request->amount,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget("delivery_charge_{$business->id}");

        return response()->json([
            'message' => __('Delivery charge updated successfully'),
            'redirect' => route('business.delivery-charge.index'),
        ]);
    }
}
