@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-semibold text-gray-800 dark:text-white mb-6">New IREC Application</h1>

    @if ($researcher)
        <!-- Display Researcher Biodata -->
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-6">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Researcher Biodata</h2>
            <p class="text-lg text-black mb-2"><strong>Name:</strong> {{ $researcher->first_name }} {{ $researcher->last_name }} ({{ $researcher->unique_number }})</p>
            <p><strong>Email:</strong> {{ $researcher->email }}</p>
            <p><strong>Institution:</strong> {{ $researcher->institution }}</p>
        </div>
    @else
        <p>Researcher data is not available.</p>
    @endif

    <form method="POST" action="{{ route('irec-applications.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Researcher Unique Number -->
        <div class="mb-4">
            <label for="researcher_unique_number" class="block text-gray-700 dark:text-gray-300">Researcher Unique Number</label>
            <input type="text" id="researcher_unique_number" name="researcher_unique_number" value="{{ old('researcher_unique_number', $researcher->unique_number ?? '') }}" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- Date of Approval -->
        <div class="mb-4">
            <label for="date_of_approval" class="block text-gray-700 dark:text-gray-300">Date of Approval</label>
            <input type="date" id="date_of_approval" name="date_of_approval" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- Date of Renewal -->
        <div class="mb-4">
            <label for="date_of_renewal" class="block text-gray-700 dark:text-gray-300">Date of Renewal</label>
            <input type="date" id="date_of_renewal" name="date_of_renewal" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- Reference Number given -->
        <div class="mb-4">
            <label for="reference_number_given" class="block text-gray-700 dark:text-gray-300">Reference Number Given</label>
            <input type="text" id="reference_number_given" name="reference_number_given" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- 2024 Reference Number -->
        <div class="mb-4">
            <label for="reference_number_2024" class="block text-gray-700 dark:text-gray-300">2024 Reference Number</label>
            <input type="text" id="reference_number_2024" name="reference_number_2024" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- 2024 Approval Number -->
        <div class="mb-4">
            <label for="approval_number_2024" class="block text-gray-700 dark:text-gray-300">2024 Approval Number</label>
            <input type="text" id="approval_number_2024" name="approval_number_2024" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- Proposal Title -->
        <div class="mb-4">
            <label for="proposal_title" class="block text-gray-700 dark:text-gray-300">Proposal Title</label>
            <input type="text" id="proposal_title" name="proposal_title" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- Principal Investigator -->
        <div class="mb-4">
            <label for="principal_investigator" class="block text-gray-700 dark:text-gray-300">Principal Investigator</label>
            <input type="text" id="principal_investigator" name="principal_investigator" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- New Resubmission -->
        <div class="mb-4">
            <label for="new_resubmission" class="block text-gray-700 dark:text-gray-300">New Resubmission</label>
            <input type="text" id="new_resubmission" name="new_resubmission" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- Payment -->
        <div class="mb-4">
            <label for="payment" class="block text-gray-700 dark:text-gray-300">Payment</label>
            <input type="text" id="payment" name="payment" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- End of Study Data -->
        <div class="mb-4">
            <label for="end_of_study_data" class="block text-gray-700 dark:text-gray-300">End of Study Data</label>
            <input type="text" id="end_of_study_data" name="end_of_study_data" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- Approval Letter -->
        <div class="mb-4">
            <label for="approval_letter" class="block text-gray-700 dark:text-gray-300">Approval Letter</label>
            <input type="text" id="approval_letter" name="approval_letter" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- KH ISERC Form -->
        <div class="mb-4">
            <label for="kh_iserc_form" class="block text-gray-700 dark:text-gray-300">KH ISERC Form</label>
            <input type="text" id="kh_iserc_form" name="kh_iserc_form" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- Evaluation 1 -->
        <div class="mb-4">
            <label for="evaluation_1" class="block text-gray-700 dark:text-gray-300">Evaluation 1</label>
            <input type="text" id="evaluation_1" name="evaluation_1" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- Evaluation 2 -->
        <div class="mb-4">
            <label for="evaluation_2" class="block text-gray-700 dark:text-gray-300">Evaluation 2</label>
            <input type="text" id="evaluation_2" name="evaluation_2" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- CVs PI Co-PIs -->
        <div class="mb-4">
            <label for="cvs_pi_co_pis" class="block text-gray-700 dark:text-gray-300">CVs PI Co-PIs</label>
            <input type="text" id="cvs_pi_co_pis" name="cvs_pi_co_pis" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- CV Co-PI -->
        <div class="mb-4">
            <label for="cv_co_pi" class="block text-gray-700 dark:text-gray-300">CV Co-PI</label>
            <input type="text" id="cv_co_pi" name="cv_co_pi" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- Human Subjects Data Protection -->
        <div class="mb-4">
            <label for="human_subjects_data_protection" class="block text-gray-700 dark:text-gray-300">Human Subjects Data Protection</label>
            <input type="text" id="human_subjects_data_protection" name="human_subjects_data_protection" class="w-full px-3 py-2 border rounded-md focus:outline-none">
        </div>

        <!-- Annual Report -->
        <div class="mb-4">
            <label for="annual_report" class="block text-gray-700 dark:text-gray-300">Annual Report</label>
            <input type="file" id="annual_report" name="annual_report" class="w-full px-3 py-2 border rounded-md focus:outline-none" required>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-6">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white border border-blue-600 font-semibold rounded-md shadow-md hover:bg-blue-700 transition ease-in-out duration-150">
                Submit Application
            </button>
        </div>
    </form>
</div>
@endsection
