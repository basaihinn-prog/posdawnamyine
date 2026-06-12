<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\AreaExport;

class AcnooAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:Areas.view')->only('index');
        $this->middleware('check.permission:Areas.create')->only('store');
        $this->middleware('check.permission:Areas.update')->only('update', 'status');
        $this->middleware('check.permission:Areas.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $areas = Area::withCount('tables as total_table')
                    ->where('business_id', auth()->user()->business_id)
                    ->latest()
                    ->paginate(20);
        return view('restaurantwebaddon::areas.index', compact('areas'));
    }

    public function acnooFilter(Request $request)
    {
        $areas = Area::withCount('tables as total_table')
            ->where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::areas.datas', compact('areas'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:areas,name,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        Area::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Area added successfully.'),
            'redirect' => route('business.areas.index')
        ]);
    }

    public function update(Request $request, string $id)
    {
        $area = Area::findOrFail($id);
        $request->validate([
            'name' => [
                'required',
                'unique:areas,name,' . $area->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);
        $area = Area::findOrFail($id);

        $area->update($request->except('business_id')+ [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Area updated successfully.'),
            'redirect' => route('business.areas.index')
        ]);
    }


    public function destroy(String $id)
    {
        Area::where('id', $id)->delete();

        return response()->json([
            'message' => __('Area deleted successfully.'),
            'redirect' => route('business.areas.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Area::where('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected Area deleted successfully.'),
            'redirect' => route('business.areas.index')
        ]);
    }

    public function generatePDF(Request $request)
    {
        $areas = Area::withCount('tables as total_table')
                       ->where('business_id', auth()->user()->business_id)
                       ->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::areas.pdf', compact('areas'));
        return $pdf->download('area-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new AreaExport, 'area-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new AreaExport, 'area-list.csv');
    }
}
