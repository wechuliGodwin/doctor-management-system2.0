<!-- resources/views/researchers/view.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Researcher Details</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <p class="text-lg text-gray-800 mb-2"><strong>Name:</strong> {{ $researcher->first_name }} {{ $researcher->last_name }} ({{ $researcher->unique_number }})</p>
        <p class="text-lg text-gray-800 mb-2"><strong>Email:</strong> {{ $researcher->email }}</p>
        <p class="text-lg text-gray-800 mb-2"><strong>Institution:</strong> {{ $researcher->institution }}</p>
        <!-- Add other details as necessary -->
    </div>

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Choose Application Type</h2>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('irec-applications.new', ['researcher_id' => $researcher->id]) }}" class="block px-4 py-2 bg-blue-500 text-white rounded-md text-center hover:bg-blue-600">IREC Application</a>
            <a href="#" class="block px-4 py-2 bg-green-500 text-white rounded-md text-center hover:bg-green-600">Research Day Application</a>
            <a href="#" class="block px-4 py-2 bg-purple-500 text-white rounded-md text-center hover:bg-purple-600">Internal Grants Application</a>
            <!-- Add more application types as necessary -->
        </div>
    </div>
</div>
@endsection
