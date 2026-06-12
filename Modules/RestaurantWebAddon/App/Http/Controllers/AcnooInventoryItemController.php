<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\InventoryItemExport;

class AcnooInventoryItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:ingredients.view')->only('index');
        $this->middleware('check.permission:ingredients.create')->only('store');
        $this->middleware('check.permission:ingredients.update')->only('update');
        $this->middleware('check.permission:ingredients.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $items = Ingredient::where('business_id', auth()->user()->business_id)->latest()->paginate(20);

        return view('restaurantwebaddon::items.index', compact('items'));
    }

    public function acnooFilter(Request $request)
    {
        $items = Ingredient::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::items.datas', compact('items'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ingredients,name,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        Ingredient::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Item added successfully.'),
            'redirect' => route('business.items.index')
        ]);
    }

    public function update(Request $request, Ingredient $item)
    {
        $request->validate([
            'name' => 'required',
            'unique:ingredients,name,' . $item->id . ',id,business_id,' . auth()->user()->business_id,
        ]);

        $item->update($request->except('business_id'));

        return response()->json([
            'message' => __('Item updated successfully.'),
            'redirect' => route('business.items.index')
        ]);
    }

    public function destroy(string $id)
    {
        Ingredient::where('id', $id)->delete();

        return response()->json([
            'message' => __('Item deleted successfully.'),
            'redirect' => route('business.items.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Ingredient::where('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected item deleted successfully.'),
            'redirect' => route('business.items.index')
        ]);
    }

    public function generatePDF(Request $request)
    {
        $items = Ingredient::where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::items.pdf', compact('items'));
        return $pdf->download('inventory-item.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new InventoryItemExport, 'inventory-item.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new InventoryItemExport, 'inventory-item.csv');
    }
}
