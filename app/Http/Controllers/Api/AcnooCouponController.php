<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AcnooCouponController extends Controller
{
    use HasUploader;

    public function index()
    {
        $today = now();
        $coupons = Coupon::where('business_id', auth()->user()->business_id)
                            ->when(request('status') === 'available', function ($query) use ($today) {
                                $query->whereDate('start_date', '<=', $today)
                                    ->whereDate('end_date', '>=', $today);
                            })
                            ->when(request('status') === 'upcoming', function ($query) use ($today) {
                                $query->whereDate('start_date', '>', $today);
                            })
                            ->when(request('status') === 'expired', function ($query) use ($today) {
                                $query->whereDate('end_date', '<', $today);
                            })
                           ->latest()
                           ->paginate(10);
        return response()->json([
            'message' => __('data fetched successfully.'),
            'data' => $coupons
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:coupons,code|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount_type' => 'required|in:percentage,flat',
            'discount' => 'required|numeric|min:0|max:99999999.99',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'nullable|string',

        ]);

       $coupon = Coupon::create($request->except('image', 'business_id') + [
            'business_id' => auth()->user()->business_id,
            'image' => $request->image ? $this->upload($request, 'image') : NULL,
        ]);

        return response()->json([
            'message' => 'Coupon created successfully',
            'data' => $coupon
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:coupons,code,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount_type' => 'required|in:percentage,flat',
            'discount' => 'required|numeric|min:0|max:99999999.99',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'nullable|string',

        ]);

        $coupon = Coupon::findOrFail($id);

        $coupon->update($request->except('image') + [
            'image' => $request->image ? $this->upload($request, 'image', $coupon->image) : $coupon->image,
        ]);

        return response()->json([
            'message' => 'Coupon update successfully',
            'data' => $coupon
        ]);
    }

    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);

        if (!empty($coupon->image) && Storage::exists($coupon->image)) {
            Storage::delete($coupon->image);
        }
        $coupon->delete();
        return response()->json([
            'message' => __('Coupon deleted successfully.'),
        ]);
    }
}
