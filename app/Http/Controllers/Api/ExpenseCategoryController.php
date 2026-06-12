<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expense_categories = ExpenseCategory::where('business_id', auth()->user()->business_id)
                                ->when(request('search'), function($query) {
                                    $query->where('categoryName', 'like', '%'.request('search'). '%');
                                })
                                ->latest();

        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $expense_categories = $expense_categories->select('id', 'categoryName')->get();
            $responseData = [
                'data' => $expense_categories,
            ];
        } else {
            $expense_categories = $expense_categories->paginate(10);
            $responseData = $expense_categories;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|unique:expense_categories,categoryName,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        $data = ExpenseCategory::create($request->except('status') + [
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
        $category = ExpenseCategory::findOrFail($id);

        $request->validate([
            'categoryName' => [
                'required',
                'unique:expense_categories,categoryName,' . $category->id . ',id,business_id,' . auth()->user()->business_id,
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
        $category = ExpenseCategory::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
