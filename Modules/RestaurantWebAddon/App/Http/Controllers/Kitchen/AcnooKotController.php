<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CancelReason;
use App\Models\KotTicket;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Scopes\DataManager;
use App\Traits\DateFilterTrait;

class AcnooKotController extends Controller
{
    use DateFilterTrait;

    public function index(Request $request)
    {
        $cancel_reasons = CancelReason::where('business_id', auth()->user()->business_id)->whereType('kot')->whereStatus(1)->get();

        $kotQuery = Sale::withoutGlobalScope(DataManager::class)
            ->select('id', 'business_id', 'party_id', 'table_id', 'kot_id', 'invoiceNumber', 'saleDate', 'business_gateway_id')
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

        $kotQuery->when($request->status, function ($query) use ($request) {
            $query->whereHas('kot_ticket', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        });

        $kotQuery->when($request->food_type, function ($query) use ($request) {
            $query->whereHas('details.product', function ($q) use ($request) {
                $q->withoutGlobalScope(DataManager::class)
                    ->where('food_type', $request->food_type);
            });
        });

        $duration = $request->custom_days ?: 'today';
        $this->applyDateFilter($kotQuery, $duration, 'saleDate', $request->from_date, $request->to_date);

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

        $kotStatusCounts = Sale::withoutGlobalScope(DataManager::class)
            ->where('business_id', auth()->user()->business_id)
            ->whereNotNull('kot_id')
            ->when($request->custom_days ?: 'today', function ($query, $duration) use ($request) {
                $this->applyDateFilter($query, $duration, 'saleDate', $request->from_date, $request->to_date);
            })
            ->with('kot_ticket')
            ->get()
            ->groupBy(fn($kot) => $kot->kot_ticket?->status ?? 'pending')
            ->map(fn($items) => count($items));

        $kotStatuses = ['pending', 'preparing', 'ready', 'cancelled'];

        $kotCounts = collect($kotStatuses)->mapWithKeys(fn($status) => [
            $status => $kotStatusCounts[$status] ?? 0
        ]);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::kitchen.kots.datas', compact('kots'))->render(),
                'counts' => $kotCounts
            ]);
        }

        return view('restaurantwebaddon::kitchen.kots.index', compact('kots', 'kotCounts', 'cancel_reasons'));
    }

    public function cookingStatus(Request $request, string $id)
    {
        $request->validate([
            'cooking_status' => 'required|in:start,ready',
        ]);

        $saleDetail = SaleDetails::findOrFail($id);
        $saleDetail->update(['cooking_status' => $request->cooking_status]);

        $sale = Sale::withoutGlobalScopes()->with('kot_ticket')->find($saleDetail->sale_id);

        $pendingCount = SaleDetails::where('sale_id', $sale->id)
            ->where('cooking_status', 'pending')
            ->count();

        if ($pendingCount === 0 && $sale->kot_ticket) {
            $sale->kot_ticket->update(['status' => 'preparing']);
        }

        $notReadyCount = SaleDetails::where('sale_id', $sale->id)
            ->where('cooking_status', '!=', 'ready')
            ->count();

        if ($notReadyCount === 0 && $sale->kot_ticket) {
            $sale->kot_ticket->update(['status' => 'ready']);
        }

        return response()->json([
            'success' => true,
            'message' => __('Cooking status updated successfully.')
        ]);
    }

    public function kotStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,cancelled',
            'cancel_reason_id' => 'nullable|integer|exists:cancel_reasons,id',
            'notes' => 'nullable|string'
        ]);

        $kot = KotTicket::findOrFail($id);

        if ($request->status == 'cancelled') {
            $kot->update([
                'status' => $request->status,
                'cancel_reason_id' => $request->cancel_reason_id,
                'notes' => $request->notes
            ]);
        } else {
            $kot->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'message' => __('Kot status updated successfully.')
        ]);
    }

    public function getKotTicket(string $id)
    {
        $kot = Sale::withoutGlobalScope(DataManager::class)
            ->select('id', 'business_id', 'table_id', 'kot_id', 'invoiceNumber', 'saleDate')
            ->with([
                'table:id,name',
                'details:id,quantities,product_id,sale_id',
                'details.product' => function ($query) {
                    $query->withoutGlobalScope(DataManager::class)
                        ->select('id', 'productName');
                },
                'details.detail_options:id,sale_detail_id,option_id',
                'details.detail_options.modifier_group_option:id,name,price'
            ])
            ->where('business_id', auth()->user()->business_id)
            ->whereNotNull('kot_id')
            ->findOrFail($id);

        return view('restaurantwebaddon::kitchen.kots.ticket', compact('kot'));
    }
}
