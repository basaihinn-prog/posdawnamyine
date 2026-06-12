<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooStaffController extends Controller
{

    public function index()
    {
        $staff = Staff::where('business_id', auth()->user()->business_id)
                ->when(request('search'), function($q) {
                    $q->where('name', 'like', '%' .request('search').'%')
                      ->where('email', 'like', '%' .request('search').'%')
                      ->where('phone', 'like', '%' .request('search').'%');
                })
                ->when(request('designation'), function($q) {
                    $q->where('designation', request('designation'));
                })
                ->latest()
                ->paginate(10);

                return response()->json([
                    'message' => 'Staff fetched successfully',
                    'data' => $staff
                ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

       $staff = Staff::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => 'Staff save successfully',
            'data' => $staff
        ]);
    }

    public function show(string $id)
    {
        $staff = Staff::findOrFail($id);

        return response()->json([
            'message' => 'Staff fetched successfully',
            'data' => $staff
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

       $staff = Staff::findOrFail($id);

       $staff->update($request->except('business_id') + [
            'business_id' => auth()->user()->business_id
        ]);

        return response()->json([
            'message' => 'Staff updated successfully',
            'data' => $staff
        ]);
    }


    public function destroy(string $id)
    {
        User::where('staff_id', $id)->delete();
        Staff::where('id', $id)->delete();

        return response()->json([
            'message' => 'Staff deleted successfully'
        ]);

    }
}
