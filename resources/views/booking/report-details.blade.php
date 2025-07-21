@extends('layouts.dashboard')

@section('title', 'Appointment Report Generator')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .header {
        text-align: center;
        margin-bottom: 24px;
        padding: 16px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .header h1 {
        color: #159ed5;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .filters-section {
        background: white;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #1e293b;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    select,
    input[type="date"] {
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
        background: #fff;
        transition: border-color 0.2s ease;
    }

    select:focus,
    input[type="date"]:focus {
        outline: none;
        border-color: #159ed5;
        box-shadow: 0 0 0 2px rgba(21, 158, 213, 0.1);
    }

    .export-buttons {
        display: flex;
        gap: 8px;
        margin-top: 16px;
        justify-content: center;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #159ed5;
        color: white;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn:hover {
        filter: brightness(110%);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .report-card,
    .table-container {
        background: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .report-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
    }

    .no-data-message {
        text-align: center;
        color: #64748b;
        font-size: 1rem;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        max-height: 400px;
        overflow-y: auto;
        display: block;
    }

    .data-table th,
    .data-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        min-width: 100px;
    }

    .data-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .data-table td {
        vertical-align: middle;
    }

    .data-table tr:hover {
        background: #f1f5f9;
    }

    .filter-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .filter-item {
        background: #f8fafc;
        padding: 12px;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .filter-item p:first-child {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 4px;
    }

    .filter-item p:last-child {
        color: #1e293b;
        font-weight: 500;
    }

    .error-message {
        background: #fee2e2;
        color: #b91c1c;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 24px;
        font-size: 0.9rem;
    }

    .custom-date-range {
        display: none;
    }

    @media (max-width: 992px) {

        .filters-grid,
        .filter-summary {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 12px;
            margin-left: 0;
        }

        .header h1 {
            font-size: 1.5rem;
        }

        .data-table th,
        .data-table td {
            min-width: 80px;
        }
    }
</style>

<div class="dashboard-container">
    @if (session('error'))
    <div class="error-message">
        <strong>Error:</strong> {{ session('error') }}
    </div>
    @endif

    @if (!isset($showReport) || !$showReport)
    <div class="header">
        <h1>
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Appointment Report Generator
        </h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Generate detailed appointment reports by applying filters below. Select your criteria and click "Generate Report" to view the results.
        </p>
    </div>

    <div class="filters-section">
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h2 class="text-xl font-semibold text-gray-900">Report Filters</h2>
        </div>

        <form action="{{ route('booking.detailed-report') }}" method="GET">
            @csrf
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="timePeriod">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Time Period
                    </label>
                    <select id="timePeriod" name="time_period">
                        <option value="day" {{ $timePeriod === 'day' ? 'selected' : '' }}>Today</option>
                        <option value="month" {{ $timePeriod === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $timePeriod === 'year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $timePeriod === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                    @error('time_period')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="filter-group custom-date-range" id="startDateRange" {{ $timePeriod === 'custom' ? '' : 'style=display:none' }}>
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="start_date" value="{{ $startDate }}" class="@error('start_date') border-red-500 @enderror">
                    @error('start_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="filter-group custom-date-range" id="endDateRange" {{ $timePeriod === 'custom' ? '' : 'style=display:none' }}>
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" name="end_date" value="{{ $endDate }}" class="@error('end_date') border-red-500 @enderror">
                    @error('end_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="filter-group">
                    <label for="specialization">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                        Specialization
                    </label>
                    <select id="specialization" name="specialization">
                        <option value="">All Specializations</option>
                        @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" {{ $selectedSpecialization == $specialization->id ? 'selected' : '' }}>{{ $specialization->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="status">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status
                    </label>
                    <select id="status" name="status">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Appointments</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Confirmed (Pending)</option>
                        <option value="patients_seen" {{ $status === 'patients_seen' ? 'selected' : '' }}>Patients Seen</option>
                        <option value="missed" {{ $status === 'missed' ? 'selected' : '' }}>Missed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="rescheduled" {{ $status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                        <!-- <option value="external_pending" {{ $status === 'external_pending' ? 'selected' : '' }}>Pending External</option>
                        <option value="external_approved" {{ $status === 'external_approved' ? 'selected' : '' }}>External Approved</option> -->
                    </select>
                </div>

                @if ($isSuperadmin)
                <div class="filter-group">
                    <label for="hospitalBranch">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4"></path>
                        </svg>
                        Hospital Branch
                    </label>
                    <select id="hospitalBranch" name="branch">
                        <option value="">All Branches</option>
                        @foreach ($hospitalBranches as $branch)
                        <option value="{{ $branch }}" {{ $selectedBranch === $branch ? 'selected' : '' }}>{{ ucfirst($branch) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="filter-group">
                    <label for="doctor">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Doctor
                    </label>
                    <select id="doctor" name="doctor">
                        <option value="">All Doctors</option>
                        @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ $selectedDoctor == $doctor->id ? 'selected' : '' }}>{{ $doctor->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="bookingType">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Booking Type
                    </label>
                    <select id="bookingType" name="booking_type">
                        <option value="">All Types</option>
                        <option value="new" {{ $bookingType === 'new' ? 'selected' : '' }}>New Patient</option>
                        <option value="review" {{ $bookingType === 'review' ? 'selected' : '' }}>Review</option>
                        <option value="post_op" {{ $bookingType === 'post_op' ? 'selected' : '' }}>Post-Op</option>
                        @if ($isSuperadmin || Auth::guard('booking')->user()->hospital_branch === 'kijabe')
                        <option value="external_approved" {{ $bookingType === 'external_approved' ? 'selected' : '' }}>External Approved</option>
                        <option value="external_pending" {{ $bookingType === 'external_pending' ? 'selected' : '' }}>Pending External</option>
                        @endif
                    </select>
                </div>

                <div class="filter-group">
                    <label for="tracingStatus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Tracing Status
                    </label>
                    <select id="tracingStatus" name="tracing_status">
                        <option value="">All Tracing Statuses</option>
                        <option value="contacted" {{ $tracingStatus === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="no response" {{ $tracingStatus === 'no response' ? 'selected' : '' }}>No Response</option>
                        <option value="other" {{ $tracingStatus === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="export-buttons">
                <button type="submit" name="generate_report" value="1" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <div class="report-card">
        <h3 class="report-title">How to Use</h3>
        <ul class="text-gray-600 space-y-1">
            <li>• Select your desired filters from the options above</li>
            <li>• Leave filters empty to include all records for that category</li>
            <li>• Click "Generate Report" to view the filtered results</li>
            <li>• Export the report as CSV for data manipulation</li>
        </ul>
    </div>
    @else
    <div class="report-card">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <a href="{{ route('booking.detailed-report') }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Filters
            </a>

            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 inline-flex items-center gap-2 justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Appointment Report
                </h1>
                <p class="text-gray-600 mt-1 text-sm">
                    Generated on {{ \Carbon\Carbon::now()->format('j F, Y') }} at {{ \Carbon\Carbon::now()->format('h:i A') }}
                </p>
            </div>

            <div class="relative">
                <form id="exportForm" action="{{ route('booking.detailed-report') }}" method="GET">
                    @csrf
                    <input type="hidden" name="time_period" value="{{ $timePeriod }}">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="specialization" value="{{ $selectedSpecialization }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="branch" value="{{ $selectedBranch }}">
                    <input type="hidden" name="doctor" value="{{ $selectedDoctor }}">
                    <input type="hidden" name="booking_type" value="{{ $bookingType }}">
                    <input type="hidden" name="tracing_status" value="{{ $tracingStatus }}">
                    <input type="hidden" name="export_csv" value="1">
                    <button type="submit" id="exportCsvButton" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                        <div id="loader" class="hidden absolute inset-0 flex items-center justify-center bg-blue-600 bg-opacity-75 rounded-lg">
                            <svg class="w-5 h-5 text-white animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="report-card">
        <h3 class="report-title">Applied Filters</h3>
        <div class="filter-summary">
            <div class="filter-item">
                <p>Time Period</p>
                <p>{{ $timePeriod === 'custom' ? ($startDate . ' to ' . $endDate) : ucfirst($timePeriod) }}</p>
            </div>
            <div class="filter-item">
                <p>Specialization</p>
                <p>{{ $selectedSpecialization ? ($specializations->firstWhere('id', $selectedSpecialization)->display_name ?? 'Unknown') : 'All' }}</p>
            </div>
            <div class="filter-item">
                <p>Status</p>
                <p>
                    @switch($status)
                    @case('all') All Appointments @break
                    @case('pending') Confirmed (Pending) @break
                    @case('patients_seen') Patients Seen @break
                    @case('missed') Missed @break
                    @case('cancelled') Cancelled @break
                    @case('rescheduled') Rescheduled @break
                    @case('archived') Archived @break
                    @default Unknown
                    @endswitch
                </p>
            </div>
            @if ($isSuperadmin)
            <div class="filter-item">
                <p>Hospital Branch</p>
                <p>{{ $selectedBranch ? ucfirst($selectedBranch) : 'All' }}</p>
            </div>
            @endif
            <div class="filter-item">
                <p>Doctor</p>
                <p>{{ $selectedDoctor ? ($doctors->firstWhere('id', $selectedDoctor)->display_name ?? 'Unknown') : 'All' }}</p>
            </div>
            <div class="filter-item">
                <p>Booking Type</p>
                <p>
                    @switch($bookingType)
                    @case('new') New Patient @break
                    @case('review') Review @break
                    @case('post_op') Post-Op @break
                    @case('external_pending') Pending External @break
                    @case('external_approved') External Approved @break
                    @default All
                    @endswitch
                </p>
            </div>
            <div class="filter-item">
                <p>Tracing Status</p>
                <p>
                    @switch($tracingStatus)
                    @case('contacted') Contacted @break
                    @case('no response') No Response @break
                    @case('other') Other @break
                    @default All
                    @endswitch
                </p>
            </div>
        </div>
    </div>
    <!-- 
    <div class="report-card">
        <h3 class="report-title">Report Summary</h3>
        <div class="filter-summary">
            <div class="filter-item">
                <p>Total Appointments</p>
                <p>{{ data_get($totals, 'total_appointments', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Confirmed (Pending)</p>
                <p>{{ data_get($totals, 'confirmed_pending', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Patients Seen</p>
                <p>{{ data_get($totals, 'patients_seen', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Missed</p>
                <p>{{ data_get($totals, 'missed', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Cancelled</p>
                <p>{{ data_get($totals, 'cancelled', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Rescheduled</p>
                <p>{{ data_get($totals, 'rescheduled', 0) }}</p>
            </div>
            @if ($isSuperadmin || Auth::guard('booking')->user()->hospital_branch === 'kijabe')
            <div class="filter-item">
                <p>Pending External Approvals</p>
                <p>{{ data_get($totals, 'pending_external_approvals', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>External Approved</p>
                <p>{{ data_get($totals, 'external_approved', 0) }}</p>
            </div>
            @endif
            <div class="filter-item">
                <p>Archived</p>
                <p>{{ data_get($totals, 'archived', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>New Patients</p>
                <p>{{ data_get($totals, 'new_count', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Review</p>
                <p>{{ data_get($totals, 'review_count', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Post-Op</p>
                <p>{{ data_get($totals, 'postop_count', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Traced (Contacted)</p>
                <p>{{ data_get($totals, 'traced_contacted', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Traced (No Response)</p>
                <p>{{ data_get($totals, 'traced_not_contacted', 0) }}</p>
            </div>
            <div class="filter-item">
                <p>Traced (Other)</p>
                <p>{{ data_get($totals, 'traced_other', 0) }}</p>
            </div>
        </div>
    </div> -->

    @if ($isSuperadmin)
    <div class="report-card">
        <h3 class="report-title">Branch-wise Summary</h3>
        @if ($branchData->isEmpty())
        <div class="no-data-message">No branch-wise data available for the selected filters.</div>
        @else
        <div class="filter-summary">
            @foreach ($branchData as $branch)
            <div class="filter-item">
                <p>{{ ucfirst(data_get($branch, 'hospital_branch', 'Unknown')) }}</p>
                <p>{{ data_get($branch, 'total_bookings', 0) }} Bookings</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    @if (empty($appointments))
    <div class="no-data-message">No appointments found for the selected filters.</div>
    @else
    <div class="table-container">
        <h2 class="report-title">Appointment Details (<span id="recordCount">{{ count($appointments) }}</span> records)</h2>
        <div style="overflow-x: auto;">
            <table class="data-table" id="appointmentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Patient Number</th>
                        <th>Email</th>
                        <th>Phone</th>
                        @if ($status === 'rescheduled')
                        <th>Previous Specialization</th>
                        <th>Current Specialization</th>
                        <th>Previous Date</th>
                        <th>Previous Time</th>
                        <th>Current Date</th>
                        <th>Current Time</th>
                        <th>Reason</th>
                        @else
                        <th>Specialization</th>
                        <th>Date</th>
                        <th>Time</th>
                        @endif
                        <th>Doctor</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Tracing Status</th>
                        <th>Tracing Message</th>
                        <th>Tracing Date</th>
                        <th>Notes</th>
                        <th>Doctor Comments</th>
                        @if ($status === 'cancelled')
                        <th>Cancellation Reason</th>
                        @endif
                        <th>Branch</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                    <tr>
                        <td>{{ data_get($appointment, 'appointment_number', '-') }}</td>
                        <td>{{ data_get($appointment, 'full_name', '-') }}</td>
                        <td>{{ data_get($appointment, 'patient_number', '-') }}</td>
                        <td>{{ data_get($appointment, 'email', '-') }}</td>
                        <td>{{ data_get($appointment, 'phone', '-') }}</td>
                        @if ($status === 'rescheduled')
                        <td>{{ data_get($appointment, 'previous_specialization', '-') }}</td>
                        <td>{{ data_get($appointment, 'current_specialization', '-') }}</td>
                        <td>{{ data_get($appointment, 'previous_date', '-') }}</td>
                        <td>{{ data_get($appointment, 'previous_time', '-') }}</td>
                        <td>{{ data_get($appointment, 'current_date', '-') }}</td>
                        <td>{{ data_get($appointment, 'current_time', '-') }}</td>
                        <td>{{ data_get($appointment, 'reason', '-') }}</td>
                        @else
                        <td>{{ data_get($appointment, 'specialization', '-') }}</td>
                        <td>{{ data_get($appointment, 'appointment_date', '-') }}</td>
                        <td>{{ data_get($appointment, 'appointment_time', '-') }}</td>
                        @endif
                        <td>{{ data_get($appointment, 'doctor', '-') }}</td>
                        <td>{{ data_get($appointment, 'booking_type', '-') }}</td>
                        <td>{{ data_get($appointment, 'appointment_status', '-') }}</td>
                        <td>{{ data_get($appointment, 'tracing_status', '-') }}</td>
                        <td>{{ data_get($appointment, 'tracing_message', '-') }}</td>
                        <td>{{ data_get($appointment, 'tracing_date', '-') }}</td>
                        <td>{{ data_get($appointment, 'notes', '-') }}</td>
                        <td>{{ data_get($appointment, 'doctor_comments', '-') }}</td>
                        @if ($status === 'cancelled')
                        <td>{{ data_get($appointment, 'cancellation_reason', '-') }}</td>
                        @endif
                        <td>{{ data_get($appointment, 'hospital_branch', '-') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - initializing time period filter');

        const timePeriodSelect = document.getElementById('timePeriod');
        const startDateGroup = document.getElementById('startDateRange');
        const endDateGroup = document.getElementById('endDateRange');

        if (!timePeriodSelect || !startDateGroup || !endDateGroup) {
            console.error('Required elements not found');
            return;
        }

        function toggleDateRangeFields() {
            const isCustom = timePeriodSelect.value === 'custom';
            console.log('Time period:', timePeriodSelect.value, 'isCustom:', isCustom);

            startDateGroup.style.display = isCustom ? 'block' : 'none';
            endDateGroup.style.display = isCustom ? 'block' : 'none';
        }

        // Initialize on page load
        toggleDateRangeFields();

        // Add event listener
        timePeriodSelect.addEventListener('change', toggleDateRangeFields);

        console.log('Time period filter initialized successfully');
    });
</script>
@endsection