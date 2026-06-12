<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\BookedTableExport;

class AcnooBookedTableController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:tables.view')->only('index');
    }

    public function index()
    {
        $booked_tables = Table::where('business_id', auth()->user()->business_id)
            ->whereIsBooked(1)
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::booked-tables.index', compact('booked_tables'));
    }

    public function acnooFilter(Request $request)
    {
        $booked_tables = Table::where('business_id', auth()->user()->business_id)
            ->whereIsBooked(1)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('capacity', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::booked-tables.datas', compact('booked_tables'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function generatePDF(Request $request)
    {
        $booked_tables = Table::where('business_id', auth()->user()->business_id)->whereIsBooked(1)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::booked-tables.pdf', compact('booked_tables'));
        return $pdf->download('booked-table-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new BookedTableExport, 'booked-table-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new BookedTableExport, 'booked-table-list.csv');
    }
}
