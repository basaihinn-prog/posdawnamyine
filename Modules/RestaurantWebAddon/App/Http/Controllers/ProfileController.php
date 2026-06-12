<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use HasUploader;

    public function index()
    {
        $user = User::where('business_id', auth()->user()->business_id)->with('business:id,remainingShopBalance,shopOpeningBalance')->first();
        return view('restaurantwebaddon::profile.index', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'companyName' => [
                $user->hasRole('shop-owner') ? 'required' : 'nullable',
                'string',
                'max:255',
                 Rule::unique('businesses', 'companyName')->ignore($user->business->id),
            ],
            'email' => 'required|email',
            'image' => 'nullable|image',
        ]);


        if ($request->password || $request->current_password) {
            if (Hash::check($request->current_password, $user->password)) {
                $request->validate([
                    'current_password' => 'required|string',
                    'password' => 'required|string|confirmed',
                ]);
            } else {
                return response()->json(__('Current Password does not match with old password'), 404);
            }
        }

        $user->update($request->except('image', 'password', 'name') + [
                'name' => $request->companyName,
                'image' => $request->image ? $this->upload($request, 'image', $user->image) : $user->image,
            ] + ($request->password ? ['password' => Hash::make($request->password)] : [])  // If password is provided, hash it and update
        );

        if ($request->companyName){
            $user->business->update([
                'companyName' => $request->companyName,
                'slug' => Str::slug($request->companyName),
            ]);
        }

        return response()->json([
            'message' => __('Profile updated successfully'),
            'redirect' => route('business.profiles.index'),
        ]);
    }
}
