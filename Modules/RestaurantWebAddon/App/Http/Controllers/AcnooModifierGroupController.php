<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Modifier;
use Illuminate\Http\Request;
use App\Models\ModifierGroups;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModifierGroupOptions;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\ModifierGroupExport;

class AcnooModifierGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:modifierGroups.view')->only('index');
        $this->middleware('check.permission:modifierGroups.create')->only('create', 'store');
        $this->middleware('check.permission:modifierGroups.update')->only('edit', 'update');
        $this->middleware('check.permission:modifierGroups.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $modifier_groups = ModifierGroups::where('business_id', auth()->user()->business_id)
            ->with('modifier_group_option')
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::modifier-groups.index', compact('modifier_groups'));
    }

    public function acnooFilter(Request $request)
    {
        $modifier_groups = ModifierGroups::where('business_id', auth()->user()->business_id)
            ->with('modifier_group_option')
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%')
                        ->orWhereHas('modifier_group_option', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('price', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::modifier-groups.datas', compact('modifier_groups'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function create()
    {
        $products = Product::where('business_id', auth()->user()->business_id)->get();

        return view('restaurantwebaddon::modifier-groups.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'product_id' => 'nullable|array',
            'product_id.*' => 'exists:products,id',
            'option_name' => 'required|array',
            'option_name.*' => 'required',
            'option_price' => 'required|array',
            'option_price.*' => 'required|numeric|min:0|max:99999999.99',
        ]);

        DB::beginTransaction();
        try {

            $modifier_group = ModifierGroups::create($request->except('business_id') + [
                'business_id' => auth()->user()->business_id,
            ]);

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

            if ($request->product_id) {
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
                'message' => __('Modifier group added successfully'),
                'redirect' => route('business.modifier-groups.index')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong' . $th->getMessage()
            ]);
        }
    }

    public function edit(string $id)
    {
        $modifier_group = ModifierGroups::findOrFail($id);
        $products = Product::where('business_id', auth()->user()->business_id)->get();

        return view('restaurantwebaddon::modifier-groups.edit', compact('products', 'modifier_group'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'option_name' => 'required|array',
            'option_name.*' => 'required',
            'option_price' => 'required|array',
            'option_price.*' => 'required|numeric|min:0|max:99999999.99'
        ]);

        $modifier_group = ModifierGroups::findOrFail($id);

        DB::beginTransaction();
        try {

            $modifier_group->update($request->except('business_id'));

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
                'message' => __('Modifier group update successfully'),
                'redirect' => route('business.modifier-groups.index')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
                $th->getMessage()
            ]);
        }
    }

    public function destroy(string $id)
    {
        ModifierGroups::where('id', $id)->delete();

        return response()->json([
            'message' => __('Modifier group deleted successfully'),
            'redirect' => route('business.modifier-groups.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        ModifierGroups::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected modifier group deleted successfully'),
            'redirect' => route('business.modifier-groups.index')
        ]);
    }

    public function generatePDF()
    {
        $modifier_groups = ModifierGroups::where('business_id', auth()->user()->business_id)
            ->with('modifier_group_option')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::modifier-groups.pdf', compact('modifier_groups'));

        return $pdf->download('modifier-groups.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ModifierGroupExport, 'modifier-groups.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new ModifierGroupExport, 'modifier-groups.csv');
    }
}
