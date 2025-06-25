<!-- resources/views/irec-applications/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Application Details</h1>
    
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">{{ $application->proposal_title }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Researcher Name:</strong>
                @if($application->researcher)
                    {{ $application->researcher->first_name }} {{ $application->researcher->last_name }} ({{ $application->researcher->unique_number }})
                @else
                    <span class="text-gray-500">Researcher not found</span>
                @endif
            </p>

            <p><strong>Principal Investigator:</strong> {{ $application->principal_investigator }}</p>
            <p><strong>Date of Approval:</strong> {{ $application->date_of_approval }}</p>
            <p><strong>Date of Renewal:</strong> {{ $application->date_of_renewal }}</p>
            <p><strong>Days to Renewal:</strong>
                @if (\Carbon\Carbon::parse($application->date_of_renewal)->isPast())
                    <span class="text-red-500">Expired</span>
                @else
                    {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($application->date_of_renewal)) }} days
                @endif
            </p>
            <p><strong>Reference Number Given:</strong> {{ $application->reference_number_given }}</p>
            <p><strong>2024 Reference Number:</strong> {{ $application->reference_number_2024 }}</p>
            <p><strong>Approval Number 2024:</strong> {{ $application->approval_number_2024 }}</p>
            <p><strong>New Resubmission:</strong> {{ $application->new_resubmission }}</p>
            <p><strong>Payment:</strong> {{ $application->payment }}</p>
            <p><strong>End of Study Data:</strong> {{ $application->end_of_study_data }}</p>
            <p><strong>Approval Letter:</strong> {{ $application->approval_letter }}</p>
            <p><strong>KH ISERC Form:</strong> {{ $application->kh_iserc_form }}</p>
            <p><strong>Evaluation 1:</strong> {{ $application->evaluation_1 }}</p>
            <p><strong>Evaluation 2:</strong> {{ $application->evaluation_2 }}</p>
            <p><strong>CVs PI Co-PIs:</strong> {{ $application->cvs_pi_co_pis }}</p>
            <p><strong>CV Co-PI:</strong> {{ $application->cv_co_pi }}</p>
            <p><strong>Human Subjects Data Protection:</strong> {{ $application->human_subjects_data_protection }}</p>
            <p><strong>Annual Report:</strong>
                @if($application->annual_report)
                    <a href="{{ asset('storage/' . $application->annual_report) }}" target="_blank" class="text-blue-500 hover:underline">View Report</a>
                @else
                    <span class="text-gray-500">No Report</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Email section -->
    <div class="mb-6">
        <form method="POST" action="{{ route('irec-applications.sendMail', $application->id) }}">
            @csrf
            <label for="email_template" class="block text-gray-700 mb-2">Select Email Template:</label>
            <select id="email_template" name="email_template" class="w-full mb-4 p-2 border rounded-md">
                <option value="renewal_reminder">Renewal Reminder</option>
                <!-- Add more options here for other templates -->
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition ease-in-out duration-150">Send Email</button>
        </form>
    </div>

    <!-- Form to change the status -->
    <div class="mb-6">
        <form method="POST" action="{{ route('irec-applications.changeStatus', $application->id) }}">
            @csrf
            <label for="status" class="block text-gray-700 mb-2">Change Status:</label>
            <select id="status" name="status" class="w-full mb-4 p-2 border rounded-md">
                <option value="In Progress" {{ $application->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Completed" {{ $application->status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition ease-in-out duration-150">Update Status</button>
        </form>
    </div>

    <div class="text-right">
        <a href="{{ route('irec-applications.current') }}" class="inline-block px-6 py-2 bg-[#159ed5] text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition ease-in-out duration-150">
            Back to Applications
        </a>
    </div>
</div>
@endsection
