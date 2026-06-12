<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooCategoryController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::where('business_id', auth()->user()->business_id)
        ->when(request('search'), function($query) {
            $query->where('categoryName', 'like', '%'.request('search'). '%');
        })
        ->latest();
         if ($request->has('no_paginate') && $request->no_paginate == true) {
            $categories = $categories->select('id', 'categoryName')->get();
            $responseData = [
                'data' => $categories,
            ];
        } else {
            $categories = $categories->paginate(10);
            $responseData = $categories;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $business_id = auth()->user()->business_id;
        $request->validate([
            'categoryName' => 'required|unique:categories,categoryName,NULL,id,business_id,' . $business_id,
        ]);

        $data = Category::create([
                    'categoryName' => $request->categoryName,
                    'business_id' => $business_id
                ]);

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $data,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'categoryName' => [
                'required',
                'unique:categories,categoryName,' . $category->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);

        $category->update([
                    'categoryName' => $request->categoryName,
                    'business_id' => auth()->user()->business_id
                    ]);

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $category,
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
