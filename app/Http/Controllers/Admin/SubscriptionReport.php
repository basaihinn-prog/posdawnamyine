<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubscriptionReportExport;

class SubscriptionReport extends Controller
{
    public function index(Request $request)
    {
        $subscribers = PlanSubscribe::with([
            'plan:id,subscriptionName',
            'business:id,companyName,pictureUrl,business_category_id',
            'business.category:id,name',
            'gateway:id,name'
        ])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->paginate(10);

        return view('admin.reports.subscribers.index', compact('subscribers'));
    }

    public function acnooFilter(Request $request)
    {
        $search = $request->input('search');

        $subscribersQuery = PlanSubscribe::with([
            'plan:id,subscriptionName',
            'business:id,companyName,business_category_id',
            'business.category:id,name',
            'gateway:id,name'
        ]);

        // Set default dates
        $startDate = Carbon::today()->format('Y-m-d');
        $endDate = Carbon::today()->format('Y-m-d');

        switch ($request->custom_days) {
            case 'yesterday':
                $startDate = $endDate = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'last_seven_days':
                $startDate = Carbon::today()->subDays(6)->format('Y-m-d');
                $endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'last_thirty_days':
                $startDate = Carbon::today()->subDays(29)->format('Y-m-d');
                $endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'current_month':
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'current_year':
                $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            case 'custom_date':
                if ($request->from_date && $request->to_date) {
                    $startDate = Carbon::parse($request->from_date)->format('Y-m-d');
                    $endDate = Carbon::parse($request->to_date)->format('Y-m-d');
                }
                break;
        }

        $subscribersQuery->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($search) {
            $subscribersQuery->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('duration', 'like', "%{$search}%");
                    $q->orWhereHas('plan', function ($q) use ($search) {
                        $q->where('subscriptionName', 'like', "%{$search}%");
                    });
                    $q->orWhereHas('gateway', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                    $q->orWhereHas('business', function ($q) use ($search) {
                        $q->where('companyName', 'like', "%{$search}%")
                            ->orWhereHas('category', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            });
                    });
                });
            });
        }

        $subscribers =  $subscribersQuery->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.reports.subscribers.datas', compact('subscribers'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function exportExcel()
    {
        return Excel::download(new SubscriptionReportExport, 'subscription-reports.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new SubscriptionReportExport, 'subscription-reports.csv');
    }
}
