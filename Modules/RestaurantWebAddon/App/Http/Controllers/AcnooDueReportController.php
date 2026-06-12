<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\CustomerDueReportExport;

class AcnooDueReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:dueReport.view')->only('index');
    }

    public function index()
    {
        $due_lists = Party::where('business_id', auth()->user()->business_id)
            ->where('type', '!=', 'supplier')
            ->where('due', '>', 0)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::reports.due.due-reports', compact('due_lists'));
    }

    public function acnooFilter(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $search = $request->input('search');

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

        $due_lists = Party::where('business_id', $businessId)
            ->where('type', '!=', 'supplier')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('type', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::reports.due.datas', compact('due_lists'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function generatePDF(Request $request)
    {
        $due_lists = Party::where('business_id', auth()->user()->business_id)->where('type', '!=', 'supplier')->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::reports.due.pdf', compact('due_lists'));
        return $pdf->download('customer-due.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new CustomerDueReportExport, 'customer-due.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new CustomerDueReportExport, 'customer-due.csv');
    }
}
