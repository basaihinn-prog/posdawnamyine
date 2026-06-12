<?php

namespace Modules\RestaurantWebAddon\App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:rolePermission.view')->only('index');
        $this->middleware('check.permission:rolePermission.create')->only('create', 'store');
        $this->middleware('check.permission:rolePermission.update')->only('edit', 'update');
        $this->middleware('check.permission:rolePermission.delete')->only('destroy');
    }

    public function index()
    {
        $users = User::where('business_id', auth()->user()->business_id)->whereIn('role', ['staff', 'chef', 'kitchen'])->latest()->get();

        return view('restaurantwebaddon::roles.index', compact('users'));
    }

    public function create()
    {
        $staffs = Staff::where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        $permissions = config('staff_permissions');

        return view('restaurantwebaddon::roles.create', compact('staffs', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|integer|exists:staff,id',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|min:4|max:15',
        ]);

        $staff = Staff::where('id', $request->staff_id)->first();
        $role = match ($staff->designation) {
            'chef'    => 'chef',
            'kitchen' => 'kitchen',
            default   => 'staff',
        };
        User::create($request->except('business_id') + [
            'business_id' => auth()->user()->business_id,
            'name' => $staff->name,
            'phone' => $staff->phone,
            'role' => $role
        ]);

        return response()->json([
            'message' => __('User role created successfully'),
            'redirect' => route('business.roles.index')
        ]);
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $permissions = config('staff_permissions');
        $staffs = Staff::where('business_id', auth()->user()->business_id)
            ->latest()
            ->get();

        return view('restaurantwebaddon::roles.edit', compact('user', 'permissions', 'staffs'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'staff_id' => 'required|integer|exists:staff,id',
            'email' => 'required|string|unique:users,email,' . $id,
            'password' => 'nullable|min:4|max:15',
        ]);

        $staff = Staff::where('id', $request->staff_id)->first();
        $user = User::findOrFail($id);
        $user->update($request->except('business_id', 'password') + [
            'business_id' => auth()->user()->business_id,
            'name' => $staff->name,
            'phone' => $staff->phone,
            'role' => $staff->designation == 'chef' ? 'chef' : 'staff',
            'password' => $request->password ? Hash::make($request->password) : $user->password
        ]);

        return response()->json([
            'message' => __('User role updated successfully'),
            'redirect' => route('business.roles.index')
        ]);
    }

    public function destroy(string $id)
    {
        User::where('id', $id)->delete();

        return response()->json([
            'message' => __('User role deleted successfully'),
            'redirect' => route('business.roles.index')
        ]);
    }
}
