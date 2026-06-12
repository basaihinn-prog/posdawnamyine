<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class AcnooIngredientController extends Controller
{
    public function index(Request $request)
    {
        $ingredients = Ingredient::where('business_id', auth()->user()->business_id)
        ->when(request('search'), function($query) {
            $query->where('name', 'like', '%'.request('search'). '%');
        })
        ->latest();
        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $ingredients = $ingredients->select('id', 'name')->get();
            $responseData = [
                'data' => $ingredients,
            ];
        } else {
            $ingredients = $ingredients->paginate(10);
            $responseData = $ingredients;
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
            'name' => 'required|unique:ingredients,name,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        $data = Ingredient::create($request->all() + [
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
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => [
                'required',
                'unique:ingredients,name,' . $ingredient->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);

        $ingredient->update($request->all());

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $ingredient,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
