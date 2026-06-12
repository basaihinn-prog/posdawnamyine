<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = Unit::where('business_id', auth()->user()->business_id)
        ->when(request('search'), function($query) {
            $query->where('unitName', 'like', '%'.request('search'). '%');
        })
        ->latest();
        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $units = $units->select('id', 'unitName')->get();
            $responseData = [
                'data' => $units,
            ];
        } else {
            $units = $units->paginate(10);
            $responseData = $units;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'unitName' => 'required|unique:units,unitName,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        $data = Unit::create($request->all() + [
                    'business_id' => auth()->user()->business_id
                ]);

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unitName' => [
                'required',
                'unique:units,unitName,' . $unit->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);

        $unit->update($request->all());

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $unit,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
