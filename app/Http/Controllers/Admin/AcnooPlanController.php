<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Exports\PlanExport;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class AcnooPlanController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:plans-create')->only('create', 'store');
        $this->middleware('permission:plans-read')->only('index');
        $this->middleware('permission:plans-update')->only('edit', 'update', 'status');
        $this->middleware('permission:plans-delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $plans = Plan::latest()->paginate(10);
        return view('admin.plans.index',  compact('plans'));
    }

    public function acnooFilter(Request $request)
    {
        $plans = Plan::when(request('search'), function ($q) {
            $q->where(function ($q) {
                $q->orWhere('subscriptionName', 'like', '%' . request('search') . '%')
                    ->orWhere('duration', 'like', '%' . request('search') . '%')
                    ->orWhere('subscriptionPrice', 'like', '%' . request('search') . '%');
            });
        })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.plans.datas', compact('plans'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'author')->get();
        return view('admin.plans.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subscriptionName' => 'required|string|max:255',
            'duration' => 'required|string',
            'offerPrice' => 'nullable|numeric|min:0|max:99999999.99',
            'subscriptionPrice' => 'required|numeric|min:0|max:99999999.99',
            'addon_domain_limit' => 'nullable|integer|min:0',
            'subdomain_limit' => 'nullable|integer|min:0',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
        ]);

        Plan::create($request->except(['offerPrice', 'status', 'icon']) + [
            'offerPrice' => $request->offerPrice ?? NULL,
            'status' => $request->status ? 1 : 0,
            'icon' => $request->icon ? $this->upload($request, 'icon') : NULL
        ]);

        return response()->json([
            'message' => __('Subscription Plan created successfully'),
            'redirect' => route('admin.plans.index')
        ]);
    }


    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }


    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'subscriptionName' => 'required|string|max:255',
            'duration' => 'required|string',
            'offerPrice' => 'nullable|numeric|min:0|max:99999999.99',
            'subscriptionPrice' => 'required|numeric|min:0|max:99999999.99',
            'addon_domain_limit' => 'nullable|integer|min:0',
            'subdomain_limit' => 'nullable|integer|min:0',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
        ]);

        DB::beginTransaction();
        try {
            $plan->update($request->except(['offerPrice', 'status', 'icon']) + [
                'offerPrice' => $request->offerPrice ?? NULL,
                'status' => $request->status ? 1 : 0,
                'icon' => $request->icon ? $this->upload($request, 'icon', $plan->icon) : $plan->icon
            ]);

            if ($request->allow_existing_subscriber){
                $updateData = [
                    'addon_domain_limit' => $request->addon_domain_limit ?? 0,
                    'subdomain_limit' => $request->subdomain_limit ?? 0,
                ];

                PlanSubscribe::where('plan_id', $plan->id)->update($updateData);
            }
            DB::commit();

            return response()->json([
                'message' => __('Subscription Plan updated successfully'),
                'redirect' => route('admin.plans.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function status(Request $request, string $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update(['status' => $request->status]);
        return response()->json(['message' => 'Plan']);
    }

    public function popular(string $id)
    {
        $plan = Plan::find($id);

        if ($plan) {
            Plan::where('id', '!=', $id)->update(['is_popular' => 0]);
            $plan->update(['is_popular' => 1]);
        }

        return redirect()->route('admin.plans.index')->with('message', __('Popular activated successfully'));
    }

    public function destroy(Plan $plan)
    {
        if (file_exists($plan->icon)) {
            Storage::delete($plan->icon);
        }

        $plan->delete();

        return response()->json([
            'message'   => __('Subscription plan deleted successfully'),
            'redirect'  => route('admin.plans.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        $plans = Plan::whereIn('id', $request->ids)->get();

        foreach ($plans as $plan) {
            if (file_exists($plan->icon)) {
                Storage::delete($plan->icon);
            }

            $plan->delete();
        }

        return response()->json([
            'message' => __('Selected subscription plan deleted successfully'),
            'redirect' => route('admin.plans.index')
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new PlanExport, 'plans.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new PlanExport, 'plans.csv');
    }
}
