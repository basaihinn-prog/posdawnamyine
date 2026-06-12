<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\SaleReportExport;

class AcnooSaleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:salesReport.view')->only('index');
    }

    public function index(Request $request)
    {
        $businessId = auth()->user()->business_id;

        $sales = Sale::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $businessId)
            ->when($request->filter === 'todays', function ($q) {
                $q->whereDate('saleDate', Carbon::today());
            })
            ->when($request->filter === 'yesterdays', function ($q) {
                $q->whereDate('saleDate', Carbon::yesterday());
            })
            ->when($request->filter === 'current_months', function ($q) {
                $q->whereMonth('saleDate', Carbon::now()->month)
                    ->whereYear('saleDate', Carbon::now()->year);
            })
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::reports.sales.sale-reports', compact('sales'));
    }

    public function acnooFilter(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $salesQuery = Sale::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $businessId)
            ->when($request->filter === 'todays', function ($q) {
                $q->whereDate('saleDate', Carbon::today());
            })
            ->when($request->filter === 'yesterdays', function ($q) {
                $q->whereDate('saleDate', Carbon::yesterday());
            })
            ->when($request->filter === 'current_months', function ($q) {
                $q->whereMonth('saleDate', Carbon::now()->month)
                    ->whereYear('saleDate', Carbon::now()->year);
            });

        // Search Filter
        if ($request->filled('search')) {
            $salesQuery->where(function ($query) use ($request) {
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

        $sales = $salesQuery->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::reports.sales.datas', compact('sales'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function generatePDF(Request $request)
    {
        $sales = Sale::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->when($request->filter === 'todays', function ($q) {
                $q->whereDate('saleDate', Carbon::today());
            })
            ->when($request->filter === 'yesterdays', function ($q) {
                $q->whereDate('saleDate', Carbon::yesterday());
            })
            ->when($request->filter === 'current_months', function ($q) {
                $q->whereMonth('saleDate', Carbon::now()->month)
                    ->whereYear('saleDate', Carbon::now()->year);
            })
            ->latest()
            ->get();
        $pdf = Pdf::loadView('restaurantwebaddon::reports.sales.pdf', compact('sales'));
        return $pdf->download('sale-reports.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new SaleReportExport, 'sale-reports.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new SaleReportExport, 'sale-reports.csv');
    }
}
