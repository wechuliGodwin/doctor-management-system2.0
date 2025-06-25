@extends('layouts.app')

@section('content')
<div class="container mx-auto my-10 px-6">
    <div class="bg-gradient-to-r from-[#159ed5] to-[#4ecdc4] text-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6 bg-white rounded-lg text-center">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Enter Access Code</h1>
            <form method="POST" action="{{ route('suppliers.bulkmail.code') }}" class="space-y-4">
                @csrf
                <div>
                    <input type="password" name="code" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]" placeholder="Enter code here" required>
                </div>
                <button type="submit" class="w-full bg-[#159ed5] text-white py-3 rounded-md shadow-md hover:bg-blue-600 transition duration-300">
                    Submit Code
                </button>
            </form>
            @if (session('error'))
                <p class="text-red-600 mt-4">{{ session('error') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection