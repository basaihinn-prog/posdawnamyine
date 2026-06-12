<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CancelReason;

class AcnooCancelReasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:cancelReason.view')->only('index');
        $this->middleware('check.permission:cancelReason.create')->only('store');
        $this->middleware('check.permission:cancelReason.update')->only('update', 'status');
        $this->middleware('check.permission:cancelReason.delete')->only('destroy', 'deleteAll');
    }

    public function index(Request $request)
    {
        $cancel_reasons = CancelReason::where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(10);

        return view('restaurantwebaddon::cancel-reasons.index', compact('cancel_reasons'));
    }

    public function acnooFilter(Request $request)
    {
        $cancel_reasons = CancelReason::where('business_id', auth()->user()->business_id)
            ->when(request('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('reason', 'like', '%' . $request->search . '%')
                        ->orWhere('type', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('restaurantwebaddon::cancel-reasons.datas', compact('cancel_reasons'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        CancelReason::create($request->except('business_id', 'type') + [
            'type' => 'kot',
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Calcel reason created successfully'),
            'redirect' => route('business.cancel-reasons.index'),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $cancelReason = CancelReason::findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $cancelReason->update($request->except('business_id', 'type') + [
            'type' => 'kot',
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Calcel reason updated successfully'),
            'redirect' => route('business.cancel-reasons.index'),
        ]);
    }

    public function destroy(string $id)
    {
        CancelReason::where('id', $id)->delete();

        return response()->json([
            'message' => __('Calcel reason deleted successfully'),
            'redirect' => route('business.cancel-reasons.index'),
        ]);
    }

    public function status(Request $request, string $id)
    {
        $cancelReason = CancelReason::findOrFail($id);
        $cancelReason->update(['status' => $request->status]);

        return response()->json(['message' => __('Cancel reason')]);
    }

    public function deleteAll(Request $request)
    {
        CancelReason::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => __('Selected cancel reason deleted successfully'),
            'redirect' => route('business.cancel-reasons.index')
        ]);
    }

}
