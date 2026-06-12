<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use App\Models\Gateway;
use App\Models\Business;
use App\Models\Currency;
use Illuminate\Support\Str;
use App\Helpers\HasUploader;
use App\Models\UserCurrency;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use App\Exports\BusinessExport;
use Illuminate\Validation\Rule;
use App\Models\BusinessCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class AcnooBusinessController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:business-create')->only('create', 'store');
        $this->middleware('permission:business-read')->only('index');
        $this->middleware('permission:business-update')->only('edit', 'update', 'status');
        $this->middleware('permission:business-delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $plans = Plan::latest()->get();
        $gateways = Gateway::latest()->get();
        $businesses = Business::with('enrolled_plan:id,plan_id', 'enrolled_plan.plan:id,subscriptionName', 'category:id,name')->latest()->paginate(10);

        return view('admin.business.index', compact('businesses', 'gateways', 'plans'));
    }

    public function acnooFilter(Request $request)
    {
        $search = $request->input('search');

        $businesses = Business::with('enrolled_plan:id,plan_id', 'enrolled_plan.plan:id,subscriptionName', 'category:id,name')
                        ->when($search, function ($q) use ($search) {
                            $q->where(function ($q) use ($search) {
                                $q->where('companyName', 'like', '%' . $search . '%')
                                    ->orWhere('phoneNumber', 'like', '%' . $search . '%')
                                    ->orWhereHas('category', function ($q) use ($search) {
                                        $q->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('enrolled_plan.plan', function ($q) use ($search) {
                                        $q->where('subscriptionName', 'like', '%' . $search . '%');
                                    });
                            });
                        })
                        ->latest()
                        ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.business.datas', compact('businesses'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function create()
    {
        $plans = Plan::where('status', 1)->latest()->get();
        $categories = BusinessCategory::whereStatus(1)->latest()->get();
        return view('admin.business.create', compact('plans', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'nullable|max:250',
            'companyName' => 'required|unique:businesses,companyName|max:250',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'pictureUrl' => 'nullable|image|max:2024',
            'phoneNumber'  => 'nullable|min:5|max:18',
            'shopOpeningBalance' => 'nullable|numeric|min:0|max:99999999.99',
            'business_category_id' => 'required|exists:business_categories,id',
            'plan_subscribe_id' => 'required|exists:plans,id',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();

            // Checking the email is existing or not
            $existingUser = User::where('email', $request->email)->first();

            $business = Business::create([
                'companyName' => $request->companyName,
                'slug' => Str::slug($request->companyName),
                'address' => $request->address,
                'email' =>  $request->email,
                'password' => $request->password,
                'phoneNumber' => $request->phoneNumber,
                'shopOpeningBalance' => $request->shopOpeningBalance,
                'business_category_id' => $request->business_category_id,
                'pictureUrl' => $request->pictureUrl ? $this->upload($request, 'pictureUrl') : NULL,
                'user_id' => $user->id,
            ]);

            if ($existingUser) {
                $existingUser->update([
                    'business_id' => $business->id,
                    'name' => $request->companyName,
                    'phone' => $request->phoneNumber,
                    'image' => $request->pictureUrl ? $this->upload($request, 'pictureUrl', $user->image) : $user->image,
                    'password' => Hash::make($request->password),
                ]);
            }
            else {
                User::create([
                    'business_id' => $business->id,
                    'name' => $request->companyName,
                    'email' =>  $request->email,
                    'phone' => $request->phoneNumber,
                    'image' => $request->pictureUrl ? $this->upload($request, 'pictureUrl') : NULL,
                    'password' => Hash::make($request->password),
                    'user_id' => $user->id,
                    'lang' => 'en',
                ]);
            }

            if ($request->plan_subscribe_id) {
                $plan = Plan::findOrFail($request->plan_subscribe_id);

                $subscribe = PlanSubscribe::create([
                    'plan_id' => $plan->id,
                    'notes' => $request->notes,
                    'duration' => $plan->duration,
                    'business_id' => $business->id,
                    'price' => $plan->subscriptionPrice,
                    'addon_domain_limit' => $plan->addon_domain_limit ?? 0,
                    'subdomain_limit' => $plan->subdomain_limit ?? 0,
                    'gateway_id' => $request->gateway_id,
                ]);

                $business->update([
                    'subscriptionDate' => now(),
                    'plan_subscribe_id' => $subscribe->id,
                    'will_expire' => now()->addDays($plan->duration),
                ]);

                sendNotification($subscribe->id, route('admin.subscription-reports.index', ['id' => $subscribe->id]), __('Plan subscribed by ' . $user->name));
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

            return response()->json([
                'message' => __('Business created successfully.'),
                'redirect' => route('admin.business.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(__('Something went wrong.'), 403);
        }
    }

    public function edit(string $id)
    {
        $plans = Plan::latest()->get();
        $business = Business::findOrFail($id);
        $categories = BusinessCategory::whereStatus(1)->latest()->get();
        $user = User::where('business_id', $business->id)->firstOrFail();
        return view('admin.business.edit', compact('business', 'plans', 'categories', 'user'));
    }

    public function update(Request $request, string $id)
    {
        $business = Business::findOrFail($id);
        $user = User::where('business_id', $business->id)->firstOrFail();
        $request->validate([
            'address' => 'nullable|max:250',
            'companyName' => [
                'required',
                'max:250',
                Rule::unique('businesses', 'companyName')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'pictureUrl' => 'nullable|image|max:2024',
            'phoneNumber'  => 'nullable|min:5|max:18',
            'shopOpeningBalance' => 'nullable|numeric',
            'business_category_id' => 'required|exists:business_categories,id',
            'plan_subscribe_id' => 'required|exists:plans,id',
        ]);

        DB::beginTransaction();

        try {
            $existingUser = User::where('email', $request->email)
                            ->where('id', '!=', $user->id)
                            ->first();

            if ($existingUser) {
                return response()->json([
                    'message' => __('This email is already in use by another user.'),
                ], 422);
            }

            $business->update([
                'companyName' => $request->companyName,
                'slug' => Str::slug($request->companyName),
                'address' => $request->address,
                'phoneNumber' => $request->phoneNumber,
                'shopOpeningBalance' => $request->shopOpeningBalance,
                'business_category_id' => $request->business_category_id,
                'pictureUrl' => $request->pictureUrl ? $this->upload($request, 'pictureUrl', $business->pictureUrl) : $business->pictureUrl,
            ]);

            $user->update([
                'name' => $request->companyName,
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'image' => $request->pictureUrl ? $this->upload($request, 'pictureUrl', $user->image) : $user->image,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            if ($request->plan_subscribe_id) {
                $plan = Plan::findOrFail($request->plan_subscribe_id);

                $subscribe = PlanSubscribe::create([
                    'plan_id' => $plan->id,
                    'notes' => $request->notes,
                    'duration' => $plan->duration,
                    'business_id' => $business->id,
                    'price' => $plan->subscriptionPrice,
                    'addon_domain_limit' => $plan->addon_domain_limit ?? 0,
                    'subdomain_limit' => $plan->subdomain_limit ?? 0,
                    'gateway_id' => $request->gateway_id,
                ]);

                $business->update([
                    'subscriptionDate' => now(),
                    'plan_subscribe_id' => $subscribe->id,
                    'will_expire' => now()->addDays($plan->duration),
                ]);

                sendNotification($subscribe->id, route('admin.subscription-reports.index', ['id' => $subscribe->id]), __('Plan subscribed by ' . auth()->user()->name));
            }

            DB::commit();

            return response()->json([
                'message' => __('Business updated successfully.'),
                'redirect' => route('admin.business.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(__('Something went wrong.'), 403);
        }
    }

    public function destroy(string $id)
    {
        $business = Business::findOrFail($id);
        $user= User::where('business_id', $business->id)->first();

        if(file_exists($business->pictureUrl)){
            Storage::delete($business->pictureUrl);
        }

        if(file_exists($user->image)){
            Storage::delete($user->image);
        }

        $business->delete();

        return response()->json([
            'message'   => __('Business deleted successfully'),
            'redirect'  => route('admin.business.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        $businesses = Business::whereIn('id', $request->ids)->get();
        $users = User::where('business_id', $request->ids)->get();

        foreach($businesses as $business){
            if(file_exists($business->pictureUrl)){
                Storage::delete($business->pictureUrl);
            }
        }

        foreach($users as $user){
            if(file_exists($user->image)){
                Storage::delete($user->image);
            }
        }

        Business::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message'   => __('Selected Business deleted successfully'),
            'redirect'  => route('admin.business.index')
        ]);
    }

    // Upgrade plan code
    public function upgradePlan(Request $request)
    {
        $request->validate([
            'price' => 'required|string',
            'notes' => 'required|string',
            'plan_id' => 'required|exists:plans,id',
            'business_id' => 'required|exists:businesses,id',
        ]);

        DB::beginTransaction();
        try {

            $plan = Plan::findOrFail($request->plan_id);
            $business = Business::findOrFail($request->business_id);

            $subscribe = PlanSubscribe::create([
                'plan_id' => $plan->id,
                'payment_status' => 'paid',
                'notes' => $request->notes,
                'duration' => $plan->duration,
                'business_id' => $business->id,
                'price' => $plan->subscriptionPrice,
                'addon_domain_limit' => $plan->addon_domain_limit ?? 0,
                'subdomain_limit' => $plan->subdomain_limit ?? 0,
                'gateway_id' => $request->gateway_id,
            ]);

            $business->update([
                'subscriptionDate' => now(),
                'plan_subscribe_id' => $subscribe->id,
                'will_expire' => now()->addDays($plan->duration),
            ]);

            sendNotification($subscribe->id, route('admin.subscription-reports.index', ['id' => $subscribe->id]), __('Plan subscribed by ' . auth()->user()->name));

            DB::commit();
            return response()->json([
                'message' => __('Subscription enrolled successfully.'),
                'redirect' => route('admin.subscription-reports.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 403);
        }
    }

    public function status(Request $request, $id)
    {
        $business = Business::findOrFail($id);
        $business->update(['status' => $request->status]);
        return response()->json(['message' => 'Business']);
    }

    public function exportExcel()
    {
        return Excel::download(new BusinessExport, 'business.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new BusinessExport, 'business.csv');
    }
}
