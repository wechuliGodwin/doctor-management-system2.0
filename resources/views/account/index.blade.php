@extends('layouts.auth')

@section('content')

<div class="container mx-auto mt-10 px-4">
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-6"> <!-- Set max width for the card -->
        <h1 class="text-2xl font-semibold mb-6 text-center">Your Profile</h1>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-6">{{ session('success') }}</div>
        @endif

        <form action="{{ route('account.update') }}" method="POST" class="mb-6">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-user mr-2"></i>Name
                </label>
                <input type="text" name="name" id="name" class="mt-1 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <input type="email" name="email" id="email" class="mt-1 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-phone-alt mr-2"></i>Phone Number
                </label>
                <input type="tel" name="phone" id="phone" class="mt-1 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" value="{{ old('phone', $user->phone ?? '') }}">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-lock mr-2"></i>New Password
                </label>
                <input type="password" name="password" id="password" class="mt-1 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" placeholder="Leave blank to keep current password">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-lock mr-2"></i>Confirm New Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" placeholder="Leave blank to keep current password">
            </div>

            <div class="flex space-x-4 mb-4">
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Update Profile</button>
            </div>
        </form>

        <form action="{{ route('account.destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-red-700 transition w-full flex items-center justify-center">
                <i class="fas fa-trash mr-2"></i>Delete Account
            </button>
        </form>
    </div>
</div>
@endsection
