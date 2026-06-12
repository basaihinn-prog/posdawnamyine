<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\KotTicket;
use App\Models\SaleDetails;
use App\Models\CancelReason;
use Illuminate\Http\Request;
use App\Models\Scopes\DataManager;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcnooKotController extends Controller
{

    public function index()
    {
        $data = Sale::withoutGlobalScope(DataManager::class)
                ->select('id', 'business_id', 'party_id', 'table_id', 'kot_id', 'business_gateway_id', 'payment_type_id', 'invoiceNumber', 'saleDate')
                ->with(['details',
                    'party' => function ($query) {
                        $query->withoutGlobalScope(DataManager::class)
                        ->select('id','name','phone');
                    },
                    'table:id,name',
                    'kot_ticket:id,cancel_reason_id,kot_number,notes,status',
                    'kot_ticket.cancel_reason:id,reason',
                    'payment_type:id,name',
                    'details.product' => function ($query) {
                        $query->withoutGlobalScope(DataManager::class)
                        ->select('id','productName','sales_price','price_type', 'food_type', 'images');
                    },
                    'details.variation:id,name,price',
                    'details.detail_options:id,sale_detail_id,option_id,modifier_id',
                    'details.detail_options.modifier_group_option:id,modifier_group_id,name,price',
                    'details.detail_options.modifier_group_option.modifier_group:id,name'
                ])
                ->withCount(['details as total_item'])
                ->where('business_id', auth()->user()->business_id)
                ->whereNotNull('kot_id')
                ->when(request('status'), function($query) {
                    $query->whereHas('kot_ticket', function($query) {
                        $query->where('status', request('status'));
                    });
                })
                 ->when(request('food_type') && request('food_type') !== 'all', function ($query) {
                    $query->whereHas('details.product', function ($q) {
                        $q->where('food_type', request('food_type'));
                    });
                })
                ->when(request('from_date') || request('to_date'), function ($query) {
                    $query->whereDate('saleDate', '>=', request('from_date'))
                          ->whereDate('saleDate', '<=', request('to_date'));
                })
                ->latest()
                ->paginate(10);

                 $kotStatusCounts = Sale::withoutGlobalScope(DataManager::class)
                                ->where('business_id', auth()->user()->business_id)
                                ->whereNotNull('kot_id')
                                ->when(request('from_date') || request('to_date'), function ($query) {
                                        $query->whereDate('saleDate', '>=', request('from_date'))
                                            ->whereDate('saleDate', '<=', request('to_date'));
                                    })
                                ->with('kot_ticket')
                                ->get()
                                ->groupBy(fn($kot) => $kot->kot_ticket?->status ?? 'pending')
                                ->map(fn($items) => count($items));

                $kotStatuses = ['pending', 'preparing', 'ready', 'cancelled'];

                $kotCounts = collect($kotStatuses)->mapWithKeys(fn($status) => [
                    $status => $kotStatusCounts[$status] ?? 0
                ]);



                // $kotStatusCounts = KotTicket::select('status', DB::raw('COUNT(*) as total'))
                //                 ->where('business_id', auth()->user()->business_id)
                //                 ->groupBy('status')
                //                 ->pluck('total', 'status');

                // $kotStatuses = ['pending', 'preparing', 'ready', 'cancelled'];

                // $kotCounts = collect($kotStatuses)->mapWithKeys(fn ($status) => [
                //     $status => $kotStatusCounts[$status] ?? 0
                // ]);

                return response()->json([
                    'message' => __('Data fetched successfully.'),
                    'data' => $data,
                    'status_count' => $kotCounts
                ]);
    }

  public function cooking_status(Request $request, $id)
    {
        $request->validate([
            'cooking_status' => 'required|in:start,ready',
        ]);

        $saleDetail = SaleDetails::findOrFail($id);
        $saleDetail->update(['cooking_status' => $request->cooking_status]);

        $sale = Sale::withoutGlobalScopes()
                    ->with('kot_ticket')
                    ->find($saleDetail->sale_id);

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
            'message' => __('Cooking status updated successfully.')
        ]);
    }

    public function kot_status(Request $request, string $id)
    {
        $request->validate([
            'cancel_reason_id' => 'nullable|integer|exists:cancel_reasons,id'
        ]);
        $kot_status = KotTicket::findOrFail($id);

        if($request->status == 'cancelled') {
            $kot_status->update([
                 'status' => $request->status,
                 'cancel_reason_id' => $request->cancel_reason_id,
                 'notes' => $request->notes
            ]);
        } else {
            $kot_status->update(['status' => $request->status]);
        }
        return response()->json(['message' => __('Kot status updated successfully.')]);
    }

    public function cancel_reason() {
        $cancel_reason = CancelReason::select('id', 'type', 'reason')
                       ->where('type', 'kot')->latest()->get();
        return response()->json([
            'message' => __('Cooking status updated successfully.'),
            'data' => $cancel_reason
        ]);
    }
}
