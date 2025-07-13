@extends('layouts.dashboard')

@section('title', 'Appointment Report Generator')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="min-h-screen bg-gray-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Error Message -->
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 max-w-7xl mx-auto">
            <strong>Error:</strong> {{ session('error') }}
        </div>
        @endif

        <!-- Filter Page -->
        @if (!isset($showReport) || !$showReport)
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center justify-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Appointment Report Generator
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Generate detailed appointment reports by applying filters below. Select your criteria and click "Generate Report" to view the results.
            </p>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-900">Report Filters</h2>
            </div>

            <form id="filtersForm" action="{{ route('booking.detailed-report') }}" method="GET">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Time Period -->
                    <div class="md:col-span-2">
                        <label for="timePeriod" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Time Period
                        </label>
                        <select id="timePeriod" name="time_period" onchange="toggleCustomDateRange()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="day" {{ $timePeriod === 'day' ? 'selected' : '' }}>Today</option>
                            <option value="month" {{ $timePeriod === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ $timePeriod === 'year' ? 'selected' : '' }}>This Year</option>
                            <option value="custom" {{ $timePeriod === 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div id="startDateRange" class="{{ $timePeriod === 'custom' ? 'block' : 'hidden' }}">
                        <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date
                        </label>
                        <input type="date" id="startDate" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div id="endDateRange" class="{{ $timePeriod === 'custom' ? 'block' : 'hidden' }}">
                        <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">
                            End Date
                        </label>
                        <input type="date" id="endDate" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                            </svg>
                            Specialization
                        </label>
                        <select id="specialization" name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Specializations</option>
                            @foreach ($specializations as $specialization)
                            <option value="{{ $specialization->id }}" {{ $selectedSpecialization == $specialization->id ? 'selected' : '' }}>{{ $specialization->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status
                        </label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Appointments</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Confirmed (Pending)</option>
                            <option value="patients_seen" {{ $status === 'patients_seen' ? 'selected' : '' }}>Patients Seen</option>
                            <option value="missed" {{ $status === 'missed' ? 'selected' : '' }}>Missed</option>
                            <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="rescheduled" {{ $status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            <option value="external_pending" {{ $status === 'external_pending' ? 'selected' : '' }}>Pending External</option>
                            <option value="external_approved" {{ $status === 'external_approved' ? 'selected' : '' }}>External Approved</option>
                        </select>
                    </div>

                    <!-- Hospital Branch -->
                    @if ($isSuperadmin)
                    <div>
                        <label for="hospitalBranch" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4"></path>
                            </svg>
                            Hospital Branch
                        </label>
                        <select id="hospitalBranch" name="branch" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Branches</option>
                            @foreach ($hospitalBranches as $branch)
                            <option value="{{ $branch }}" {{ $selectedBranch === $branch ? 'selected' : '' }}>{{ ucfirst($branch) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Doctor -->
                    <div>
                        <label for="doctor" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Doctor
                        </label>
                        <select id="doctor" name="doctor" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Doctors</option>
                            @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $selectedDoctor == $doctor->id ? 'selected' : '' }}>{{ $doctor->display_name }} - {{ $doctor->department ?? 'No Dept' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Booking Type -->
                    <div>
                        <label for="bookingType" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Booking Type
                        </label>
                        <select id="bookingType" name="booking_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            <option value="new" {{ $bookingType === 'new' ? 'selected' : '' }}>New Patient</option>
                            <option value="review" {{ $bookingType === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="post_op" {{ $bookingType === 'post_op' ? 'selected' : '' }}>Post-Op</option>
                        </select>
                    </div>

                    <!-- Tracing Status -->
                    <div>
                        <label for="tracingStatus" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Tracing Status
                        </label>
                        <select id="tracingStatus" name="tracing_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Tracing Statuses</option>
                            <option value="contacted" {{ $tracingStatus === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="no response" {{ $tracingStatus === 'no response' ? 'selected' : '' }}>No Response</option>
                            <!-- <option value="other" {{ $tracingStatus === 'other' ? 'selected' : '' }}>Other</option> -->
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center gap-4 mt-8">
                    <button type="button" onclick="resetFilters()" class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Reset Filters
                    </button>
                    <button type="submit" name="generate_report" value="1" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Generate Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">How to Use</h3>
            <ul class="text-blue-800 space-y-1">
                <li>• Select your desired filters from the options above</li>
                <li>• Leave filters empty to include all records for that category</li>
                <li>• Click "Generate Report" to view the filtered results</li>
                <li>• Export the report as PDF or print once generated</li>
            </ul>
        </div>
        @else
        <!-- Report Page -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col items-center">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2 justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Appointment Report
                </h1>
                <p class="text-gray-600 mt-1 text-center">
                    Generated on {{ \Carbon\Carbon::now()->format('j F, Y') }} at {{ \Carbon\Carbon::now()->format('h:i A') }}
                </p>
                <div class="flex gap-3 mt-4">
                    <a href="{{ route('booking.detailed-report') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Filters
                    </a>
                    <div class="relative">
                        <button type="button" id="exportPdfButton" onclick="exportPdf()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export PDF
                            <div id="loader" class="hidden absolute inset-0 flex items-center justify-center bg-green-600 bg-opacity-75 rounded-lg">
                                <svg class="w-5 h-5 text-white animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                    <form id="exportForm" action="{{ route('booking.detailed-report') }}" method="GET" style="display: none;">
                        @foreach (request()->all() as $key => $value)
                        @if ($key !== 'export_pdf')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                        @endforeach
                        <input type="hidden" name="export_pdf" value="1">
                    </form>
                </div>
            </div>
        </div>

        <!-- Applied Filters Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Applied Filters</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @if ($startDate || $endDate)
                <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Date Range</p>
                    <p class="font-medium text-blue-900">{{ $startDate }} to {{ $endDate ?: 'Present' }}</p>
                </div>
                @endif
                @if ($selectedSpecialization)
                <div class="bg-green-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Specialization</p>
                    <p class="font-medium text-green-900">{{ \App\Models\BkSpecializations::where('id', $selectedSpecialization)->value('name') }}</p>
                </div>
                @endif
                @if ($status && $status !== 'all')
                <div class="bg-purple-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-medium text-purple-900">{{ ucfirst(str_replace('_', ' ', $status)) }}</p>
                </div>
                @endif
                @if ($selectedBranch)
                <div class="bg-orange-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Hospital Branch</p>
                    <p class="font-medium text-orange-900">{{ ucfirst($selectedBranch) }}</p>
                </div>
                @endif
                @if ($selectedDoctor)
                <div class="bg-pink-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Doctor</p>
                    <p class="font-medium text-pink-900">{{ \App\Models\BkDoctor::where('id', $selectedDoctor)->value('doctor_name') }}</p>
                </div>
                @endif
                @if ($bookingType)
                <div class="bg-indigo-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Booking Type</p>
                    <p class="font-medium text-indigo-900">{{ ucfirst(str_replace('_', '-', $bookingType)) }}</p>
                </div>
                @endif
                @if ($tracingStatus)
                <div class="bg-teal-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Tracing Status</p>
                    <p class="font-medium text-teal-900">{{ ucfirst(str_replace('_', ' ', $tracingStatus)) }}</p>
                </div>
                @endif
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">Total Filtered Records</p>
                    <p class="font-medium text-gray-900">{{ $appointments->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Branch Booking Rates -->
        @if ($isSuperadmin && $branchData->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Rates by Branch</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($branchData->sortByDesc('total_bookings') as $branch)
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-600">{{ ucfirst($branch->hospital_branch) }}</p>
                    <p class="font-medium text-gray-900">{{ $branch->total_bookings }} bookings</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Report Table -->
        @if ($appointments->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center text-gray-600">
            No appointments found for the selected filters.
        </div>
        @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Appointment Details ({{ $appointments->count() }} records)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tracing Status</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tracing Message</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tracing Date</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            @if ($status === 'rescheduled')
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Number</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Specialization</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Date</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            @endif
                            @if ($status === 'cancelled')
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Cancellation Reason</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($appointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $appointment->appointment_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if ($status === 'rescheduled')
                                {{ $appointment->current_date ? \Carbon\Carbon::parse($appointment->current_date)->format('Y-m-d') : '-' }}
                                @if ($appointment->current_time && $appointment->current_time !== '-')
                                <br><span class="text-gray-500">{{ $appointment->current_time }}</span>
                                @endif
                                @else
                                {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '-' }}
                                @if ($appointment->appointment_time && $appointment->appointment_time !== '-')
                                <br><span class="text-gray-500">{{ $appointment->appointment_time }}</span>
                                @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->full_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->doctor ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $status === 'rescheduled' ? ($appointment->current_specialization ?? '-') : ($appointment->specialization ?? '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if (isset($appointment->appointment_status) && in_array($appointment->appointment_status, ['honoured', 'early', 'late']))
                                        bg-green-100 text-green-800
                                    @elseif (isset($appointment->appointment_status) && $appointment->appointment_status === 'missed')
                                        bg-red-100 text-red-800
                                    @elseif (isset($appointment->appointment_status) && $appointment->appointment_status === 'cancelled')
                                        bg-gray-100 text-gray-800
                                    @elseif (isset($appointment->appointment_status) && $appointment->appointment_status === 'rescheduled')
                                        bg-purple-100 text-purple-800
                                    @else
                                        bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ isset($appointment->appointment_status) ? ucfirst($appointment->appointment_status) : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if ($appointment->tracing_status === 'contacted')
                                        bg-teal-100 text-teal-800
                                    @elseif ($appointment->tracing_status === 'no response')
                                        bg-orange-100 text-orange-800
                                    @elseif ($appointment->tracing_status === 'other')
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $appointment->tracing_status ? ucfirst(str_replace('_', ' ', $appointment->tracing_status)) : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->tracing_message ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->tracing_date ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $status === 'rescheduled' ? ($appointment->current_branch ?? '-') : ($appointment->hospital_branch ?? '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $appointment->booking_type ? ucfirst(str_replace('_', '-', $appointment->booking_type)) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->notes ?? '-' }}</td>
                            @if ($status === 'rescheduled')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->previous_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->previous_specialization ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $appointment->previous_date ? \Carbon\Carbon::parse($appointment->previous_date)->format('Y-m-d') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->reason ?? '-' }}</td>
                            @endif
                            @if ($status === 'cancelled')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $appointment->cancellation_reason ?? '-' }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @endif

        <!-- JavaScript for toggling date range and handling export -->
        <script>
            function toggleCustomDateRange() {
                const timePeriod = document.getElementById('timePeriod').value;
                document.getElementById('startDateRange').style.display = timePeriod === 'custom' ? 'block' : 'none';
                document.getElementById('endDateRange').style.display = timePeriod === 'custom' ? 'block' : 'none';
            }

            function exportPdf() {
                const button = document.getElementById('exportPdfButton');
                const loader = document.getElementById('loader');
                const form = document.getElementById('exportForm');

                // Show loader and disable button
                loader.classList.remove('hidden');
                button.disabled = true;

                // Submit the form to initiate PDF download
                form.submit();
                // Note: Loader persists until the server responds with the PDF download.
                // Since form submission may redirect or trigger a download, we do not attempt to hide the loader client-side.
            }

            function resetFilters() {
                document.getElementById('filtersForm').reset();
                toggleCustomDateRange();
            }

            toggleCustomDateRange();
        </script>

        <!-- Print-specific styles -->
        <style>
            @media print {
                .filters-section,
                .action-buttons,
                .info-section,
                .back-button,
                .export-buttons {
                    display: none;
                }

                .report-container {
                    box-shadow: none;
                    border: none;
                }

                table {
                    font-size: 0.8rem;
                }
            }
        </style>
    </div>
</div>
@endsection