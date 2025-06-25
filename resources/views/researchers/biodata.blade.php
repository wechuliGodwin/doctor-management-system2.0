@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-semibold text-gray-800 dark:text-white mb-6">Researcher Biodata</h1>
    
    <form method="POST" action="{{ route('researchers.store') }}" class="space-y-6">
        @csrf
        
        <!-- First Name -->
        <div>
            <label for="first_name" class="block text-gray-700 dark:text-gray-300 mb-2">First Name</label>
            <input type="text" id="first_name" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
        </div>

        <!-- Last Name -->
        <div>
            <label for="last_name" class="block text-gray-700 dark:text-gray-300 mb-2">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-gray-700 dark:text-gray-300 mb-2">Phone</label>
            <input type="text" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
        </div>

        <!-- Institution -->
        <div>
            <label for="institution" class="block text-gray-700 dark:text-gray-300 mb-2">Institution</label>
            <input type="text" id="institution" name="institution" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-6">
            <button type="submit" class="px-6 py-3 bg-[#159ed5] text-white border border-[#159ed5] font-semibold rounded-md shadow-md hover:bg-blue-700 transition ease-in-out duration-150">
                Save Researcher
            </button>

        </div>

    </form>
</div>
@endsection
