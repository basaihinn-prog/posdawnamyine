<?php

namespace App\Http\Controllers\Api;

use App\Models\Modifier;
use Illuminate\Http\Request;
use App\Models\Scopes\DataManager;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcnooModifierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modifiers = Modifier::with([
            'product' => function ($query) {
                $query->withoutGlobalScope(DataManager::class)
                ->select('id','productName');
            },
            'modifier_group:id,name',
            'modifier_group.modifier_group_option:id,modifier_group_id,name'])
            ->when($request->search, function($q) use ($request) {
                $q->whereHas('product', function($q) use ($request) {
                    $q->where('productName', 'like', '%' .$request->search. '%');
                });
                $q->orWhereHas('modifier_group', function($q) use ($request) {
                    $q->where('name', 'like', '%' .$request->search. '%');
                });
            })
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(10);
        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => $modifiers
        ]);
    }


    public function store(Request $request)
    {
       $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'modifier_group_id' => 'required|integer|exists:modifier_groups,id',
            'is_required' => 'nullable|boolean',
            'is_multiple' => 'nullable|boolean'
        ]);

        $businessId = auth()->user()->business_id;

         DB::beginTransaction();

         try {
           // Check if any of the modifier_group_ids are already attached
           $modifier = Modifier::where('business_id', $businessId)
                                ->where('modifier_group_id', $request->modifier_group_id)
                                ->where('product_id', $request->product_id)
                                ->first();
            if ($modifier) {
                if ($modifier) {
                    return response()->json([
                        'message' => 'Some modifier groups are already associated with this product.',
                    ], 422);
                }
            }

            $modifier = Modifier::create([
                'business_id' => auth()->user()->business_id,
                'product_id' => $request->product_id,
                'modifier_group_id' => $request->modifier_group_id,
                'is_required' => $request->is_required,
                'is_multiple' => $request->is_multiple
            ]);

            DB::commit();

            return response()->json([
                    'message' => 'Modifier save successfully',
                    'data' => $modifier
                ]);
         } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollBack();
           return response()->json([
                'message' => 'Something went wrong',
            ]);
         }

    }

    public function show(string $id)
    {
        $modifier = Modifier::with('product:id,productName','modifier_group:id,name', 'modifier_group.modifier_group_option:id,modifier_group_id,name')
        ->findOrFail($id);

        return response()->json([
            'message' => 'data fetched successfully',
            'data' => $modifier
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'modifier_group_id' => 'required|integer|exists:modifier_groups,id',
            'is_required' => 'nullable|boolean',
            'is_multiple' => 'nullable|boolean',
        ]);


        DB::beginTransaction();

        try {
            $modifier = Modifier::findOrFail($id);

             // Step 1: Check for duplicates, excluding current modifier ID
             $duplicate = Modifier::where('business_id', auth()->user()->business_id)
                   ->where('modifier_group_id', $request->modifier_group_id)
                   ->where('product_id', $request->product_id)
                   ->where('id', '!=', $id)
                   ->exists();

            if ($duplicate) {
                return response()->json([
                    'message' => 'One or more modifier groups are already associated with this product.',
                ], 422);
            }

            // Update modifier info
            $modifier->update([
                'product_id' => $request->product_id,
                'modifier_group_id' => $request->modifier_group_id,
                'is_required' => $request->is_required,
                'is_multiple' => $request->is_multiple,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Modifier updated successfully',
                'data' => $modifier
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        Modifier::where('id', $id)->delete();
        return response()->json([
            'message' => 'Modifier deleted successfully',
        ]);
    }
}
