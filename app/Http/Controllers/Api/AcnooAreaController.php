<?php

namespace App\Http\Controllers\Api;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooAreaController extends Controller
{

    public function index(Request $request)
    {
        $data = Area::withCount('tables as total_table')
                        ->where('business_id', auth()->user()->business_id)
                        ->when($request->search, function ($q) use ($request) {
                            $q->where(function ($q) use ($request) {
                                $q->where('name', 'like', '%' . $request->search . '%');
                            });
                        })
                        ->latest()
                        ->paginate($request->per_page ?? 10);

        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:areas,name,NULL,id,business_id,' . auth()->user()->business_id,
        ]);

        $area = Area::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Area added successfully.'),
            'data' => $area
        ]);
    }

    public function update(Request $request, string $id)
    {
        $area = Area::findOrFail($id);
        $request->validate([
            'name' => [
                'required',
                'unique:areas,name,' . $area->id . ',id,business_id,' . auth()->user()->business_id,
            ],
        ]);
        $area = Area::findOrFail($id);

        $area->update($request->except('business_id')+ [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => __('Area updated successfully.'),
            'data' => $area
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Area::where('id', $id)->delete();

        return response()->json([
            'message' => __('Area deleted successfully.'),
        ]);
    }
}
