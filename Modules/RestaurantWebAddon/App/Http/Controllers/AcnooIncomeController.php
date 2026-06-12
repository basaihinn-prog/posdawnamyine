<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Income;
use App\Models\Business;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use App\Models\IncomeCategory;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\IncomeExport;

class AcnooIncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:income.view')->only('index');
        $this->middleware('check.permission:income.create')->only('store');
        $this->middleware('check.permission:income.update')->only('update');
        $this->middleware('check.permission:income.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $income_categories = IncomeCategory::where('business_id', auth()->user()->business_id)->whereStatus(1)->latest()->get();
        $payment_types = PaymentType::where('business_id', auth()->user()->business_id)->whereStatus(1)->latest()->get();
        $incomes = Income::with('category:id,categoryName', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->paginate(20);

        return view('restaurantwebaddon::incomes.index', compact('incomes', 'income_categories', 'payment_types'));
    }

    public function acnooFilter(Request $request)
    {
        $incomes = Income::with('category:id,categoryName', 'payment_type:id,name')
            ->where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('incomeFor', 'like', '%' . $request->search . '%')
                        ->orWhere('referenceNo', 'like', '%' . $request->search . '%')
                        ->orWhere('incomeDate', 'like', '%' . $request->search . '%')
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('categoryName', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('payment_type', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::incomes.datas', compact('incomes'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0|max:99999999.99',
            'payment_type_id' => 'required|exists:payment_types,id',
            'incomeFor' => 'nullable|string',
            'referenceNo' => 'nullable|string',
            'incomeDate' => 'nullable|string',
            'note' => 'nullable|string',
            'income_category_id' => 'required|exists:income_categories,id',
        ]);

        DB::beginTransaction();
        try {
            $business_id = auth()->user()->business_id;

            Business::findOrFail(auth()->user()->business_id)->increment('remainingShopBalance', $request->amount);

            $income = Income::create($request->except('user_id', 'business_id') + [
                'user_id' => auth()->id(),
                'business_id' => auth()->user()->business_id,
            ]);

            DB::commit();

            sendNotifyToUser($income->id, route('business.incomes.index', ['id' => $income->id]), __('Income has been created.'), $business_id);


            return response()->json([
                'message' => __('Income saved successfully.'),
                'redirect' => route('business.incomes.index')
            ]);
        } catch (\Exception) {
            DB::rollback();
            return response()->json(['message' => __('Somethings went wrong!')], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0|max:99999999.99',
            'payment_type_id' => 'required|exists:payment_types,id',
            'incomeFor' => 'nullable|string',
            'referenceNo' => 'nullable|string',
            'incomeDate' => 'nullable|string',
            'note' => 'nullable|string',
            'income_category_id' => 'required|exists:income_categories,id',
        ]);
        DB::beginTransaction();
        try {
            $business_id = auth()->user()->business_id;

            $income = Income::findOrFail($id);

            $business = Business::findOrFail($business_id);

            $business->increment('remainingShopBalance', $request->amount - $income->amount);

            $income->update($request->except('user_id', 'business_id') + [
                'user_id' => auth()->id(),
                'business_id' => auth()->user()->business_id,
            ]);

            sendNotifyToUser($income->id, route('business.incomes.index', ['id' => $income->id]), __('Income has been updated.'), $business_id);

            DB::commit();

            return response()->json([
                'message' => __('Income updated successfully.'),
                'redirect' => route('business.incomes.index')
            ]);
        } catch (\Exception) {
            DB::rollback();
            return response()->json(['message' => __('Somethings went wrong!')], 404);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $income = Income::findOrFail($id);
            $business = Business::findOrFail(auth()->user()->business_id);

            $business->decrement('remainingShopBalance', $income->amount);

            sendNotifyToUser($income->id, route('business.incomes.index', ['id' => $income->id]), __('Income has been deleted.'), $income->business_id);

            $income->delete();

            DB::commit();

            return response()->json([
                'message' => __('Income deleted successfully'),
                'redirect' => route('business.incomes.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function deleteAll(Request $request)
    {
        DB::beginTransaction();
        try {
            $incomes = Income::whereIn('id', $request->ids)->get();
            $totalAmount = $incomes->sum('amount');
            $business = Business::findOrFail(auth()->user()->business_id);

            $business->decrement('remainingShopBalance', $totalAmount);

            foreach ($incomes as $income) {
                sendNotifyToUser($income->id, route('business.incomes.index', ['id' => $income->id]), __('Income has been deleted.'), $income->business_id);
            }

            Income::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'message' => __('Selected Items deleted successfully.'),
                'redirect' => route('business.incomes.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => __('Something went wrong!')], 404);
        }
    }

    public function generatePDF(Request $request)
    {
        $incomes = Income::with('category:id,categoryName', 'payment_type:id,name')->where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::incomes.pdf', compact('incomes'));
        return $pdf->download('income-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new IncomeExport, 'income-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new IncomeExport, 'income-list.csv');
    }
}
