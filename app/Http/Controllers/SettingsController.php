<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SettingsController extends Controller
{
    // Method to display roles and permissions management interface
    public function index()
    {
        // Retrieve all users, roles, and permissions
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();

        // Pass the data to the view
        return view('settings.index', compact('users', 'roles', 'permissions'));
    }

    // Another method to manage roles and permissions
    public function rolesPermissions()
    {
        // Retrieve all users, roles, and permissions
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();

        // Pass the data to the view
        return view('settings.roles_permissions', compact('users', 'roles', 'permissions'));
    }

    // Method to assign roles to users
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $user->syncRoles([$request->input('role')]);

        return redirect()->route('settings.index')->with('success', 'Role assigned successfully.');
    }

    // Method to update permissions for a role
    public function updatePermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('settings.index')->with('success', 'Permissions updated successfully.');
    }
}
