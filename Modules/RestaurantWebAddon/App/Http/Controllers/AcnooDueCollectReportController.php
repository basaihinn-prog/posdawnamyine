<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\DueCollect;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\DueCollectReportExport;

class AcnooDueCollectReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:dueCollectionReport.view')->only('index');
    }

    public function index()
    {
        $due_collections = DueCollect::with('payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->whereDate('paymentDate', Carbon::today())
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::reports.due-collect.index', compact('due_collections'));
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

        $due_collections = DueCollect::with('payment_type:id,name')
            ->where('business_id', $businessId)
            ->whereDate('paymentDate', '>=', $startDate)
            ->whereDate('paymentDate', '<=', $endDate)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoiceNumber', 'like', '%' . $search . '%')
                        ->orWhere('paymentDate', 'like', '%' . $search . '%')
                        ->orWhere('totalDue', 'like', '%' . $search . '%')
                        ->orWhere('payDueAmount', 'like', '%' . $search . '%')
                        ->orWhereHas('payment_type', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::reports.due-collect.datas', compact('due_collections'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function generatePDF(Request $request)
    {
        $due_collections = DueCollect::with('payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::reports.due-collect.pdf', compact('due_collections'));
        return $pdf->download('due-collection.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new DueCollectReportExport, 'due-collection.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new DueCollectReportExport, 'due-collection.csv');
    }
}
