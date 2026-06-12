<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use App\Models\User;
use App\Models\Option;
use App\Models\Business;
use App\Models\Currency;
use Illuminate\Support\Str;
use App\Helpers\HasUploader;
use App\Models\UserCurrency;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class BusinessController extends Controller
{
    use HasUploader;

    public function index()
    {
        $business_id = auth()->user()->business_id;

        $business_currency = null;

        // Only fetch or create currency if business_id exists
        if ($business_id) {
            $business_currency = UserCurrency::select('id', 'name', 'code', 'symbol', 'position')
                ->where('business_id', $business_id)
                ->first();
        }

        $user = User::with('business', 'business.category:id,name', 'business.enrolled_plan:id,plan_id,business_id,price,duration', 'business.enrolled_plan.plan:id,subscriptionName', 'business.tax')->findOrFail(auth()->id());

        //admin setting option
        $generalValue = Option::where('key', 'general')->first()->value ?? [];
        $develop_by_level = $generalValue['admin_footer_text'] ?? '';
        $develop_by = $generalValue['admin_footer_link_text'] ?? '';
        $develop_by_link = $generalValue['admin_footer_link'] ?? '';

         // Get business settings option
         $option = Option::where('key', 'business-settings')
         ->whereJsonContains('value->business_id', $business_id)
         ->first();

        $invoice_logo = $option->value['invoice_logo'] ?? null;
        $invoice_note_level = $option->value['invoice_note_level'] ?? null;
        $invoice_note = $option->value['invoice_note'] ?? null;
        $gratitude_message = $option->value['gratitude_message'] ?? null;

        $data = array_merge(
            $user->toArray(),
            ['business_currency' => $business_currency],
            ['invoice_logo' => $invoice_logo],
            ['invoice_note_level' => $invoice_note_level],
            ['invoice_note' => $invoice_note],
            ['gratitude_message' => $gratitude_message],
            ['invoice_size' => !empty(invoice_setting()) ? invoice_setting() : null],
            ['develop_by_level' => $develop_by_level],
            ['develop_by' => $develop_by],
            ['develop_by_link' => $develop_by_link],
        );

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'nullable|max:250',
            'companyName' => 'required|unique:businesses,companyName|max:250',
            'pictureUrl' => 'nullable|image|max:5120',
            'shopOpeningBalance' => 'nullable|numeric|min:0|max:99999999.99',
            'business_category_id' => 'required|exists:business_categories,id',
            'phoneNumber'  => 'nullable|min:5|max:15',

        ]);

        DB::beginTransaction();
        try {

            $user = auth()->user();
            $free_plan = Plan::where('subscriptionPrice', '<=', 0)->orWhere('offerPrice', '<=', 0)->first();

            $business = Business::create($request->except('pictureUrl', 'shopOpeningBalance', 'slug') + [
                            'slug' => Str::slug($request->companyName),
                            'shopOpeningBalance' => $request->shopOpeningBalance ?? 0,
                            'phoneNumber' => $request->phoneNumber,
                            'subscriptionDate' => $free_plan ? now() : NULL,
                            'will_expire' => $free_plan ? now()->addDays($free_plan->duration) : NULL,
                            'pictureUrl' => $request->pictureUrl ? $this->upload($request, 'pictureUrl') : NULL
                        ]);

            $user->update([
                'business_id' => $business->id,
            ]);

            if ($free_plan) {
                $subscribe = PlanSubscribe::create([
                                'plan_id' => $free_plan->id,
                                'business_id' => $business->id,
                                'duration' => $free_plan->duration,
                            ]);

                            $business->update([
                                'subscriptionDate' => now(),
                                'plan_subscribe_id' => $subscribe->id,
                                'will_expire' => now()->addDays($free_plan->duration),
                            ]);
            }

            $currency = Currency::where('is_default', 1)->first();

            UserCurrency::create([
                'business_id' => $business->id,
                'currency_id' => $currency->id,
                'name' => $currency->name,
                'country_name' => $currency->country_name,
                'code' => $currency->code,
                'rate' => $currency->rate,
                'symbol' => $currency->symbol,
                'position' => $currency->position,
            ]);

            DB::commit();

            $user->load('business', 'business.enrolled_plan:id,plan_id,business_id,price,duration', 'business.enrolled_plan.plan:id,subscriptionName');

            $business_currency = UserCurrency::select('id', 'name', 'code', 'symbol', 'position')
                                    ->where('business_id', $user->business_id)
                                    ->first();

            $data = array_merge(
                $user->toArray(),
                ['business_currency' => $business_currency]
            );

            return response()->json([
                'message' => __('Business setup completed.'),
                'data' => $data
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(__('Something was wrong, Please contact with admin.'), 403);
        }
    }

    public function update(Request $request, Business $business)
    {
        $request->validate([
            'address' => 'nullable|max:250',
            'companyName' => [
                'required',
                'max:250',
                Rule::unique('businesses', 'companyName')->ignore($business->id),
            ],
            'shopOpeningBalance' => 'nullable|numeric|min:0|max:99999999.99',
            'pictureUrl' => 'nullable|image|max:5120',
            'business_category_id' => 'required|exists:business_categories,id',
            'phoneNumber'  => 'nullable|min:5|max:15',
        ]);

        $user = auth()->user();

        if ($user->role === 'staff' && $user->business_id === $business->id) {

            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'image' => $request->image ? $this->upload($request, 'image', $user->image) : $user->image
            ]);

            return response()->json([
                'message' => __('Profile updated successfully.'),
                'data' => $user,
            ]);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        $business->address = $request->address;
        $business->phoneNumber = $request->phoneNumber;
        $business->companyName = $request->companyName;
        $business->slug = Str::slug($request->companyName);
        $business->shopOpeningBalance = $request->shopOpeningBalance ?? 0;
        $business->business_category_id = $request->business_category_id;
        $business->vat_name = $request->vat_name;
        $business->vat_no = $request->vat_no;
        $business->pictureUrl = $request->pictureUrl ? $this->upload($request, 'pictureUrl', $business->pictureUrl) : $business->pictureUrl;
        $business->save();


        // Update or insert business settings
        $setting = Option::where('key', 'business-settings')
            ->whereJsonContains('value->business_id', $business->id)
            ->first();

        $invoiceLogo = $request->invoice_logo ? $this->upload($request, 'invoice_logo', $setting->value['invoice_logo'] ?? null) : ($setting->value['invoice_logo'] ?? null);

        $settingData = [
            'business_id' => $business->id,
            'invoice_logo' => $invoiceLogo,
            'invoice_note_level' => $request->invoice_note_level,
            'invoice_note' => $request->invoice_note,
            'gratitude_message' => $request->gratitude_message,
        ];

        if ($setting) {
            $setting->update([
                'value' => array_merge($setting->value, $settingData),
            ]);
        } else {
            Option::create([
                'key' => 'business-settings',
                'value' => $settingData,
            ]);
        }

        // Update Invoice Settings
        if ($request->filled('invoice_size')) {
            $invoiceKey = 'invoice_setting_' . $business->id;

            Option::updateOrCreate(
                ['key' => $invoiceKey],
                ['value' => $request->invoice_size]
            );

            Cache::forget($invoiceKey);
        }
        Cache::forget("business_setting_{$business->id}");

        $userData = $user->load('business')->toArray();

        $mergedData = array_merge(
            $userData,
            $settingData,
            [
                'invoice_size' => $request->filled('invoice_size') ? $request->invoice_size : null,
            ]
        );

        return response()->json([
            'message' => __('Data saved successfully.'),
            'business' => $mergedData,
        ]);
    }
}
