<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Scopes\DataManager;
use App\Traits\DateFilterTrait;
use App\Traits\DateRangeTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\KotReportExport;

class AcnooKotReportController extends Controller
{
    use DateFilterTrait, DateRangeTrait;

    public function index(Request $request)
    {
        $kotQuery = Sale::withoutGlobalScope(DataManager::class)
            ->select('id','business_id', 'party_id', 'table_id', 'kot_id', 'invoiceNumber', 'saleDate')
            ->with([
                'details',
                'party' => function ($query) {
                    $query->withoutGlobalScope(DataManager::class)
                        ->select('id', 'name');
                },
                'table:id,name',
                'kot_ticket:id,cancel_reason_id,kot_number,status',
                'kot_ticket.cancel_reason:id,reason',
                'details.product' => function ($query) {
                    $query->withoutGlobalScope(DataManager::class)
                        ->select('id', 'productName', 'food_type');
                },
                'details.variation:id,name',
                'details.detail_options:id,sale_detail_id,option_id',
                'details.detail_options.modifier_group_option:id,modifier_group_id,name',
                'details.detail_options.modifier_group_option.modifier_group:id,name'
            ])
            ->withCount(['details as total_item'])
            ->where('business_id', auth()->user()->business_id)
            ->whereNotNull('kot_id');

        // Status filter
        $kotQuery->when($request->status, function ($query) use ($request) {
            $query->whereHas('kot_ticket', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        });

        // Date filter
        $duration = $request->custom_days ?: 'today';
        $this->applyDateFilter($kotQuery, $duration, 'saleDate', $request->from_date, $request->to_date);

        // Search filter
        $kotQuery->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('invoiceNumber', 'like', '%' . $request->search . '%')
                    ->orWhereHas('kot_ticket', function ($q) use ($request) {
                        $q->where('kot_number', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('table', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('party', function ($q) use ($request) {
                        $q->withoutGlobalScope(DataManager::class)
                            ->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        });

        $kots = $kotQuery->latest()->paginate($request->per_page ?? 10)->appends($request->query());

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::kitchen.reports.datas', compact('kots'))->render(),
            ]);
        }

        return view('restaurantwebaddon::kitchen.reports.index', compact('kots'));
    }

    public function exportExcel()
    {
        return Excel::download(new KotReportExport, 'kot-reports.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new KotReportExport, 'kot-reports.csv');
    }

    public function exportPdf(Request $request)
    {
        $kotQuery = Sale::withoutGlobalScope(DataManager::class)
            ->select('id','business_id', 'party_id', 'table_id', 'kot_id', 'invoiceNumber', 'saleDate')
            ->with([
                'details',
                'party' => function ($query) {
                    $query->withoutGlobalScope(DataManager::class)
                        ->select('id', 'name');
                },
                'table:id,name',
                'kot_ticket:id,kot_number,status'
            ])
            ->withCount(['details as total_item'])
            ->where('business_id', auth()->user()->business_id)
            ->whereNotNull('kot_id');

        // Status filter
        $kotQuery->when($request->status, function ($query) use ($request) {
            $query->whereHas('kot_ticket', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        });

        // Date filter
        $duration = $request->custom_days ?: 'today';
        [$from_date, $to_date] = $this->applyDateRange($duration, $request->from_date, $request->to_date);

        $this->applyDateFilter($kotQuery, $duration, 'saleDate', $request->from_date, $request->to_date);

        // Search filter
        $kotQuery->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('invoiceNumber', 'like', '%' . $request->search . '%')
                    ->orWhereHas('kot_ticket', function ($q) use ($request) {
                        $q->where('kot_number', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('table', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('party', function ($q) use ($request) {
                        $q->withoutGlobalScope(DataManager::class)
                            ->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        });

        $kots = $kotQuery->latest()->limit($request->per_page ?? 10)->get();

        $pdf = Pdf::loadView('restaurantwebaddon::kitchen.reports.pdf', compact('kots', 'from_date', 'to_date', 'duration'));

        return $pdf->download('kot-reports.pdf');
    }
}
