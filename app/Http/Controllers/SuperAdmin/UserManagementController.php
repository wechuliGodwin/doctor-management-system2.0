<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // Show the User Management Page
    public function index()
    {
        $users = User::all();
        return view('livewire.UserManagement', compact('users'));
    }

    // Change User Password
    public function changePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_password' => 'required|string|min:8',
        ]);

        $user = User::findOrFail($request->user_id);
        //$user->password = Hash::make($request->new_password);
        $user->forceFill([
            'password' => $request->new_password,
        ])->save();
        $user->save();

        return redirect()->route('superadmin.user_management')->with('success', 'Password updated successfully.');
    }

    // Change User Role
    public function changeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string|in:null,doctor,superadmin',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->role = $request->role === 'null' ? null : $request->role; // Assuming 'role' is a column in the 'users' table
        $user->save();

        return redirect()->route('superadmin.user_management')->with('success', 'Role updated successfully.');
    }
}
