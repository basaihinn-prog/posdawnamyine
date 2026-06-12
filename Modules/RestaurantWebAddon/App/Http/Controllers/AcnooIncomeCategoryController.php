<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeCategory;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\IncomeCategoryExport;

class AcnooIncomeCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:incomeCategory.view')->only('index');
        $this->middleware('check.permission:incomeCategory.create')->only('store');
        $this->middleware('check.permission:incomeCategory.update')->only('update', 'status');
        $this->middleware('check.permission:incomeCategory.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $income_categories = IncomeCategory::where('business_id', auth()->user()->business_id)->latest()->paginate(20);
        return view('restaurantwebaddon::income-categories.index', compact('income_categories'));
    }

    public function acnooFilter(Request $request)
    {
        $income_categories = IncomeCategory::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('categoryName', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::income-categories.datas', compact('income_categories'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|unique:income_categories,categoryName,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        IncomeCategory::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
        ]);

        return response()->json([
            'message' => __('Income category saved successfully.'),
            'redirect' => route('business.income-categories.index')
        ]);
    }

    public function update(Request $request, string $id)
    {
        $category = IncomeCategory::findOrFail($id);

        $request->validate([
            'categoryName' => [
                'required',
                'unique:income_categories,categoryName,' . $category->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);

        $category->update($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
        ]);

        return response()->json([
            'message' => __('Income category updated successfully.'),
            'redirect' => route('business.income-categories.index')
        ]);
    }

    public function destroy(string $id)
    {
        IncomeCategory::where('id', $id)->delete();

        return response()->json([
            'message' => __('Income category deleted successfully'),
            'redirect' => route('business.income-categories.index')
        ]);
    }

    public function status(Request $request, string $id)
    {
        $income_category = IncomeCategory::findOrFail($id);
        $income_category->update(['status' => $request->status]);
        return response()->json(['message' => __('Income category')]);
    }

    public function deleteAll(Request $request)
    {
        IncomeCategory::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Selected income category deleted successfully.'),
            'redirect' => route('business.income-categories.index'),
        ]);
    }

    public function generatePDF(Request $request)
    {
        $income_categories = IncomeCategory::where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::income-categories.pdf', compact('income_categories'));
        return $pdf->download('income-categories.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new IncomeCategoryExport, 'income-categories.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new IncomeCategoryExport, 'income-categories.csv');
    }
}
