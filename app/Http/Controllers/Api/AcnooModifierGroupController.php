<?php

namespace App\Http\Controllers\Api;

use App\Models\Modifier;
use Illuminate\Http\Request;
use App\Models\ModifierGroups;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModifierGroupOptions;

class AcnooModifierGroupController extends Controller
{

    public function index(Request $request)
    {
        $modifier_groups = ModifierGroups::where('business_id', auth()->user()->business_id)
                ->with('modifier_group_option')
                ->withCount(['modifiers as total_modifier'])
                ->when(request('search'), function($query) {
                    $query->where('name', 'like', '%'.request('search'). '%');
                })
                ->latest();
                if ($request->has('no_paginate') && $request->no_paginate == true) {
                    $modifier_groups = $modifier_groups->select('id', 'name')->get();
                    $responseData = [
                        'data' => $modifier_groups,
                    ];
                } else {
                    $modifier_groups = $modifier_groups->paginate(10);
                    $responseData = $modifier_groups;
                }

        return response()->json([
            'message' => __('Data fetched successfully.'),
            'data' => $responseData,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'product_id' => 'nullable|array',
                'product_id.*' => 'exists:products,id',
            ],
            [
                'product_id.array' => 'Invalid product selection format.',
                'product_id.*.exists' => 'selected products are invalid.',
            ]
        );

        DB::beginTransaction();
        try {

            $modifier_group = ModifierGroups::create($request->except('business_id') +[
                'business_id' => auth()->user()->business_id,
            ]);

            foreach($request->option_name ?? [] as $index => $name) {
                $price = $request->option_price[$index] ?? 0;
                $is_available = $request->is_available[$index] ?? 0;
                if (!empty($name)) {
                    ModifierGroupOptions::create([
                        'modifier_group_id' => $modifier_group->id,
                        'name' => $name,
                        'price' => $price,
                        'is_available' => $is_available,
                    ]);
                }
            }

            if($request->product_id) {
                foreach ($request->product_id ?? [] as $productId) {
                        Modifier::create([
                            'business_id' => auth()->user()->business_id,
                            'modifier_group_id' => $modifier_group->id,
                            'product_id' => $productId,
                        ]);
                    }
            }

            DB::commit();

            return response()->json([
                'message' => 'Modifier Group save successfully',
                'data' => $modifier_group->load('modifier_group_option')
            ]);
        } catch (\Throwable $th) {
           DB::rollBack();
           return response()->json([
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function show(string $id)
    {
        $modifier_group = ModifierGroups::findOrFail($id);
        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => $modifier_group
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $modifier_group = ModifierGroups::findOrFail($id);

        DB::beginTransaction();
        try {

        $modifier_group->update($request->except('business_id') +[
            'business_id' => auth()->user()->business_id,
        ]);

        // Remove old options
        $modifier_group->modifier_group_option()->delete();

        // Re-add updated options
        foreach ($request->option_name ?? [] as $index => $name) {
            $price = $request->option_price[$index] ?? 0;
            $is_available = $request->is_available[$index] ?? 0;

            if (!empty($name)) {
                ModifierGroupOptions::create([
                    'modifier_group_id' => $modifier_group->id,
                    'name' => $name,
                    'price' => $price,
                    'is_available' => $is_available,
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'message' => 'Modifier Group update successfully',
            'data' => $modifier_group->load('modifier_group_option')
        ]);

     } catch(\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
            ]);
        }
    }


    public function destroy(string $id)
    {
        ModifierGroups::where('id', $id)->delete();
        return response()->json([
            'message' => 'Modifier Group deleted successfully',
        ]);
    }
}
