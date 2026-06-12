<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\TransactionExport;

class AcnooTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:transactions.view')->only('index');
    }

    public function index()
    {
        $businessId = auth()->user()->business_id;

        $total_sale = Transaction::where('business_id', $businessId)
            ->whereNotNull('sale_id')
            ->whereType('credit')
            ->sum('total_amount');

        $sale_due = Transaction::where('business_id', $businessId)
            ->whereNotNull('sale_id')
            ->whereType('credit')
            ->sum('due_amount');

        $total_purchase = Transaction::where('business_id', $businessId)
            ->whereNotNull('purchase_id')
            ->whereType('debit')
            ->sum('total_amount');

        $purchase_due = Transaction::where('business_id', $businessId)
            ->whereNotNull('purchase_id')
            ->whereType('debit')
            ->sum('due_amount');

        $transactions = Transaction::where('business_id', $businessId)
            ->with('payment_type:id,name')
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::transactions.index', compact('transactions', 'total_sale', 'sale_due', 'total_purchase', 'purchase_due'));
    }

    public function acnooFilter(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $search = $request->input('search');

        $transactions = Transaction::with('payment_type:id,name')
            ->where('business_id', $businessId)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('invoiceNumber', 'like', '%' . $search . '%')
                        ->orWhere('date', 'like', '%' . $search . '%')
                        ->orWhere('total_amount', 'like', '%' . $search . '%')
                        ->orWhere('due_amount', 'like', '%' . $search . '%')
                        ->orWhere('paid_amount', 'like', '%' . $search . '%')
                        ->orWhereHas('payment_type', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::transactions.datas', compact('transactions'))->render()
            ]);
        }

        return redirect(url()->previous());
    }

    public function generatePDF(Request $request)
    {
        $transactions = Transaction::where('business_id', auth()->user()->business_id)->with('payment_type:id,name')->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::transactions.pdf', compact('transactions'));
        return $pdf->download('transactions.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new TransactionExport, 'transaction.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new TransactionExport, 'transaction.csv');
    }
}
