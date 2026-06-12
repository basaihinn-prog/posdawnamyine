<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\IncomeCategory;
use App\Http\Controllers\Controller;

class AcnooIncomeCategoryController extends Controller
{
    public function index(Request $request)
    {
        $income_categories = IncomeCategory::where('business_id', auth()->user()->business_id)
                                ->when(request('search'), function($query) {
                                    $query->where('categoryName', 'like', '%'.request('search'). '%');
                                })
                                ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $income_categories = $income_categories->select('id', 'categoryName')->get();
            $responseData = [
                'data' => $income_categories,
            ];
        } else {
            $income_categories = $income_categories->paginate(10);
            $responseData = $income_categories;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|unique:income_categories,categoryName,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        $data = IncomeCategory::create($request->except('status') + [
                    'business_id' => auth()->user()->business_id,
                    'status' => $request->status == 'true' ? 1 : 0,
                ]);

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $data,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $category = IncomeCategory::findOrFail($id);

        $request->validate([
            'categoryName' => [
                'required',
                'unique:income_categories,categoryName,' . $category->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);

        $category->update($request->except('status') + [
            'business_id' => auth()->user()->business_id,
            'status' => $request->status == 'true' ? 1 : 0,
        ]);

        return response()->json([
            'message' => __('Data updated successfully.'),
            'data' => $category,
        ]);
    }

    public function destroy(string $id)
    {
        $category = IncomeCategory::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
