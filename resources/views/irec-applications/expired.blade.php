<!-- resources/views/irec-applications/expired.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Expired & Soon Expiring IREC Applications</h1>

    <!-- Display last reminder sent date -->
    @if($lastReminderSent)
        <p class="text-gray-600 mb-4">Last reminder sent on: <span class="font-semibold">{{ \Carbon\Carbon::parse($lastReminderSent)->format('F j, Y') }}</span></p>
    @else
        <p class="text-gray-600 mb-4">No reminders sent yet.</p>
    @endif

    <!-- Button to send email reminders -->
    <div class="mb-6">
        <form action="{{ route('irec-applications.sendReminders') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Send Email Reminders to Soon Expiring Applications
            </button>
        </form>
    </div>

    @if($applications->isEmpty())
        <p>No expired or soon expiring applications found.</p>
    @else
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Application ID</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Proposal Title</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Principal Investigator</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Date of Approval</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Date of Expiration</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Status</th>
                    <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td class="border-t py-2 px-4">{{ $application->id }}</td>
                    <td class="border-t py-2 px-4">{{ $application->proposal_title }}</td>
                    <td class="border-t py-2 px-4">{{ $application->principal_investigator }}</td>
                    <td class="border-t py-2 px-4">{{ $application->date_of_approval }}</td>
                    <td class="border-t py-2 px-4">{{ $application->date_of_renewal->format('F j, Y') }}</td>

                    <td class="border-t py-2 px-4">
                        @if($application->date_of_renewal < now())
                            <span class="text-red-500 font-semibold">Expired</span>
                        @elseif($application->date_of_renewal < now()->addMonths(2))
                            <span class="text-yellow-500 font-semibold">Soon Expiring</span>
                        @endif
                    </td>
                    <td class="border-t py-2 px-4">
                        <a href="{{ route('irec-applications.show', $application->id) }}" class="text-blue-500 hover:underline">View Details</a>
                        <!-- Add more actions here, such as renew, notify, etc. -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
