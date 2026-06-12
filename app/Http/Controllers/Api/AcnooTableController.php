<?php

namespace App\Http\Controllers\Api;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooTableController extends Controller
{
    public function index(Request $request)
    {
        $tables = Table::where('business_id', auth()->user()->business_id)
                    ->when(request('search'), function($query) {
                        $query->where('name', 'like', '%'.request('search'). '%')
                            ->orWhere('capacity', 'like', '%'.request('search'). '%');
                    })
                    ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $tables = Table::select('id', 'business_id', 'name', 'is_booked', 'status')
                    ->where('business_id', auth()->user()->business_id)
                    ->where('status', 1)
                    ->get();
            $responseData = [
                'data' => $tables,
            ];
        } else {
            $tables = $tables->paginate(10);
            $responseData = $tables;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tables,name,NULL,id,business_id,' . auth()->user()->business_id,
            'capacity' => 'required|integer'
        ]);

        $data = Table::create($request->all() + [
                    'business_id' => auth()->user()->business_id
                ]);

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $data,
        ]);
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => [
                'required',
                'unique:tables,name,' . $table->id . ',id,business_id,' . auth()->user()->business_id,
            ],
            'capacity' => 'required|integer'
        ]);

        $table->update($request->all());

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $table,
        ]);
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
