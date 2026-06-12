<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Staff;

class AcnooRolePermissionController extends Controller
{

    public function index()
    {
        $user = User::with('staff:id,name')
                      ->where('business_id', auth()->user()->business_id)
                      ->whereIn('role', ['staff', 'chef'])
                      ->when(request('search'), function($q) {
                            $q->where('name', 'like', '%'.request('search'). '%');
                      })
                      ->latest()
                      ->paginate(10);

        return response()->json([
            'message' => 'Role and permission data fetched successfully',
            'data' => $user
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|integer|exists:staff,id',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|min:4|max:15',
        ]);

       $staff = Staff::where('id', $request->staff_id)->first();
       $user =  User::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
            'name' => $staff->name,
            'phone' => $staff->phone,
            'role' => $staff->designation === 'chef' ? 'chef' : 'staff'
        ]);

        return response()->json([
            'message' => 'Role and permission store successfully',
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'staff_id' => 'required|integer|exists:staff,id',
            'email' => 'required|string|unique:users,email,'.$id,
            'password' => 'nullable|min:4|max:15',
        ]);

       $staff = Staff::where('id', $request->staff_id)->first();
       $user = User::findOrFail($id);
       $user->update($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
            'name' => $staff->name,
            'phone' => $staff->phone,
            'role' => $staff->designation === 'chef' ? 'chef' : 'staff'
        ]);

        return response()->json([
            'message' => 'Role and permission updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }
}
