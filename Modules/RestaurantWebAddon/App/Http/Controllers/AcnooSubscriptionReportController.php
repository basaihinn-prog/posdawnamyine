<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\SubscriptionReportExport;

class AcnooSubscriptionReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:subscriptionReport.view')->only('index');
    }

    public function index()
    {
        $subscribers = PlanSubscribe::with('plan:id,subscriptionName', 'gateway:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::reports.subscription-reports.subscription-reports', compact('subscribers'));
    }

    public function acnooFilter(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $search = $request->input('search');

        $subscribersQuery = PlanSubscribe::with('plan:id,subscriptionName', 'gateway:id,name')
            ->where('business_id', $businessId);

        // Default date: Today
        $startDate = Carbon::today()->format('Y-m-d');
        $endDate = Carbon::today()->format('Y-m-d');

        if ($request->custom_days === 'yesterday') {
            $startDate = Carbon::yesterday()->format('Y-m-d');
            $endDate = Carbon::yesterday()->format('Y-m-d');
        } elseif ($request->custom_days === 'last_seven_days') {
            $startDate = Carbon::today()->subDays(6)->format('Y-m-d');
        } elseif ($request->custom_days === 'last_thirty_days') {
            $startDate = Carbon::today()->subDays(29)->format('Y-m-d');
        } elseif ($request->custom_days === 'current_month') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($request->custom_days === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        } elseif ($request->custom_days === 'current_year') {
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
        } elseif ($request->custom_days === 'custom_date' && $request->from_date && $request->to_date) {
            $startDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $endDate = Carbon::parse($request->to_date)->format('Y-m-d');
        }

        $subscribersQuery->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Search filter
        if ($search) {
            $subscribersQuery->where(function ($q) use ($search) {
                $q->where('duration', 'like', '%' . $search . '%')
                    ->orWhereHas('plan', function ($q) use ($search) {
                        $q->where('subscriptionName', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('gateway', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $subscribers = $subscribersQuery->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::reports.subscription-reports.datas', compact('subscribers'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function generatePDF(Request $request)
    {
        $subscribers = PlanSubscribe::with(['plan:id,subscriptionName', 'gateway:id,name'])->where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::reports.subscription-reports.pdf', compact('subscribers'));
        return $pdf->download('subscribers.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new SubscriptionReportExport, 'subscribers.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new SubscriptionReportExport, 'subscribers.csv');
    }

    public function getInvoice($invoice_id)
    {
        $subscriber = PlanSubscribe::with(['plan:id,subscriptionName', 'business:id,companyName,phoneNumber,address', 'gateway:id,name'])->where('business_id', auth()->user()->business_id)->findOrFail($invoice_id);
        return view('restaurantwebaddon::reports.subscription-reports.invoice', compact('subscriber'));
    }
}
