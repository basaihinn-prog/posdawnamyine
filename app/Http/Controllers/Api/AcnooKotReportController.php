<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\Scopes\DataManager;
use App\Http\Controllers\Controller;

class AcnooKotReportController extends Controller
{
    public function index(Request $request)
    {
        $data = Sale::withoutGlobalScope(DataManager::class)
                ->select('id', 'party_id', 'table_id', 'kot_id', 'payment_type_id', 'invoiceNumber', 'saleDate')
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
                    'details.detail_options.modifier_group_option:id,name,price'
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
                ->when(request('search'), function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('invoiceNumber', 'like', '%' . request('search') . '%')
                            ->whereHas('party', function ($query) {
                                $query->where('name', 'like', '%' . request('search') . '%');
                            })
                            ->orWhereHas('details.product', function ($query) {
                                $query->where('food_type', 'like', '%' . request('search') . '%');
                            })
                            ->orWhereHas('kot_ticket', function ($query) {
                                $query->where('kot_number', 'like', '%' . request('search') . '%');
                            });
                    });
                })
                ->latest();
                if ($request->has('no_paginate') && $request->no_paginate == true) {
                    $kots = $data->get();
                    $responseData = [
                        'data' => $kots,
                    ];
                } else {
                    $kots = $data->paginate(10);
                    $responseData = $kots;
                }
                return response()->json([
                    'message' => __('Data fetched successfully.'),
                    'data' => $responseData,
                ]);
    }
}
