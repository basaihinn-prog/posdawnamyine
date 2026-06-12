<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Helpers\HasUploader;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('check.permission:generalSetting.view')->only('index');
        $this->middleware('check.permission:generalSetting.update')->only('update');
    }

    public function index()
    {
        $setting = Option::where('key', 'business-settings')
            ->whereJsonContains('value->business_id', auth()->user()->business_id)
            ->first();

        $business_categories = BusinessCategory::whereStatus(1)->latest()->get();
        $business = Business::findOrFail(auth()->user()->business_id);

        return view('restaurantwebaddon::settings.general', compact('setting', 'business_categories', 'business'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'address' => 'nullable|max:250',
            'companyName' => 'required|max:250',
            'business_category_id' => 'required|exists:business_categories,id',
            'phoneNumber' => 'nullable',
            'min:5',
            'max:15',
            'vat_name' => 'nullable|max:250',
            'vat_no' => 'nullable|max:250|required_with:vat_name',
            'invoice_logo' => 'nullable|image',
            'notification_favicon' => 'nullable|mimes:png,jpg,jpeg',
            'note' => 'nullable|string|max:250',
            'note_label' => 'nullable|string|max:250',
            'gratitude_message' => 'nullable|string|max:250'
        ]);

        DB::beginTransaction();

        try {
            $business = Business::findOrFail(auth()->user()->business_id);

            $business->update([
                'address' => $request->address,
                'companyName' => $request->companyName,
                'business_category_id' => $request->business_category_id,
                'phoneNumber' => $request->phoneNumber,
                'vat_name' => $request->vat_name,
                'vat_no' => $request->vat_no,
                'email' => $request->email
            ]);

            $data = $request->except('_token', '_method', 'logo', 'favicon', 'invoice_logo', 'notification_favicon', 'cart_sound', 'notification_sound', 'address', 'companyName', 'business_category_id', 'phoneNumber');

            $setting = Option::find($id);

            if ($setting) {
                $setting->update($request->except($data) + [
                    'value' => $request->except('_token', '_method', 'invoice_logo', 'notification_favicon', 'cart_sound', 'notification_sound', 'address', 'companyName', 'business_category_id', 'phoneNumber') + [
                        'business_id' => $business->id,
                        'invoice_logo' => $request->invoice_logo ? $this->upload($request, 'invoice_logo', $setting->value['invoice_logo'] ?? null) : ($setting->value['invoice_logo'] ?? null),
                        'notification_favicon' => $request->notification_favicon ? $this->upload($request, 'notification_favicon', $setting->value['notification_favicon'] ?? null) : ($setting->value['notification_favicon'] ?? null),
                        'cart_sound' => $request->cart_sound ? $this->upload($request, 'cart_sound', $setting->value['cart_sound'] ?? null) : ($setting->value['cart_sound'] ?? null),
                        'notification_sound' => $request->notification_sound ? $this->upload($request, 'notification_sound', $setting->value['notification_sound'] ?? null) : ($setting->value['notification_sound'] ?? null),
                        'sale_rounding_option' => $request->sale_rounding_option ?? 'none',
                        'note' => $request->note,
                        'note_label' => $request->note_label,
                        'gratitude_message' => $request->gratitude_message,
                        'vat_name' => $request->vat_name,
                        'vat_no' => $request->vat_no,
                        'email' => $request->email

                    ],
                ]);
            } else {
                Option::insert([
                    'key' => 'business-settings',
                    'value' => json_encode([
                        'business_id' => $business->id,
                        'invoice_logo' => $request->invoice_logo ? $this->upload($request, 'invoice_logo') : null,
                        'notification_favicon' => $request->notification_favicon ? $this->upload($request, 'notification_favicon') : null,
                        'cart_sound' => $request->cart_sound ? $this->upload($request, 'cart_sound') : null,
                        'notification_sound' => $request->notification_sound ? $this->upload($request, 'notification_sound') : null,
                        'sale_rounding_option' => $request->sale_rounding_option ?? 'none',
                        'note' => $request->note,
                        'note_label' => $request->note_label,
                        'gratitude_message' => $request->gratitude_message,
                        'vat_name' => $request->vat_name,
                        'vat_no' => $request->vat_no,
                        'email' => $request->email
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Cache::forget("business_setting_{$business->id}");
            Cache::forget("business_sale_rounding_{$business->id}");

            DB::commit();

            return response()->json([
                'message' => __('Business General Setting updated successfully'),
                'redirect' => route('business.settings.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(__('Something was wrong.'), 400);
        }
    }
}
