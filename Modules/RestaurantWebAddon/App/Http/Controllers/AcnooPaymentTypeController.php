<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\PaymentTypeExport;

class AcnooPaymentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:paymentMethod.view')->only('index');
        $this->middleware('check.permission:paymentMethod.create')->only('store');
        $this->middleware('check.permission:paymentMethod.update')->only('update', 'status');
        $this->middleware('check.permission:paymentMethod.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $paymentTypes = PaymentType::where('business_id', auth()->user()->business_id)->latest()->paginate(20);
        return view('restaurantwebaddon::payment-types.index', compact('paymentTypes'));
    }

    public function acnooFilter(Request $request)
    {
        $paymentTypes = PaymentType::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::payment-types.datas', compact('paymentTypes'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('payment_types')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business_id);
                }),
            ],
        ]);

        $existingActiveCount = PaymentType::where('business_id', auth()->user()->business_id)
            ->where('is_view', 1)
            ->count();

        PaymentType::create($request->except('business_id', 'is_view') + [
            'business_id' => auth()->user()->business_id,
            'is_view' => $existingActiveCount >= 2 ? 0 : 1
        ]);

        return response()->json([
            'message' => __('Payemnt Type created successfully'),
            'redirect' => route('business.payment-types.index'),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $paymentType = PaymentType::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:payment_types,name,' . $paymentType->id . ',id,business_id,' . auth()->user()->business_id,
        ]);

        $existingActiveCount = PaymentType::where('business_id', auth()->user()->business_id)
            ->where('is_view', 1)
            ->count();

        $paymentType->update($request->except('is_view') + [
            'is_view' => $existingActiveCount >= 2 ? 0 : 1
        ]);

        return response()->json([
            'message' => __('Payemnt Type updated successfully'),
            'redirect' => route('business.payment-types.index'),
        ]);
    }

    public function destroy(string $id)
    {
        PaymentType::where('id', $id)->delete();

        return response()->json([
            'message' => __('Payemnt Type deleted successfully'),
            'redirect' => route('business.payment-types.index')
        ]);
    }

    public function status(Request $request, string $id)
    {
        $paymentType = PaymentType::findOrFail($id);
        $paymentType->update(['status' => $request->status]);
        return response()->json(['message' => __('Payemnt Type')]);
    }

    public function deleteAll(Request $request)
    {
        $idsToDelete = $request->input('ids');
        PaymentType::whereIn('id', $idsToDelete)->delete();
        return response()->json([
            'message' => __('Selected Payemnt Type deleted successfully'),
            'redirect' => route('business.payment-types.index')
        ]);
    }

    public function generatePDF(Request $request)
    {
        $paymentTypes = PaymentType::where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::payment-types.pdf', compact('paymentTypes'));
        return $pdf->download('payment-type-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PaymentTypeExport, 'payment-type-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new PaymentTypeExport, 'payment-type-list.csv');
    }
}
