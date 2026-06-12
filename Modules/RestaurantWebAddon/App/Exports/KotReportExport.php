<?php

namespace Modules\RestaurantWebAddon\App\Exports;

use App\Models\Sale;
use App\Models\Scopes\DataManager;
use App\Traits\DateFilterTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KotReportExport implements FromView
{
    use DateFilterTrait;

    public function view(): View
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
        $kotQuery->when(request('status'), function ($query) {
            $query->whereHas('kot_ticket', function ($q) {
                $q->where('status', request('status'));
            });
        });

        // Date filter
        $duration = request('custom_days') ?: 'today';
        $this->applyDateFilter($kotQuery, $duration, 'saleDate', request('from_date'), request('to_date'));

        // Search filter
        $kotQuery->when(request('search'), function ($query) {
            $query->where(function ($q) {
                $q->where('invoiceNumber', 'like', '%' . request('search') . '%')
                    ->orWhereHas('kot_ticket', function ($q) {
                        $q->where('kot_number', 'like', '%' . request('search') . '%');
                    })
                    ->orWhereHas('table', function ($q) {
                        $q->where('name', 'like', '%' . request('search') . '%');
                    })
                    ->orWhereHas('party', function ($q) {
                        $q->withoutGlobalScope(DataManager::class)
                            ->where('name', 'like', '%' . request('search') . '%');
                    });
            });
        });

        $kots = $kotQuery->latest()->limit(request('per_page') ?? 10)->get();

        return view('restaurantwebaddon::kitchen.reports.excel-csv', compact('kots'));
    }
}
