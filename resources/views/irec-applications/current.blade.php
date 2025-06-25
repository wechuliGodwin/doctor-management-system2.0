<!-- resources/views/irec-applications/current.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-semibold text-gray-800 dark:text-black mb-6">Current IREC Applications</h1>
    <table class="min-w-full bg-white dark:bg-gray-800">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold text-left">Application ID</th>
                <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold text-left">Proposal Title</th>
                <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold text-left">Principal Investigator</th>
                <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold text-left">Date of Approval</th>
                <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold text-left">Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
            <tr>
                <td class="border-t py-2 px-4">{{ $application->id }}</td>
                <td class="border-t py-2 px-4">{{ $application->proposal_title }}</td>
                <td class="border-t py-2 px-4">{{ $application->principal_investigator }}</td>
                <td class="border-t py-2 px-4">{{ $application->date_of_approval }}</td>
                <td class="border-t py-2 px-4">
                    <a href="{{ route('irec-applications.show', $application->id) }}" class="text-blue-500 hover:underline">View Details</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
