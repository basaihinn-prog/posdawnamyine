<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Option;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcnooReservationSettingController extends Controller
{

    public function index()
    {
        $reservation_setting = Option::where('key', 'reservation-setting')
                              ->whereJsonContains('value->business_id', auth()->user()->business_id)
                              ->first();
        return view('restaurantwebaddon::reservation-setting.index', compact('reservation_setting'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'slots' => 'required|array',
            'slots.*.start_time' => 'nullable|string',
            'slots.*.end_time' => 'nullable|string',
            'slots.*.is_available' => 'nullable|boolean',
        ]);

        $business_id =  auth()->user()->business_id;

         DB::beginTransaction();
        try {
            foreach ($request->slots as $slotType => $slotData) {
                TimeSlot::updateOrCreate(
                    [
                        'business_id' => $business_id,
                        'day' => $request->day,
                        'slot_type' => $slotType
                    ],
                    [
                        'start_time' => $slotData['start_time'],
                        'end_time' => $slotData['end_time'],
                        'time_difference' => $slotData['time_difference'] ?? 0,
                        'is_available' => isset($slotData['is_available'])
                    ]
                );
            }

            Option::updateOrCreate(
                    ['key' => 'reservation-setting'],
                    [
                        'value' => [
                            'business_id' => $business_id,
                            'is_restaurant' => $request->is_restaurant ?? 0,
                            'is_customer' => $request->is_customer ?? 0,
                            'minimum_party_size' => $request->minimum_party_size,
                            'disable_slot' => $request->disable_slot,
                        ]
                    ]
                );

            DB::commit();

            return response([
                'message'=> 'Time slots updated successfully!',
                'redirect'=> route('business.reservation-settting.index')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(__('Something was wrong.'), 400);
        }

    }

    public function getData(Request $request)
    {
        $slots = TimeSlot::where('business_id', auth()->user()->business_id)
                    ->where('day', $request->day)
                    ->get();

        return response()->json($slots);
    }


}
