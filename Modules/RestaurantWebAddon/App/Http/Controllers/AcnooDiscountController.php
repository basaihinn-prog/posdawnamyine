<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Option;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AcnooDiscountController extends Controller
{
     public function index()
    {
        $discount = Option::where('key', 'discount')
            ->whereJsonContains('value->business_id', auth()->user()->business_id)
            ->first();

        return view('restaurantwebaddon::discount.index', compact('discount'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'amount' => 'required|max:250',
            'discount_type' => 'required|string',
        ]);

        $business = Business::findOrFail(auth()->user()->business_id);

        $delivery_charge = Option::find($id);

        if ($delivery_charge) {
            $delivery_charge->update([
                'value' =>  [
                    'business_id' => $business->id,
                    'amount' => $request->amount,
                    'discount_type' => $request->discount_type,
                    'status' => $request->status,
                ],
            ]);
        } else {
            Option::insert([
                'key' => 'discount',
                'value' => json_encode([
                    'business_id' => $business->id,
                    'amount' => $request->amount,
                    'discount_type' => $request->discount_type,
                    'status' => $request->status,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget("discount_{$business->id}");

        return response()->json([
            'message' => __('Discount updated successfully'),
            'redirect' => route('business.discount.index'),
        ]);
    }
}
