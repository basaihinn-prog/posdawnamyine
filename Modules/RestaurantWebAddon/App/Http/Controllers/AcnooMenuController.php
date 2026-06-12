<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\MenuExport;

class AcnooMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:menus.view')->only('index');
        $this->middleware('check.permission:menus.create')->only('store');
        $this->middleware('check.permission:menus.update')->only('update');
        $this->middleware('check.permission:menus.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $menus = Menu::where('business_id', auth()->user()->business_id)->latest()->paginate(20);
        return view('restaurantwebaddon::menus.index', compact('menus'));
    }

    public function acnooFilter(Request $request)
    {
        $menus = Menu::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::menus.datas', compact('menus'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Menu::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
        ]);

        return response()->json([
            'message' => __('Menu created cuccessfully'),
            'redirect' => route('business.menus.index'),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $menu->update($request->except('business_id'));

        return response()->json([
            'message' => __('Menu updated successfully'),
            'redirect' => route('business.menus.index'),
        ]);
    }

    public function destroy(string $id)
    {
        Menu::where('id', $id)->delete();

        return response()->json([
            'message' => __('Menu deleted successfully'),
            'redirect' => route('business.menus.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Menu::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected menu deleted successfully'),
            'redirect' => route('business.menus.index')
        ]);
    }

    public function generatePDF()
    {
        $menus = Menu::where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::menus.pdf', compact('menus'));

        return $pdf->download('menu-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new MenuExport, 'menu-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new MenuExport, 'menu-list.csv');
    }
}
