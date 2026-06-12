<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Helpers\HasUploader;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\StaffExport;

class AcnooStaffController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('check.permission:staff.view')->only('index');
        $this->middleware('check.permission:staff.create')->only('store');
        $this->middleware('check.permission:staff.update')->only('update');
        $this->middleware('check.permission:staff.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $staffs = Staff::where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(20);

        return view('restaurantwebaddon::staffs.index', compact('staffs'));
    }

    public function acnooFilter(Request $request)
    {
        $staffs = Staff::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%')
                        ->orWhere('address', 'like', '%' . $request->search . '%')
                        ->orWhere('designation', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->designation, function ($q) use ($request) {
                $q->where('designation', $request->designation);
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::staffs.datas', compact('staffs'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:1024',
            'name' => 'required|string|max:255',
            'designation' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:500',
        ]);

        Staff::create($request->except('business_id', 'image') + [
            'business_id' => auth()->user()->business_id,
            'image' => $request->image ? $this->upload($request, 'image') : null
        ]);

        return response()->json([
            'message' => __('Staff added successfully'),
            'redirect' => route('business.staffs.index')
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'nullable|image|max:1024',
            'name' => 'required|string|max:255',
            'designation' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:500',
        ]);

        $staff = Staff::findOrFail($id);

        $staff->update($request->except('business_id', 'image') + [
            'image' => $request->image ? $this->upload($request, 'image', $staff->image) : $staff->image
        ]);

        return response()->json([
            'message' => __('Staff updated successfully'),
            'redirect' => route('business.staffs.index')
        ]);
    }

    public function destroy(string $id)
    {
        User::where('staff_id', $id)->delete();
        Staff::where('id', $id)->delete();

        return response()->json([
            'message' => __('Staff deleted successfully'),
            'redirect' => route('business.staffs.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        User::whereIn('staff_id', $request->ids)->delete();
        Staff::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected staff deleted successfully'),
            'redirect' => route('business.staffs.index')
        ]);
    }

    public function generatePDF(Request $request)
    {
        $staffs = Staff::where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::staffs.pdf', compact('staffs'));
        return $pdf->download('staff-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new StaffExport, 'staff-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new StaffExport, 'staff-list.csv');
    }
}
