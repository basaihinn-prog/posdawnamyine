<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\Coupon;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RestaurantWebAddon\App\Exports\CouponExport;

class AcnooCouponController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('check.permission:coupon.view')->only('index');
        $this->middleware('check.permission:coupon.create')->only('store');
        $this->middleware('check.permission:coupon.update')->only('update');
        $this->middleware('check.permission:coupon.delete')->only('destroy', 'deleteAll');
    }

    public function index()
    {
        $coupons = Coupon::where('business_id', auth()->user()->business_id)->latest()->paginate(20);
        return view('restaurantwebaddon::coupons.index', compact('coupons'));
    }

    public function acnooFilter(Request $request)
    {
        $coupons = Coupon::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('code', 'like', '%' . $request->search . '%')
                        ->orWhere('start_date', 'like', '%' . $request->search . '%')
                        ->orWhere('end_date', 'like', '%' . $request->search . '%')
                        ->orWhere('discount', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->status, function ($q) use ($request) {
                $today = now()->format('Y-m-d');

                if ($request->status == 'available') {
                    $q->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today);
                } elseif ($request->status == 'expired') {
                    $q->whereDate('end_date', '<', $today);
                } elseif ($request->status == 'upcoming') {
                    $q->whereDate('start_date', '>', $today);
                }
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::coupons.datas', compact('coupons'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:coupons,code|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount_type' => 'required|in:percentage,flat',
            'discount' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($request->discount_type === 'percentage', 'max:99'),
                Rule::when($request->discount_type === 'flat', 'max:99999999.99'),
            ],
            'image' => 'nullable|image',
            'description' => 'nullable|string',

        ]);

        Coupon::create($request->except('image', 'business_id') + [
            'business_id' => auth()->user()->business_id,
            'image' => $request->image ? $this->upload($request, 'image') : NULL,
        ]);

        return response()->json([
            'message' => __('Coupon created successfully'),
            'redirect' => route('business.coupons.index')
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:coupons,code,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount_type' => 'required|in:percentage,flat',
            'discount' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($request->discount_type === 'percentage', 'max:99'),
                Rule::when($request->discount_type === 'flat', 'max:99999999.99'),
            ],
            'image' => 'nullable|image',
            'description' => 'nullable|string',

        ]);

        $coupon = Coupon::findOrFail($id);

        $coupon->update($request->except('image') + [
            'image' => $request->image ? $this->upload($request, 'image', $coupon->image) : $coupon->image,
        ]);

        return response()->json([
            'message' => __('Coupon update successfully'),
            'redirect' => route('business.coupons.index')
        ]);
    }

    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);

        if (!empty($coupon->image) && Storage::exists($coupon->image)) {
            Storage::delete($coupon->image);
        }
        $coupon->delete();
        return response()->json([
            'message' => __('Coupon deleted successfully.'),
            'redirect' => route('business.coupons.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        $coupons = Coupon::where('id', $request->ids)->get();

        foreach ($coupons as $coupon) {
            if (!empty($coupon->image) && Storage::exists($coupon->image)) {
                Storage::delete($coupon->image);
            }

            $coupon->delete();
        }

        return response()->json([
            'message' => __('Selected coupon deleted successfully.'),
            'redirect' => route('business.coupons.index')
        ]);
    }

    public function generatePDF(Request $request)
    {
        $coupons = Coupon::where('business_id', auth()->user()->business_id)->latest()->get();
        $pdf = Pdf::loadView('restaurantwebaddon::coupons.pdf', compact('coupons'));
        return $pdf->download('coupon-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new CouponExport, 'coupon-list.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new CouponExport, 'coupon-list.csv');
    }
}
