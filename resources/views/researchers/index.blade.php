<!-- resources/views/researchers/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-semibold text-gray-800 dark:text-white mb-6">Registered Researchers</h1>

    <div class="overflow-x-auto">
        <table class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <thead>
                <tr>
                    <th class="py-3 px-6 bg-[#159ed5] text-white text-left">ID</th>
                    <th class="py-3 px-6 bg-[#159ed5] text-white text-left">Name</th>
                    <th class="py-3 px-6 bg-[#159ed5] text-white text-left">Email</th>
                    <th class="py-3 px-6 bg-[#159ed5] text-white text-left">Institution</th>
                    <th class="py-3 px-6 bg-[#159ed5] text-white text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($researchers as $researcher)
                <tr>
                    <td class="border-t border-gray-200 dark:border-gray-700 py-3 px-6 text-center">{{ $researcher->id }}</td>
                    <td class="border-t border-gray-200 dark:border-gray-700 py-3 px-6 text-center">{{ $researcher->first_name }} {{ $researcher->last_name }}</td>
                    <td class="border-t border-gray-200 dark:border-gray-700 py-3 px-6 text-center">{{ $researcher->email }}</td>
                    <td class="border-t border-gray-200 dark:border-gray-700 py-3 px-6 text-center">{{ $researcher->institution }}</td>
                    <td class="border-t border-gray-200 dark:border-gray-700 py-3 px-6 text-center">
                        <a href="{{ route('researchers.view', $researcher->id) }}" class="text-[#159ed5] hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
