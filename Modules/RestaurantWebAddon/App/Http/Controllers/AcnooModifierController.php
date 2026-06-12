<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Modifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModifierGroups;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\ModifierExport;

class AcnooModifierController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:itemModifiers.view')->only('index');
        $this->middleware('check.permission:itemModifiers.create')->only('store');
        $this->middleware('check.permission:itemModifiers.update')->only('update');
        $this->middleware('check.permission:itemModifiers.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $products = Product::where('business_id', auth()->user()->business_id)->latest()->get();
        $modifier_groups = ModifierGroups::where('business_id', auth()->user()->business_id)->latest()->get();
        $modifiers = Modifier::with('product:id,productName', 'modifier_group:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::modifiers.index', compact('products', 'modifiers', 'modifier_groups'));
    }

    public function acnooFilter(Request $request)
    {
        $modifiers = Modifier::where('business_id', auth()->user()->business_id)
            ->with('product:id,productName', 'modifier_group:id,name')
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->whereHas('product', function ($q) use ($request) {
                        $q->where('productName', 'like', '%' . $request->search . '%');
                    })
                        ->orWhereHas('modifier_group', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->when(isset($request->is_required), function ($q) use ($request) {
                $q->where('is_required', $request->is_required);
            })
            ->when(isset($request->is_multiple), function ($q) use ($request) {
                $q->where('is_multiple', $request->is_multiple);
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::modifiers.datas', compact('modifiers'))->render()
            ]);
        }
        return redirect(url()->previous());
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
                return response()->json([
                    'message' => 'Some modifier groups are already associated with this product.',
                ], 422);
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
                'message' => __('Modifier save successfully'),
                'redirect' => route('business.modifiers.index')
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong' . $th->getMessage()
            ]);
        }
    }

    public function update(Request $request, string $id)
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
                'message' => __('Modifier updated successfully'),
                'redirect' => route('business.modifiers.index')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong' . $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        Modifier::where('id', $id)->delete();

        return response()->json([
            'message' => __('Modifier deleted successfully'),
            'redirect' => route('business.modifiers.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Modifier::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected modifier deleted successfully'),
            'redirect' => route('business.modifiers.index')
        ]);
    }

    public function generatePDF()
    {
        $modifiers = Modifier::with('product:id,productName', 'modifier_group:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::modifiers.pdf', compact('modifiers'));

        return $pdf->download('modifier-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ModifierExport, 'modifier-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new ModifierExport, 'modifier-list.csv');
    }
}
