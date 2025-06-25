@extends('layouts.SuperHeader')

@section('content')
<div class="container mx-auto my-12 p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold text-center mb-8" style="color: #159ed5;">Super Admin - User Management</h1>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse bg-white shadow-lg">
            <thead style="background-color: #159ed5" class="text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">User ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            {{ ucfirst($user->roles->pluck('name')->first() ?? 'user') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            <!-- Change Password Button -->
                            <button class="text-blue-500 hover:underline mr-3" onclick="showChangePasswordModal({{ json_encode($user) }})">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                            <!-- Change Role Button -->
                            <button class="text-green-500 hover:underline" onclick="showChangeRoleModal({{ json_encode($user) }})">
                                <i class="fas fa-user-tag"></i> Change Role
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4" style="color: #159ed5;">Change User Password</h2>
        <form id="changePasswordForm" method="POST" action="{{ route('superadmin.change_password') }}">
            @csrf
            <input type="hidden" id="change_password_user_id" name="user_id">
            <div class="mb-4">
                <label for="new_password" class="block text-gray-700">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded mr-2">Save</button>
                <button type="button" class="px-4 py-2 bg-gray-300 text-black rounded" onclick="closeModal('changePasswordModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Change Role Modal -->
<div id="changeRoleModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4" style="color: #159ed5;">Change User Role</h2>
        <form id="changeRoleForm" method="POST" action="{{ route('superadmin.change_role') }}">
            @csrf
            <input type="hidden" id="change_role_user_id" name="user_id">
            <div class="mb-4">
                <label for="role" class="block text-gray-700">Role:</label>
                <select id="role" name="role" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    <option value="">Select Role</option>
                    <option value="doctor">Doctor</option>
                    <option value="null">Patient</option>
                    <option value="superadmin">Super Admin</option>
                </select>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded mr-2">Save</button>
                <button type="button" class="px-4 py-2 bg-gray-300 text-black rounded" onclick="closeModal('changeRoleModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showChangePasswordModal(user) {
        document.getElementById('change_password_user_id').value = user.id;
        document.getElementById('changePasswordModal').classList.remove('hidden');
    }

    function showChangeRoleModal(user) {
        document.getElementById('change_role_user_id').value = user.id;
        document.getElementById('changeRoleModal').classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection
