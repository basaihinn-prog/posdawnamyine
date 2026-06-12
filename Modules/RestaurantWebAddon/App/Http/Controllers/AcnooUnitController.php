<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\UnitExport;

class AcnooUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:units.view')->only('index');
        $this->middleware('check.permission:units.create')->only('store');
        $this->middleware('check.permission:units.update')->only('update', 'status');
        $this->middleware('check.permission:units.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $units = Unit::where('business_id', auth()->user()->business_id)->latest()->paginate(20);
        return view('restaurantwebaddon::units.index', compact('units'));
    }

    public function acnooFilter(Request $request)
    {
        $units = Unit::where('business_id', auth()->user()->business_id)->when(request('search'), function ($q) {
            $q->where(function ($q) {
                $q->where('unitName', 'like', '%' . request('search') . '%');
            });
        })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::units.datas', compact('units'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'unitName' => 'required|string|max:255',
        ]);

        Unit::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message'   => __('Unit saved successfully'),
            'redirect'  => route('business.units.index')
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'unitName' => 'required|string|max:255,' . $id,
        ]);

        $units = Unit::find($id);

        $units->update($request->except('business_id'));

        return response()->json([
            'message'   => __('Unit updated successfully'),
            'redirect'  => route('business.units.index')
        ]);
    }

    public function destroy(string $id)
    {
        Unit::where('id', $id)->delete();

        return response()->json([
            'message'   => __('Units deleted successfully'),
            'redirect'  => route('business.units.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Unit::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message'   => __('Selected unit deleted successfully'),
            'redirect'  => route('business.units.index')
        ]);
    }

    public function status(Request $request, string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->update(['status' => $request->status]);
        return response()->json(['message' => __('Unit')]);
    }

    public function generatePDF()
    {
        $units = Unit::where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::units.pdf', compact('units'));

        return $pdf->download('unit-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new UnitExport, 'unit-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new UnitExport, 'unit-list.csv');
    }
}
