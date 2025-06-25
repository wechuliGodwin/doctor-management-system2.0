<!-- Example: resources/views/irec-applications/expiring.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Expiring IREC Applications</h1>
    @if($expiringApplications->isEmpty())
        <p>No expiring applications found.</p>
    @else
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Application ID</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Proposal Title</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Principal Investigator</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Date of Approval</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Date of Expiration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expiringApplications as $application)
                <tr>
                    <td class="border-t py-2 px-4">{{ $application->id }}</td>
                    <td class="border-t py-2 px-4">{{ $application->proposal_title }}</td>
                    <td class="border-t py-2 px-4">{{ $application->principal_investigator }}</td>
                    <td class="border-t py-2 px-4">{{ $application->date_of_approval }}</td>
                    <td class="border-t py-2 px-4">{{ $application->date_of_renewal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
