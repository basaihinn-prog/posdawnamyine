<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\ExpenseCategoryExport;

class AcnooExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:expenseCategory.view')->only('index');
        $this->middleware('check.permission:expenseCategory.create')->only('store');
        $this->middleware('check.permission:expenseCategory.update')->only('update', 'status');
        $this->middleware('check.permission:expenseCategory.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $expense_categories = ExpenseCategory::where('business_id', auth()->user()->business_id)->latest()->paginate(20);
        return view('restaurantwebaddon::expense-categories.index', compact('expense_categories'));
    }

    public function acnooFilter(Request $request)
    {
        $expense_categories = ExpenseCategory::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('categoryName', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::expense-categories.datas', compact('expense_categories'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|unique:expense_categories,categoryName,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        ExpenseCategory::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
        ]);

        return response()->json([
            'message' => __('Expense category saved successfully.'),
            'redirect' => route('business.expense-categories.index')
        ]);
    }

    public function update(Request $request, string $id)
    {
        $category = ExpenseCategory::findOrFail($id);

        $request->validate([
            'categoryName' => [
                'required',
                'unique:expense_categories,categoryName,' . $category->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);

        $category->update($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
        ]);

        return response()->json([
            'message' => __('Expense category updated successfully.'),
            'redirect' => route('business.expense-categories.index')
        ]);
    }

    public function destroy(string $id)
    {
        ExpenseCategory::where('id', $id)->delete();

        return response()->json([
            'message' => __('Expense category deleted successfully'),
            'redirect' => route('business.expense-categories.index')
        ]);
    }

    public function status(Request $request, string $id)
    {
        $expense_category = ExpenseCategory::findOrFail($id);
        $expense_category->update(['status' => $request->status]);
        return response()->json(['message' => __('Expense category')]);
    }

    public function deleteAll(Request $request)
    {
        ExpenseCategory::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Selected expense category deleted successfully.'),
            'redirect' => route('business.expense-categories.index'),
        ]);
    }

    public function generatePDF(Request $request)
    {
        $expense_categories = ExpenseCategory::where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::expense-categories.pdf', compact('expense_categories'));
        return $pdf->download('expense-categories.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ExpenseCategoryExport, 'expense-categories.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new ExpenseCategoryExport, 'expense-categories.csv');
    }
}
