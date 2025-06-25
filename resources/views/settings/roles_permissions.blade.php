@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Manage Roles & Permissions</h1>

    <!-- Display success messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Assign Role to User -->
    <div class="bg-white shadow-md rounded mb-6 p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Assign Role to User</h3>
        <form action="{{ route('settings.assign_role') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="user_id" class="block text-gray-700 font-bold mb-2">Select User:</label>
                <select name="user_id" id="user_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700 font-bold mb-2">Select Role:</label>
                <select name="role" id="role" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Assign Role
                </button>
            </div>
        </form>
    </div>

    <!-- Manage Role Permissions -->
    <div class="bg-white shadow-md rounded p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Manage Role Permissions</h3>
        @foreach($roles as $role)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">{{ ucfirst($role->name) }}</h4>

                <form action="{{ route('settings.update_permissions', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="permissions" class="block text-gray-700 font-bold mb-2">Select Permissions:</label>
                        <select name="permissions[]" id="permissions" multiple class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                    {{ ucfirst($permission->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Update Permissions
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
