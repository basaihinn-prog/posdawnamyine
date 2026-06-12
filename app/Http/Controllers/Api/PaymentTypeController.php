<?php

namespace App\Http\Controllers\Api;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PaymentTypeController extends Controller
{
    public function index(Request $request)
    {
        $payment_types = PaymentType::where('business_id', auth()->user()->business_id)
                            ->when(request('search'), function($query) {
                                $query->where('name', 'like', '%'.request('search'). '%');
                            })
                            ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $payment_types = $payment_types->select('id', 'name', 'is_view')->get();
            $responseData = [
                'data' => $payment_types,
            ];
        } else {
            $payment_types = $payment_types->paginate(10);
            $responseData = $payment_types;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('payment_types')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business_id);
                }),
            ],
        ]);

        $existingActiveCount = PaymentType::where('business_id', auth()->user()->business_id)
        ->where('is_view', 1)
        ->count();

        // If already 2 active, don't allow more
        $isView = (int) $request->is_view;

        if ($existingActiveCount >= 2 && $isView === 1) {
            return response()->json([
                'message' => __('Only two payment types can be set as quick view.'),
            ], 403);
        }

        $data = PaymentType::create($request->all() + [
                'business_id' => auth()->user()->business_id
            ]);

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $data,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $payment_type = PaymentType::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:payment_types,name,' . $payment_type->id . ',id,business_id,' . auth()->user()->business_id,
        ]);

        $existingActiveCount = PaymentType::where('business_id', auth()->user()->business_id)
                                ->where('is_view', 1)
                                ->count();

        // If already 2 active, don't allow more
        $isView = (int) $request->is_view;

        if ($existingActiveCount >= 2 && $isView === 1) {
            return response()->json([
                'message' => __('Only two payment types can be set as quick view.'),
            ], 403);
        }

        $payment_type->update($request->all());

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $payment_type,
        ]);
    }

    public function quick_view(Request $request, $id) {

        $payment_type = PaymentType::findOrFail($id);
        $business_id = auth()->user()->business_id;

        $existingActiveCount = PaymentType::where('business_id', $business_id)
                                    ->where('is_view', 1)
                                    ->count();

        // If already 2 active, don't allow more
        $isView = (int) $request->is_view;

        if ($existingActiveCount >= 2 && $isView === 1) {
            return response()->json([
                'message' => __('Only two payment types can be set as quick view.'),
            ], 403);
        }

        $payment_type->update(['is_view' => $request->is_view]);

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $payment_type,
        ]);
    }

    public function destroy(string $id)
    {
        $payment_type = PaymentType::findOrFail($id);
        $payment_type->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
