<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\CategoryExport;

class AcnooCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:categories.view')->only('index');
        $this->middleware('check.permission:categories.create')->only('store');
        $this->middleware('check.permission:categories.update')->only('update', 'status');
        $this->middleware('check.permission:categories.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $categories = Category::where('business_id', auth()->user()->business_id)->latest()->paginate(20);
        return view('restaurantwebaddon::categories.index', compact('categories'));
    }

    public function acnooFilter(Request $request)
    {
        $categories = Category::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('categoryName', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::categories.datas', compact('categories'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $business_id = auth()->user()->business_id;
        $request->validate([
            'categoryName' => 'required|unique:categories,categoryName,NULL,id,business_id,' . $business_id,
        ]);

        Category::create($request->except('business_id') + [
            'business_id' => $business_id
        ]);

        return response()->json([
            'message' => __('Category created successfully'),
            'redirect' => route('business.categories.index'),
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

        $category->update($request->all());

        return response()->json([
            'message' => __('Category updated successfully'),
            'redirect' => route('business.categories.index'),
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => __('Category deleted successfully'),
            'redirect' => route('business.categories.index'),
        ]);
    }

    public function status(Request $request, string $id)
    {
        $status = Category::findOrFail($id);
        $status->update(['status' => $request->status]);
        return response()->json(['message' => __('Category')]);
    }

    public function deleteAll(Request $request)
    {
        Category::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected category deleted successfully'),
            'redirect' => route('business.categories.index')
        ]);
    }

    public function generatePDF()
    {
        $categories = Category::where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('restaurantwebaddon::categories.pdf', compact('categories'));

        return $pdf->download('category-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new CategoryExport, 'category-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new CategoryExport, 'category-list.csv');
    }
}
