<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooMenuController extends Controller
{

    public function index(Request $request)
    {
        $brands = Menu::where('business_id', auth()->user()->business_id)
        ->when(request('search'), function($query) {
            $query->where('name', 'like', '%'.request('search'). '%');
        })
        ->latest();
        if ($request->has('no_paginate') && $request->no_paginate == true) {
            $brands = $brands->select('id', 'name')->get();
            $responseData = [
                'data' => $brands,
            ];
        } else {
            $brands = $brands->paginate(10);
            $responseData = $brands;
        }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        $data = Menu::create($request->all() + [
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
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => [
                'required',
                'unique:menus,name,' . $menu->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);

        $menu->update($request->all());

        return response()->json([
            'message' => __('Data saved successfully.'),
            'data' => $menu,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json([
            'message' => __('Data deleted successfully.'),
        ]);
    }
}
