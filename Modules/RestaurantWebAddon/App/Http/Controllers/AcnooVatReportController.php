<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Http\Controllers\Controller;
use App\Models\Tax;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\VatReportExport;

class AcnooVatReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:vatReport.view')->only('index');
    }

    public function index()
    {
        $businessId = auth()->user()->business_id;

        $sales = Sale::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $businessId)
            ->where('tax_amount', '>', 0)
            ->latest()
            ->paginate(20);

        $purchases = Purchase::with('party:id,name', 'payment_type:id,name')
            ->where('business_id', $businessId)
            ->where('tax_amount', '>', 0)
            ->latest()
            ->paginate(20);

        $vats = Tax::where('business_id', auth()->user()->business_id)->whereStatus(1)->get();

        $currentPageSales = collect($sales->items());
        $saleVatTotals = $currentPageSales
            ->groupBy('tax_id')
            ->map(fn($group) => $group->sum('tax_amount'));

        return view('restaurantwebaddon::reports.vats.index', compact('sales', 'purchases', 'vats', 'saleVatTotals'));
    }

    public function generatePDF($type = 'all')
    {
        $businessId = auth()->user()->business_id;

        $sales = collect();
        $purchases = collect();

        if ($type === 'sales' || $type === 'all') {
            $sales = Sale::with('party:id,name', 'payment_type:id,name')
                ->where('business_id', $businessId)
                ->where('tax_amount', '>', 0)
                ->latest()
                ->get();
        }

        if ($type === 'purchases' || $type === 'all') {
            $purchases = Purchase::with('party:id,name', 'payment_type:id,name')
                ->where('business_id', $businessId)
                ->where('tax_amount', '>', 0)
                ->latest()
                ->get();
        }

        $vats = Tax::where('business_id', $businessId)->get();

        $pdf = Pdf::loadView('restaurantwebaddon::reports.vats.pdf', compact('sales', 'purchases', 'vats'));

        return $pdf->download('vat-reports.pdf');
    }

    public function exportExcel($type = 'all')
    {
        return $this->exportFile($type, 'vat-report.xlsx');
    }

    public function exportCsv($type = 'all')
    {
        return $this->exportFile($type, 'vat-report.csv');
    }

    private function exportFile($type, $filename, $format = null)
    {
        $businessId = auth()->user()->business_id;

        $sales = collect();
        $purchases = collect();

        if ($type === 'sales' || $type === 'all') {
            $sales = Sale::with('party:id,name', 'payment_type:id,name')
                ->where('business_id', $businessId)
                ->where('tax_amount', '>', 0)
                ->latest()
                ->get();
        }

        if ($type === 'purchases' || $type === 'all') {
            $purchases = Purchase::with('party:id,name', 'payment_type:id,name')
                ->where('business_id', $businessId)
                ->where('tax_amount', '>', 0)
                ->latest()
                ->get();
        }

        $vats = Tax::where('business_id', $businessId)->get();

        $export = new VatReportExport($sales, $purchases, $vats);

        return Excel::download($export, $filename, $format);
    }
}
