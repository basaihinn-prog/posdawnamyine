<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\QuotationReportExport;

class AcnooQuotationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:salesQuotationReport.view')->only('index');
    }

    public function index()
    {
        $businessId = auth()->user()->business_id;

        $quotations = Quotation::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $businessId)
            ->whereDate('quotationDate', Carbon::today())
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::reports.quotations.index', compact('quotations'));
    }

    public function acnooFilter(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $quotationQuery = Quotation::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $businessId);

        // Default to today
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

        $quotationQuery->whereDate('quotationDate', '>=', $startDate)
            ->whereDate('quotationDate', '<=', $endDate);

        // Search Filter
        if ($request->filled('search')) {
            $quotationQuery->where(function ($query) use ($request) {
                $query->where('invoiceNumber', 'like', '%' . $request->search . '%')
                    ->orWhere('totalAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('discountAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('paidAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('dueAmount', 'like', '%' . $request->search . '%')
                    ->orWhere('tax_amount', 'like', '%' . $request->search . '%')
                    ->orWhereHas('party', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('payment_type', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $quotations = $quotationQuery->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::reports.quotations.datas', compact('quotations'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function generatePDF(Request $request)
    {
        $quotations = Quotation::with('party:id,name', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::reports.quotations.pdf', compact('quotations'));
        return $pdf->download('quotation-reports.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new QuotationReportExport, 'quotation-reports.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new QuotationReportExport, 'quotation-reports.csv');
    }
}
