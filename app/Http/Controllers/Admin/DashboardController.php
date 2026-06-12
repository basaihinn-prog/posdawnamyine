<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-read')->only('index');
    }

    public function index()
    {
        $businesses = Business::with('enrolled_plan:id,plan_id', 'enrolled_plan.plan:id,subscriptionName', 'category:id,name')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('admin.dashboard.index', compact('businesses'));
    }

    public function getDashboardData()
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $total_businesses = Business::count();
        $expired_businesses = Business::where('will_expire', '<', now())->count();

        $latestSubscriptions = PlanSubscribe::select(DB::raw('MAX(id) as latest_id'))->groupBy('business_id');
        $latestPlans = PlanSubscribe::whereIn('id', $latestSubscriptions)->get();
        $paid_users = $latestPlans->where('price', '>', 0)->count();
        $free_users = $latestPlans->where('price', '<=', 0)->count();

        $this_month_total_businesses = Business::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $this_month_expired_businesses = Business::where('will_expire', '<', now())->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $this_month_paid_users = $latestPlans->where('price', '>', 0)->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $this_month_free_users = $latestPlans->where('price', '<=', 0)->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();

        $last_month_total_businesses = Business::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $last_month_expired_businesses = Business::where('will_expire', '<', now())->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $last_month_paid_users = $latestPlans->where('price', '>', 0)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $last_month_free_users = $latestPlans->where('price', '<=', 0)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        $total_business_arrow = getArrow($last_month_total_businesses, $this_month_total_businesses);
        $expired_arrow = getArrow($last_month_expired_businesses, $this_month_expired_businesses);
        $paid_arrow = getArrow($last_month_paid_users, $this_month_paid_users);
        $free_arrow = getArrow($last_month_free_users, $this_month_free_users);


        $data = [
            'total_businesses' => $total_businesses,
            'expired_businesses' => $expired_businesses,
            'paid_users' => $paid_users,
            'free_users' => $free_users,
            'this_month_total_businesses' => $this_month_total_businesses,
            'this_month_expired_businesses' => $this_month_expired_businesses,
            'this_month_paid_users' => $this_month_paid_users,
            'this_month_free_users' => $this_month_free_users,
            'total_business_arrow' => $total_business_arrow,
            'expired_arrow' => $expired_arrow,
            'paid_arrow' => $paid_arrow,
            'free_arrow' => $free_arrow,
        ];

        return response()->json($data);
    }

    public function subscriptionPlan(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $subscription = PlanSubscribe::with('plan:id,subscriptionName')
                            ->select('plan_id', DB::raw('COUNT(*) as plan_count'))
                            ->whereYear('created_at', $year)
                            ->groupBy('plan_id')
                            ->orderByDesc('plan_count')
                            ->limit(4)
                            ->get();

        return response()->json($subscription);
    }

    public function yearlySubscriptions(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $subscriptions = PlanSubscribe::whereYear('created_at', request('year') ?? date('Y'))
                            ->selectRaw('MONTHNAME(created_at) as month, SUM(price) as total_amount')
                            ->whereYear('created_at', $year)
                            ->groupBy('month')
                            ->get();

        return response()->json($subscriptions);
    }
}
