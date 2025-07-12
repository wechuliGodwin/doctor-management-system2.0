@extends('layouts.dashboard')

@section('title', $title)

@section('content')
<div class="container-fluid px-0">
    <div class="widget shadow-sm mb-0 border-0 rounded-3">
        <div class="card-header text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top"
            style="background-color: #159ed5;">
            <h4 class="mb-0 d-flex align-items-center">
                <i class="fas fa-table me-2"></i>{{ $title }}
            </h4>
        </div>

        <div class="p-3 bg-light border-bottom">
            <form id="filter-form" class="mb-0">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label small fw-semibold text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>Start Date
                        </label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-control form-control-sm shadow-sm" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label small fw-semibold text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>End Date
                        </label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm shadow-sm"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-primary btn-sm flex-grow-1 shadow-sm" id="apply-filters">
                            <i class="fas fa-filter me-1"></i>Apply Filter
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm" id="reset-filters">
                            <i class="fas fa-undo me-1"></i>Reset
                        </button>
                        <button type="button" id="export-csv-btn" class="btn btn-secondary btn-sm shadow-sm">
                            <i class="fas fa-file-csv me-1"></i>CSV
                        </button>
                        <button type="button" class="btn btn-info btn-sm shadow-sm" onclick="printTable()">
                            <i class="fas fa-print me-1"></i>Print
                        </button>

                    </div>
                </div>
            </form>
        </div>

        @if (session('success'))
        <div class="alert alert-success m-3 rounded-0">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        <div class="d-flex align-items-center mt-2 ms-3 me-3 pb-2">
            @if (in_array($status, ['new', 'review', 'postop', 'external_approved']))
            <div class="action-dropdown me-3">
                <div class="custom-dropdown d-inline-block">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown">
                        <i class="fas fa-cog me-1"></i> Actions
                    </button>
                    <ul class="custom-dropdown-menu" id="actionDropdownMenu">
                        <li><a class="custom-dropdown-item" href="#" id="mark-came">Mark selected as came</a></li>
                        <li><a class="custom-dropdown-item" href="#" id="mark-missed">Mark selected as missed</a></li>
                    </ul>
                </div>
            </div>
            @endif
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label for="search" class="form-label small fw-semibold text-muted mb-0 me-2">
                        <i class="fas fa-search me-1"></i>Search
                    </label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm shadow-sm"
                        value="{{ request('search') }}" placeholder="Search by any detail" style="width: 200px;">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label for="dataTable_length" class="form-label small fw-semibold text-muted mb-0 me-2">
                        <i class="fas fa-list me-1"></i>Show
                    </label>
                    <select id="dataTable_length" class="form-select form-select-sm shadow-sm d-inline-block" style="width: 80px;">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="-1">All</option>
                    </select>
                </div>
            </div>
        </div>

        @include("booking.tables.{$status}", ['appointments' => $appointments, 'specializations' => $specializations])

        <!-- Single Reusable Modal for Viewing/Editing Appointments -->
        <div class="modal fade" id="viewAppointmentModal" tabindex="-1" aria-labelledby="viewAppointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
                    <div class="modal-header" style="background-color: #159ed5; color: white;">
                        <span class="modal-title" id="viewAppointmentModalLabel" style="font-size: 16px;">
                            View Appointment
                        </span>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card compact-card">
                            <div class="card-body">
                                <span class="card-title" style="font-size: 14px; margin-bottom: 0.5rem;">Appointment Details</span>
                                <form method="POST" id="update-form" class="appointment-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="all_details">
                                    <input type="hidden" name="status" id="modal_status">
                                    <input type="hidden" name="source_table" id="modal_source_table">
                                    <input type="hidden" name="branch" id="modal_branch">
                                    <input type="hidden" name="appointment_id" id="modal_appointment_id">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_full_name" class="form-label" style="font-weight: normal;">Patient Name</label>
                                            <input type="text" class="form-control" id="modal_full_name" name="full_name"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_patient_number" class="form-label" style="font-weight: normal;">Patient Number</label>
                                            <input type="text" class="form-control" id="modal_patient_number" name="patient_number"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_email" class="form-label" style="font-weight: normal;">Email</label>
                                            <input type="email" class="form-control" id="modal_email" name="email"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_phone" class="form-label" style="font-weight: normal;">Phone</label>
                                            <input type="text" class="form-control" id="modal_phone" name="phone"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_appointment_date" class="form-label" style="font-weight: normal;">Appointment Date</label>
                                            <input type="date" class="form-control" id="modal_appointment_date" name="appointment_date"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" readonly>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_appointment_time" class="form-label" style="font-weight: normal;">Appointment Time</label>
                                            <input type="time" class="form-control" id="modal_appointment_time" name="appointment_time"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" readonly>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_specialization" class="form-label" style="font-weight: normal;">Specialization</label>
                                            <select class="form-control form-select" id="modal_specialization" name="specialization_disabled"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" disabled>
                                                <option value="">Select Specialization</option>
                                                @foreach($specializations as $specialization)
                                                <option value="{{ $specialization->name }}">{{ $specialization->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="specialization" id="modal_specialization_hidden">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_doctor_name" class="form-label" style="font-weight: normal;">Doctor</label>
                                            @if(Auth::guard('booking')->check() && Auth::guard('booking')->user()->hospital_branch === 'westlands')
                                            <select name="doctor_name" id="modal_doctor_name" class="form-control form-select" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                                <option value="">-- Select a Doctor --</option>
                                                @foreach($doctors ?? [] as $doctor)
                                                <option value="{{ $doctor->doctor_name }}">{{ $doctor->doctor_name }} - ({{ $doctor->department }})</option>
                                                @endforeach
                                            </select>
                                            @else
                                            <input type="text" class="form-control" id="modal_doctor_name" name="doctor_name"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                            @endif
                                            @error('doctor_name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_appointment_status" class="form-label" style="font-weight: normal;">Status</label>
                                            <select class="form-control form-select" id="modal_appointment_status" name="appointment_status"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                                <option value="pending">Pending</option>
                                                <option value="honoured">Honoured</option>
                                                <option value="missed">Missed</option>
                                                <option value="late">Late</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="modal_visit_date" class="form-label" style="font-weight: normal;">Visit Date</label>
                                            <input type="date" class="form-control" id="modal_visit_date" name="visit_date"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                        </div>
                                        <div class="col-md-4 mb-2" id="modal_booking_type_container">
                                            <label for="modal_booking_type" class="form-label" style="font-weight: normal;">Booking Type</label>
                                            <select class="form-control form-select" id="modal_booking_type" name="booking_type"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required>
                                                <option value="new">New</option>
                                                <option value="review">Review</option>
                                                <option value="post_op">Post-Op</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2" id="modal_booking_id_container">
                                            <label for="modal_booking_id" class="form-label" style="font-weight: normal;">Booking ID</label>
                                            <input type="text" class="form-control" id="modal_booking_id" name="booking_id"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                        </div>
                                        <div class="col-md-4 mb-2" id="modal_patient_notified_container">
                                            <label for="modal_patient_notified" class="form-label" style="font-weight: normal;">Notified</label>
                                            <select class="form-control form-select" id="modal_patient_notified" name="patient_notified"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2" id="modal_status_field_container">
                                            <label for="modal_status_field" class="form-label" style="font-weight: normal;">Pending Status</label>
                                            <select class="form-control form-select" id="modal_status_field" name="status_field"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2" id="modal_notes_container">
                                            <label for="modal_notes" class="form-label" style="font-weight: normal;">Notes</label>
                                            <textarea class="form-control" id="modal_notes" name="notes" rows="2"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                                        </div>
                                        <div class="col-md-6 mb-2" id="modal_doctor_comments_container">
                                            <label for="modal_doctor_comments" class="form-label" style="font-weight: normal;">Doctor Comments</label>
                                            <textarea class="form-control" id="modal_doctor_comments" name="doctor_comments" rows="2"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-2" id="modal_cancellation_reason_container">
                                            <label for="modal_cancellation_reason" class="form-label" style="font-weight: normal;">Cancellation Reason</label>
                                            <textarea class="form-control" id="modal_cancellation_reason" name="cancellation_reason" rows="2"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                                        </div>
                                    </div>
                                    <div id="modal_errors" class="alert alert-danger mt-3 d-none"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="modal_footer">
                        <!-- Dynamic buttons will be appended here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Reusable Cancel Modal -->
        <div class="modal fade" id="cancelAppointmentModal" tabindex="-1" aria-labelledby="cancelAppointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
                    <div class="modal-header" style="background-color: #159ed5; color: white;">
                        <span class="modal-title" id="cancelAppointmentModalLabel" style="font-size: 16px;">Cancel Appointment</span>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" id="cancel-form" class="appointment-form">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="status" id="cancel_modal_status">
                        <input type="hidden" name="branch" id="cancel_modal_branch">
                        <input type="hidden" name="appointment_id" id="cancel_modal_appointment_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: normal;">Patient Name</label>
                                <input type="text" class="form-control" id="cancel_modal_full_name" readonly
                                    style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: normal;">Patient Number</label>
                                <input type="text" class="form-control" id="cancel_modal_patient_number" readonly
                                    style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            </div>
                            <div class="mb-3">
                                <label for="cancel_modal_cancellation_reason" class="form-label" style="font-weight: normal;">Cancellation Reason</label>
                                <textarea class="form-control" id="cancel_modal_cancellation_reason" name="cancellation_reason" rows="3"
                                    style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger btn-sm">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reusable Reschedule Modal -->
        <div class="modal fade" id="rescheduleAppointmentModal" tabindex="-1" aria-labelledby="rescheduleAppointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
                    <div class="modal-header" style="background-color: #159ed5; color: white;">
                        <span class="modal-title" id="rescheduleAppointmentModalLabel" style="font-size: 16px;">Reschedule Appointment</span>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" id="reschedule-form" class="appointment-form" action="">
                        @csrf
                        @method('POST')
                        <div class="row p-3">
                            <input type="hidden" name="appointment_id" id="reschedule_appointment_id">
                            <input type="hidden" name="form_type" value="all_details">
                            <input type="hidden" name="hospital_branch"
                                value="{{ Auth::guard('booking')->check() ? Auth::guard('booking')->user()->hospital_branch : 'kijabe' }}">

                            @if ($errors->any())
                            <div class="alert alert-danger m-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if (session('error'))
                            <div class="alert alert-danger m-3">
                                {{ session('error') }}
                                @if (session('suggested_dates'))
                                <p>Suggested alternative dates:</p>
                                <ul>
                                    @foreach (session('suggested_dates') as $date)
                                    <li>{{ $date }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            @endif

                            <div class="col-md-4 mb-2">
                                <label for="reschedule_full_name" class="form-label">Patient Name</label>
                                <input type="text" class="form-control" id="reschedule_full_name" name="full_name" value="{{ old('full_name') }}" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_patient_number" class="form-label">Patient Number</label>
                                <input type="text" class="form-control" id="reschedule_patient_number" name="patient_number" value="{{ old('patient_number') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="reschedule_email" name="email" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="reschedule_phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_appointment_date" class="form-label">Appointment Date</label>
                                <input type="date" class="form-control" id="reschedule_appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_appointment_time" class="form-label">Appointment Time</label>
                                <input type="time" class="form-control" id="reschedule_appointment_time" name="appointment_time" value="{{ old('appointment_time') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_specialization" class="form-label">Specialization</label>
                                <select class="form-control form-select" id="reschedule_specialization" name="specialization" required>
                                    <option value="">Select Specialization</option>
                                    @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->name }}" {{ old('specialization') == $specialization->name ? 'selected' : '' }}>{{ $specialization->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_doctor_name" class="form-label">Doctor</label>
                                @if(Auth::guard('booking')->check() && Auth::guard('booking')->user()->hospital_branch === 'westlands')
                                <select name="doctor_name" id="reschedule_doctor_name" class="form-control form-select" required>
                                    <option value="">-- Select a Doctor --</option>
                                    @foreach($doctors ?? [] as $doctor)
                                    <option value="{{ $doctor->doctor_name }}">{{ $doctor->doctor_name }} - ({{ $doctor->department }})</option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" class="form-control" id="reschedule_doctor_name" name="doctor_name" value="{{ old('doctor_name') }}">
                                @endif
                                @error('doctor_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="reschedule_booking_type" class="form-label">Booking Type</label>
                                <select class="form-control form-select" id="reschedule_booking_type" name="booking_type" required>
                                    <option value="new" {{ old('booking_type') == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="review" {{ old('booking_type') == 'review' ? 'selected' : '' }}>Review</option>
                                    <option value="post_op" {{ old('booking_type') == 'post_op' ? 'selected' : '' }}>Post-Op</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="reschedule_reason" class="form-label">Reschedule Reason</label>
                                <textarea class="form-control" id="reschedule_reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm">Reschedule</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('booking.tracing_modal')
    </div>
</div>

<style>
    .select2-container {
        display: block !important;
        z-index: 1051;
        /* Ensure dropdown appears above modal */
    }

    .select2-dropdown {
        z-index: 1052;
        /* Ensure dropdown menu appears above modal */
    }

    .select2-container .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        /* Match Bootstrap input height */
        padding: 0.375rem 0.75rem;
        font-size: 14px;
        font-family: Arial, sans-serif;
    }

    .btn-outline-secondary {
        border-color: #d1e9f5;
        color: #0d6a9f;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #e6f4fa;
        color: #094d7a;
    }

    .dataTables_wrapper .dataTables_filter {
        display: none;
    }

    .action-dropdown {
        margin-left: 10px;
        display: inline-block;
        vertical-align: middle;
        position: relative;
    }

    .custom-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 200px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 10003;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .custom-dropdown-menu.show {
        display: block;
    }

    .custom-dropdown-item {
        display: block;
        padding: 0.25rem 1rem;
        color: #212529;
        text-decoration: none;
    }

    .custom-dropdown-item:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    table.dataTable.table tbody tr.tracing-contacted {
        background-color: #b3fff2 !important;
        border-left: 5px solid #33b38c !important;
    }

    table.dataTable.table tbody tr.tracing-not-contacted {
        background-color: #ffd6d6 !important;
        border-left: 5px solid #e60000 !important;
    }

    table.dataTable.table tbody tr.tracing-other {
        background-color: #cce0ff !important;
        border-left: 5px solid #3355cc !important;
    }

    table.dataTable.table tbody tr.tracing-contacted td,
    table.dataTable.table tbody tr.tracing-not-contacted td,
    table.dataTable.table tbody tr.tracing-other td {
        background-color: transparent !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>


<script>
    $(document).ready(function() {
        console.log('Document ready, initializing scripts for status: {{ $status }}');
        @if(Auth::guard('booking') -> check() && Auth::guard('booking') -> user() -> hospital_branch === 'westlands')
        // Initialize Select2 when modals are shown
        $('#viewAppointmentModal, #rescheduleAppointmentModal').on('shown.bs.modal', function() {
            $('#modal_doctor_name, #reschedule_doctor_name').select2({
                placeholder: "-- Select a Doctor --",
                allowClear: true,
                width: '100%',
                dropdownParent: $(this) // Attach dropdown to the modal to fix z-index issues
            });
            $(this).find('.select2-container').css('width', '100%');
        });

        // Destroy Select2 when modals are hidden to prevent memory leaks
        $('#viewAppointmentModal, #rescheduleAppointmentModal').on('hidden.bs.modal', function() {
            $('#modal_doctor_name, #reschedule_doctor_name').select2('destroy');
        });

        // Update Select2 value when loading appointment details
        $('#viewAppointmentModal').on('show.bs.modal', function() {
            const doctorName = $('#modal_doctor_name').val();
            $('#modal_doctor_name').val(doctorName).trigger('change');
        });

        // Update Select2 value when opening reschedule modal
        $(document).on('click', '.openRescheduleModal', function() {
            const data = $(this).data();
            $('#reschedule_doctor_name').val(data.doctor_name || '').trigger('change');
        });
        @endif

        // CSRF Token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken) {
            console.error('CSRF token not found.');
            alert('CSRF token missing. Please contact the administrator.');
        }

        // Custom dropdown toggle
        const $dropdownButton = $('#actionDropdown');
        const $dropdownMenu = $('#actionDropdownMenu');
        if ($dropdownButton.length && $dropdownMenu.length) {
            $dropdownButton.on('click', function(e) {
                e.preventDefault();
                $dropdownMenu.toggleClass('show');
            });
            $(document).on('click', function(e) {
                if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {
                    $dropdownMenu.removeClass('show');
                }
            });
        }

        // Normalize status
        let status = '{{ $status }}';
        status = status === 'post-op' ? 'postop' : status;

        let columns = [];
        const checkboxColumn = {
            data: null,
            orderable: false,
            className: 'select-checkbox',
            render: () => '<input type="checkbox" class="row-checkbox">'
        };

        const hmisVisitStatusColumn = {
            data: 'hmis_visit_status',
            orderable: false,
            render: (data) => {
                switch (data) {
                    case 'pending':
                        return `<span class="badge bg-secondary">Pending</span>`;
                    case 'honoured':
                        return `<span class="badge bg-success">Honoured</span>`;
                    case 'missed':
                        return `<span class="badge bg-danger">Missed</span>`;
                    case 'late':
                        return `<span class="badge bg-warning">Late</span>`;
                    case 'cancelled':
                        return `<span class="badge bg-dark">Cancelled</span>`;
                    default:
                        return `<span class="badge bg-info">${data}</span>`;
                }
            }
        }

        const tracingColumn = {
            data: null,
            orderable: false,
            className: 'tracing-class',
            render: (data, type, row) => {
                if (row.source_table === 'external_pending') {
                    return '';
                }
                return `
                    <a href="#" onclick="openTracingModal(${row.id}, event)">
                        <span class="fa-stack fa-sm" style="position: relative; width: 2em; height: 2em;">
                            <i class="fas fa-user fa-stack-1x" style="color:#4A90E2;"></i>
                            <i class="fas fa-route fa-stack-1x" style="color:#50E3C2; position: absolute; top: 0.6em; left: 1.1em; font-size: 0.7em;"></i>
                        </span>
                    </a>`;
            }
        };

        // Define columns based on status
        if (status === 'all') {
            columns = [
                checkboxColumn,
                tracingColumn,
                {
                    data: 'appointment_number',
                    defaultContent: '-'
                },
                {
                    data: 'full_name',
                    defaultContent: '-'
                },
                {
                    data: 'patient_number',
                    defaultContent: '-'
                },
                {
                    data: 'phone',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_date',
                    defaultContent: '-',
                    render: (data) => normalizeDate(data) || '-'
                },
                {
                    data: 'appointment_time',
                    defaultContent: '-',
                    render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'
                },
                {
                    data: 'doctor',
                    defaultContent: '-'
                },
                {
                    data: 'specialization',
                    defaultContent: '-'
                },
                {
                    data: 'hospital_branch',
                    defaultContent: '-'
                },
                {
                    data: 'booking_type',
                    defaultContent: '-'
                },
                {
                    data: 'tracing_status',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_status',
                    defaultContent: '-'
                },
                {
                    data: 'notes',
                    defaultContent: '-'
                },
                {
                    data: 'doctor_comments',
                    defaultContent: '-'
                },
                {
                    data: 'cancellation_reason',
                    defaultContent: '-'
                },
                {
                    data: null,
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <button type="button" class="btn btn-sm btn-primary view-appointment" 
                                    data-id="${row.id}" 
                                    data-source-table="${row.source_table}" 
                                    title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                        `;
                    }
                }
            ];
        } else if (status === 'external_pending') {
            columns = [{
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'appointment_number',
                    defaultContent: '-'
                },
                {
                    data: 'full_name',
                    defaultContent: '-'
                },
                {
                    data: 'patient_number',
                    defaultContent: '-'
                },
                {
                    data: 'phone',
                    defaultContent: '-'
                },
                {
                    data: 'email',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_date',
                    defaultContent: '-',
                    render: (data) => normalizeDate(data) || '-'
                },
                {
                    data: 'specialization',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_status',
                    defaultContent: '-'
                },
                {
                    data: null,
                    orderable: false,
                    render: (data, type, row) => {
                        const appointmentNumber = row.appointment_number.replace(/"/g, '"'); // Escape quotes
                        return `
                            <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#approveModal${appointmentNumber}" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm delete-appointment" 
                                    data-id="${row.id}" 
                                    data-source-table="${row.source_table}" 
                                    title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        `;
                    }
                }
            ];
        } else if (status === 'external_approved') {
            columns = [
                checkboxColumn,
                tracingColumn,
                {
                    data: 'full_name',
                    defaultContent: '-'
                },
                {
                    data: 'patient_number',
                    defaultContent: '-'
                },
                {
                    data: 'phone',
                    defaultContent: '-'
                },
                {
                    data: 'email',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_date',
                    defaultContent: '-',
                    render: (data) => normalizeDate(data) || '-'
                },
                {
                    data: 'appointment_time',
                    defaultContent: '-',
                    render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'
                },
                {
                    data: 'doctor',
                    defaultContent: '-'
                },
                {
                    data: 'specialization',
                    defaultContent: '-'
                },
                {
                    data: 'booking_type',
                    defaultContent: '-'
                },
                {
                    data: 'tracing_status',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_status',
                    defaultContent: '-'
                },
                {
                    data: null,
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <button type="button" class="btn btn-sm btn-primary view-appointment" 
                                    data-id="${row.id}" 
                                    data-source-table="${row.source_table}" 
                                    title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                        `;
                    }
                }
            ];
        } else if (status === 'cancelled') {
            columns = [{
                    data: 'appointment_number',
                    defaultContent: '-'
                },
                {
                    data: 'full_name',
                    defaultContent: '-'
                },
                {
                    data: 'patient_number',
                    defaultContent: '-'
                },
                {
                    data: 'phone',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_date',
                    defaultContent: '-',
                    render: (data) => normalizeDate(data) || '-'
                },
                {
                    data: 'appointment_time',
                    defaultContent: '-',
                    render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'
                },
                {
                    data: 'doctor',
                    defaultContent: '-'
                },
                {
                    data: 'specialization',
                    defaultContent: '-'
                },
                {
                    data: 'hospital_branch',
                    defaultContent: '-'
                },
                {
                    data: 'booking_type',
                    defaultContent: '-'
                },
                {
                    data: 'tracing_status',
                    defaultContent: '-'
                },
                {
                    data: null,
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <button type="button" class="btn btn-sm btn-primary view-appointment" 
                                    data-id="${row.id}" 
                                    data-source-table="${row.source_table}" 
                                    title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                        `;
                    }
                }
            ];
        } else if (status === 'rescheduled') {
            columns = [{
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'previous_number',
                    defaultContent: '-'
                },
                {
                    data: 'full_name',
                    defaultContent: '-'
                },
                {
                    data: 'previous_specialization',
                    defaultContent: '-'
                },
                {
                    data: 'previous_date',
                    defaultContent: '-',
                    render: (data) => normalizeDate(data) || '-'
                },
                {
                    data: 'previous_time',
                    defaultContent: '-',
                    render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'
                },
                {
                    data: 'from_to',
                    defaultContent: '-'
                },
                {
                    data: 'current_number',
                    defaultContent: '-'
                },
                {
                    data: 'current_specialization',
                    defaultContent: '-'
                },
                {
                    data: 'current_date',
                    defaultContent: '-',
                    render: (data) => normalizeDate(data) || '-'
                },
                {
                    data: 'current_time',
                    defaultContent: '-',
                    render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'
                },
                {
                    data: 'reason',
                    defaultContent: '-'
                },
            ];
        } else {
            columns = [
                checkboxColumn,
                tracingColumn,
                {
                    data: 'appointment_number',
                    defaultContent: '-'
                },

                {
                    data: 'full_name',
                    defaultContent: '-'
                },
                {
                    data: 'patient_number',
                    defaultContent: '-'
                },
                {
                    data: 'phone',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_date',
                    defaultContent: '-',
                    render: (data) => normalizeDate(data) || '-'
                },
                {
                    data: 'appointment_time',
                    defaultContent: '-',
                    render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '-'
                },
                {
                    data: 'doctor',
                    defaultContent: '-'
                },
                {
                    data: 'specialization',
                    defaultContent: '-'
                },
                {
                    data: 'booking_type',
                    defaultContent: '-'
                },
                {
                    data: 'tracing_status',
                    defaultContent: '-'
                },
                {
                    data: 'appointment_status',
                    defaultContent: '-'
                },
                hmisVisitStatusColumn,
                {
                    data: null,
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <button type="button" class="btn btn-sm btn-primary view-appointment" 
                                    data-id="${row.id}" 
                                    data-source-table="${row.source_table}" 
                                    title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                        `;
                    }
                }
            ];
        }

        function normalizeDate(dateStr) {
            if (!dateStr) return null;
            const date = moment(dateStr, [
                'YYYY-MM-DD',
                'DD/MM/YYYY',
                'DD-MM-YYYY',
                'YYYY-MM-DD HH:mm:ss',
                moment.ISO_8601
            ], true);
            return date.isValid() ? date.format('YYYY-MM-DD') : null;
        }

        // Initialize DataTable
        const table = $('.table').DataTable({
            ajax: {
                url: `/appointments/status-filter/${status}`,
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.branch = $('#branch').val();
                    d.ajax = 1;
                }
            },
            columns: columns,
            pageLength: parseInt($('#dataTable_length').val()) || 50,
            lengthMenu: [
                [50, 100, 200, -1],
                ["50", "100", "200", "All"]
            ],
            responsive: true,
            ordering: true,
            searching: true,
            order: [
                [status === 'external_pending' || status === 'all' ? 7 : 6, 'desc']
            ],
            language: {
                emptyTable: `No ${status} appointments found.`
            },
            dom: 'frtip', // Removed 'l' to hide default length dropdown
            createdRow: function(row, data, dataIndex) {
                if (data.tracing_status === 'contacted') {
                    $(row).addClass('tracing-contacted');
                } else if (data.tracing_status === 'not contacted') {
                    $(row).addClass('tracing-not-contacted');
                } else {
                    $(row).addClass('tracing-none');
                }
            },
            initComplete: function() {
                console.log('DataTable initialized with', this.api().rows().count(), 'rows');
                // Bind custom length dropdown change event
                $('#dataTable_length').on('change', function() {
                    const length = parseInt($(this).val());
                    table.page.len(length).draw();
                    console.log('Page length changed to:', length);
                });
            }
        });
        // Function to load appointment details via AJAX
        function loadAppointmentDetails(id, sourceTable) {
            $.ajax({
                url: `/booking/view/${id}/${sourceTable}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.appointment && response.status) {
                        const appointment = response.appointment;
                        const status = response.status;
                        const isReminderRoute = window.location.pathname.includes('reminders');

                        // Log specialization for debugging
                        console.log('Specialization from server:', appointment.specialization);

                        // Update modal title
                        $('#viewAppointmentModalLabel').text(`View Appointment: ${appointment.full_name || 'N/A'}`);

                        // Populate form fields
                        $('#modal_status').val(status);
                        $('#modal_source_table').val(appointment.source_table || sourceTable);
                        $('#modal_branch').val(appointment.hospital_branch || '');
                        $('#modal_appointment_id').val(id);
                        $('#modal_full_name').val(appointment.full_name || '');
                        $('#modal_patient_number').val(appointment.patient_number || '');
                        $('#modal_email').val(appointment.email || '');
                        $('#modal_phone').val(appointment.phone || '');
                        $('#modal_appointment_date').val(appointment.appointment_date ? moment(appointment.appointment_date).format('YYYY-MM-DD') : '');
                        $('#modal_appointment_time').val(appointment.appointment_time ? moment(appointment.appointment_time, 'HH:mm:ss').format('HH:mm') : '');

                        const specialization = String(appointment.specialization || '').trim();
                        const $specializationSelect = $('#modal_specialization');
                        $specializationSelect.prop('disabled', false); // Temporarily enable to set value

                        // Check if the specialization exists in the select options
                        if (specialization && !$specializationSelect.find(`option[value="${specialization}"]`).length) {
                            console.warn(`Specialization "${specialization}" not found in select options, adding dynamically`);
                            // Add the specialization as a new option
                            $specializationSelect.append(`<option value="${specialization}">${specialization}</option>`);
                        }
                        $specializationSelect.val(specialization);
                        $specializationSelect.prop('disabled', true); // Re-disable after setting
                        $('#modal_specialization_hidden').val(specialization);

                        $('#modal_doctor_name').val(appointment.doctor_name || appointment.doctor || '');

                        // Handle appointment_status
                        const validStatuses = ['pending', 'honoured', 'missed', 'late', 'cancelled'];
                        const appointmentStatus = validStatuses.includes(appointment.appointment_status) ? appointment.appointment_status : 'pending';
                        $('#modal_appointment_status').val(appointmentStatus);
                        console.log('Setting appointment_status to:', appointmentStatus);
                        $('#modal_visit_date').val(appointment.visit_date ? moment(appointment.visit_date).format('YYYY-MM-DD') : '');
                        // Handle booking_type
                        const $bookingTypeSelect = $('#modal_booking_type');
                        $bookingTypeSelect.val(appointment.booking_type || '');
                        // Set or remove required attribute based on status
                        if (['new', 'review', 'postop'].includes(status)) {
                            $bookingTypeSelect.prop('required', true);
                        } else {
                            $bookingTypeSelect.prop('required', false);
                        }
                        $('#modal_booking_id').val(appointment.booking_id || appointment.appointment_number || '');
                        $('#modal_patient_notified').val(appointment.patient_notified || '0');
                        $('#modal_status_field').val(appointment.status || 'pending');
                        $('#modal_notes').val(appointment.notes || '');
                        $('#modal_doctor_comments').val(appointment.doctor_comments || '');
                        $('#modal_cancellation_reason').val(appointment.cancellation_reason || '');

                        // Set form action
                        $('#update-form').attr('action', `/booking/update/${id}`);

                        // Show/hide conditional fields
                        $('#modal_booking_type_container').toggle(['new', 'review', 'postop'].includes(status));
                        $('#modal_booking_id_container').toggle(status === 'external_approved' && (appointment.booking_id || appointment.appointment_number));
                        $('#modal_patient_notified_container').toggle(status === 'external_approved' && 'patient_notified' in appointment);
                        $('#modal_status_field_container').toggle(status === 'external_pending');
                        $('#modal_notes_container').toggle(['external_pending', 'external_approved', 'cancelled'].includes(status));
                        $('#modal_doctor_comments_container').toggle('doctor_comments' in appointment);
                        $('#modal_cancellation_reason_container').toggle(status === 'cancelled' || appointment.appointment_status === 'cancelled');

                        // Dynamic footer buttons
                        let footerHtml = `
                    <button type="button" class="btn btn-sm" style="background-color: #6c757d;" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" form="update-form" class="btn btn-sm" style="background-color: #5bbbe1;"><i class="fas fa-save"></i> Update</button>
                `;

                        // Reapprove button for cancelled appointments
                        if ((status === 'all' ? appointment.source_table : status) === 'cancelled') {
                            footerHtml += `
                        <form action="/booking/reapprove/${id}/${status === 'all' ? appointment.source_table : status}" method="POST" style="display: inline-block;" class="appointment-form" onsubmit="return confirm('Are you sure you want to reapprove this appointment? It will be moved back to its original status.');">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="status" value="${status === 'all' ? appointment.source_table : status}">
                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-check"></i> Reapprove</button>
                        </form>
                    `;
                        }

                        // Clear button for reminders
                        if (isReminderRoute && status !== 'cancelled' && (status !== 'all' || appointment.source_table !== 'cancelled') && appointment.appointment_status !== 'cancelled') {
                            footerHtml += `
                        <form action="/booking/clear/${id}/${status === 'all' ? appointment.source_table : status}" method="POST" style="display: inline-block;" class="appointment-form" onsubmit="return confirm('Are you sure you want to clear this reminder? It will be removed from the reminder list.');">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="status" value="${status === 'all' ? appointment.source_table : status}">
                            <button type="submit" class="btn btn-sm" style="background-color: #f0ad4e;"><i class="fas fa-check"></i> Clear</button>
                        </form>
                    `;
                        }

                        // Delete button
                        footerHtml += `
                    <form action="/booking/${id}/delete" method="POST" style="display: inline-block;" class="appointment-form" onsubmit="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.');">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="status" value="${status === 'all' ? appointment.source_table : status}">
                        <button type="submit" class="btn btn-sm" style="background-color: #dc3545;"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                `;

                        // Cancel button
                        if (!isReminderRoute &&
                            status !== 'cancelled' &&
                            (status !== 'all' || appointment.source_table !== 'cancelled') &&
                            appointment.appointment_status !== 'cancelled' &&
                            appointment.appointment_status !== 'rescheduled') {
                            footerHtml += `
                        <button type="button" class="btn btn-sm cancel-appointment" style="background-color: #6c757d;" data-id="${id}" data-source-table="${status === 'all' ? appointment.source_table : status}" data-full-name="${appointment.full_name || 'N/A'}" data-patient-number="${appointment.patient_number || 'N/A'}"><i class="fas fa-ban"></i> Cancel</button>
                    `;
                        }

                        // Reschedule button
                        if (status !== 'cancelled' && (status !== 'all' || appointment.source_table !== 'cancelled') && appointment.appointment_status !== 'cancelled') {
                            footerHtml += `
                        <button type="button" class="btn btn-sm btn-info openRescheduleModal" style="background-color: #6c757d;" data-bs-toggle="modal" data-bs-target="#rescheduleAppointmentModal"
                            data-action="/booking/reschedule/${id}/${status === 'all' ? appointment.source_table : status}"
                            data-id="${id}"
                            data-full_name="${appointment.full_name || ''}"
                            data-patient_number="${appointment.patient_number || ''}"
                            data-email="${appointment.email || ''}"
                            data-phone="${appointment.phone || ''}"
                            data-appointment_date="${appointment.appointment_date ? moment(appointment.appointment_date).format('YYYY-MM-DD') : ''}"
                            data-appointment_time="${appointment.appointment_time ? moment(appointment.appointment_time, 'HH:mm:ss').format('HH:mm') : ''}"
                            data-specialization="${appointment.specialization || ''}"
                            data-doctor_name="${appointment.doctor_name || appointment.doctor || ''}"
                            data-booking_type="${appointment.booking_type || ''}">
                            <i class="fas fa-calendar-alt"></i> Reschedule
                        </button>
                    `;
                        }

                        $('#modal_footer').html(footerHtml);
                        $('#viewAppointmentModal').modal('show');
                    } else {
                        alert('Failed to load appointment details: Invalid response from server.');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading appointment:', xhr);
                    let errorMessage = 'Error loading appointment details: Unknown error';
                    if (xhr.status === 400) {
                        errorMessage = xhr.responseJSON?.error || 'Invalid status provided.';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Appointment not found.';
                    } else if (xhr.status === 419) {
                        errorMessage = 'Session expired. Please refresh the page.';
                    }
                    alert(errorMessage);
                }
            });
        }

        // Handle cancel button click
        $(document).on('click', '.cancel-appointment', function() {
            const id = $(this).data('id');
            let sourceTable = $(this).data('source-table');
            const fullName = $(this).data('full-name');
            const patientNumber = $(this).data('patient-number');

            // Normalize source_table to status
            const statusMap = {
                'new': 'new',
                'review': 'review',
                'post_op': 'postop',
                'external_pending_approvals': 'external_pending',
                'external_approved': 'external_approved',
                'cancelled': 'cancelled'
            };
            const status = statusMap[sourceTable] || sourceTable;

            $('#cancel-form').attr('action', `/booking/${id}/cancel`);
            $('#cancel_modal_appointment_id').val(id);
            $('#cancel_modal_status').val(status); // Use normalized status
            $('#cancel_modal_branch').val($('#modal_branch').val());
            $('#cancel_modal_full_name').val(fullName);
            $('#cancel_modal_patient_number').val(patientNumber);
            $('#cancel_modal_cancellation_reason').val('');
            $('#cancelAppointmentModal').modal('show');
        });

        // Handle delete button click in table (for external_pending)
        $('.table').on('click', '.delete-appointment', function() {
            const id = $(this).data('id');
            const sourceTable = $(this).data('source-table');
            if (confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
                $.ajax({
                    url: `/booking/${id}/delete`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        _method: 'DELETE',
                        status: sourceTable
                    },
                    success: function(response) {
                        alert(response.message || 'Appointment deleted successfully.');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to delete appointment.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        alert(errorMessage);
                    }
                });
            }
        });

        // View Appointment Button Click Handler
        $('.table').on('click', '.view-appointment', function() {
            const id = $(this).data('id');
            const sourceTable = $(this).data('source-table');
            loadAppointmentDetails(id, sourceTable);
        });

        // Form submission handler for update
        // Form submission handler for update
        $('#update-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const loaderOverlay = document.getElementById('loaderOverlay');
            const tableResponsive = document.querySelector('.table-responsive');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#viewAppointmentModal').modal('hide');
                    alert('Appointment updated successfully.');
                    table.ajax.reload();
                    // Hide loader and reset session storage
                    console.log('Loader: Hiding after update success');
                    loaderOverlay.classList.remove('active');
                    sessionStorage.removeItem('loaderActive');
                    if (tableResponsive) {
                        tableResponsive.classList.add('loaded');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to update appointment.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $('#modal_errors').html(errorMessage).removeClass('d-none');
                    // Hide loader and reset session storage
                    console.log('Loader: Hiding after update error');
                    loaderOverlay.classList.remove('active');
                    sessionStorage.removeItem('loaderActive');
                    if (tableResponsive) {
                        tableResponsive.classList.add('loaded');
                    }
                }
            });
        });

        // Form submission handler for cancel
        $('#cancel-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#cancelAppointmentModal').modal('hide');
                    $('#viewAppointmentModal').modal('hide');
                    alert(response.message || 'Appointment cancelled successfully.');
                    table.ajax.reload();
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to cancel appointment.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    alert(errorMessage);
                }
            });
        });

        // Reschedule modal handler
        $(document).on('click', '.openRescheduleModal', function() {
            const data = $(this).data();
            console.log('Reschedule modal data:', data);

            $('#reschedule-form').attr('action', `/booking/reschedule/${data.id}`);
            $('#reschedule_appointment_id').val(data.id);
            $('#reschedule_full_name').val(data.full_name || '');
            $('#reschedule_patient_number').val(data.patient_number || '');
            $('#reschedule_email').val(data.email || '');
            $('#reschedule_phone').val(data.phone || '');
            $('#reschedule_appointment_date').val(data.appointment_date || '');
            $('#reschedule_appointment_time').val(data.appointment_time || '');
            $('#reschedule_hospital_branch').val(data.hospital_branch || '');

            // Handle specialization
            const specialization = String(data.specialization || '').trim();
            const $specializationSelect = $('#reschedule_specialization');
            if (specialization && !$specializationSelect.find(`option[value="${specialization}"]`).length) {
                console.warn(`Specialization "${specialization}" not found in reschedule select options, adding dynamically`);
                $specializationSelect.append(`<option value="${specialization}">${specialization}</option>`);
            }
            $specializationSelect.val(specialization);

            $('#reschedule_doctor_name').val(data.doctor_name || '');
            $('#reschedule_booking_type').val(data.booking_type || '');
            $('#reschedule_reason').val('');

            $('#viewAppointmentModal').modal('hide');
            $('#rescheduleAppointmentModal').modal('show');
        });

        // Existing filter, export, and print logic
        const debouncedApplyDateFilters = _.debounce(applyDateFilters, 300);
        const debouncedApplySearchFilter = _.debounce(applySearchFilter, 300);

        function applyDateFilters() {
            const startDateInput = $('#start_date').val();
            const endDateInput = $('#end_date').val();
            const startDate = startDateInput ? normalizeDate(startDateInput) : null;
            const endDate = endDateInput ? normalizeDate(endDateInput) : null;

            if (startDateInput && !startDate) {
                alert('Invalid start date format.');
                return;
            }
            if (endDateInput && !endDate) {
                alert('Invalid end date format.');
                return;
            }

            table.ajax.reload(function() {
                console.log('Server-side date filters applied:', {
                    startDate,
                    endDate
                });
                applySearchFilter();
            });
        }

        function applySearchFilter() {
            const searchTerm = $('#search').val().toLowerCase();
            $.fn.dataTable.ext.search.pop();
            $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
                const row = table.row(dataIndex).data();
                if (!row) return false;

                const searchableFields = [
                    row.appointment_number,
                    row.full_name,
                    row.patient_number,
                    row.email,
                    row.phone,
                    row.appointment_date,
                    row.appointment_time,
                    row.specialization,
                    row.doctor,
                    row.hospital_branch || '',
                    row.booking_type,
                    row.appointment_status,
                    row.notes,
                    row.doctor_comments,
                    row.cancellation_reason
                ].map(val => (val || '').toString().toLowerCase());

                return !searchTerm || searchableFields.some(field => field.includes(searchTerm));
            });
            table.draw();
            console.log('Client-side search filter applied:', {
                searchTerm
            });
        }

        $('#apply-filters').on('click', () => {
            $('#apply-filters').html('<i class="fas fa-spinner fa-spin me-1"></i>Applying...');
            debouncedApplyDateFilters();
            setTimeout(() => {
                $('#apply-filters').html('<i class="fas fa-filter me-1"></i>Apply Filter');
            }, 500);
        });

        $('#start_date, #end_date').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#apply-filters').html('<i class="fas fa-spinner fa-spin me-1"></i>Applying...');
                debouncedApplyDateFilters();
                setTimeout(() => {
                    $('#apply-filters').html('<i class="fas fa-filter me-1"></i>Apply Filter');
                }, 500);
            }
        });

        $('#search').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                debouncedApplySearchFilter();
            }
        });

        $('#reset-filters').on('click', () => {
            $('#filter-form')[0].reset();
            $.fn.dataTable.ext.search.pop();
            table.search('').draw();
            table.ajax.reload();
            console.log('Filters reset');
        });

        $('#search').on('keyup', debouncedApplySearchFilter);

        $('.table').on('click', '.row-checkbox', function() {
            $(this).closest('tr').toggleClass('selected');
        });

        $('.table').on('click', 'thead .select-checkbox input', function() {
            const isChecked = $(this).prop('checked');
            $('.table tbody .row-checkbox').prop('checked', isChecked).closest('tr').toggleClass('selected', isChecked);
        });

        function updateAppointments(action, ids, status) {
            $.ajax({
                url: action === 'came' ? '{{ route("booking.mark-came") }}' : '{{ route("booking.mark-missed") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    ids: ids,
                    status: status,
                    _token: csrfToken
                },
                beforeSend: function() {
                    $('#actionDropdown').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
                },
                success: function(response) {
                    alert(response.message || `Appointments marked as ${action} successfully.`);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    let errorMessage = `Failed to mark appointments as ${action}.`;
                    if (xhr.status === 419) {
                        errorMessage = 'Session expired or CSRF token mismatch. Please refresh the page and try again.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'You do not have permission to perform this action.';
                    } else if (xhr.status === 404) {
                        errorMessage = 'The requested endpoint was not found.';
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    alert(`Error: ${errorMessage}`);
                },
                complete: function() {
                    $('#actionDropdown').prop('disabled', false).html('<i class="fas fa-cog me-1"></i> Actions');
                }
            });
        }

        $(document).on('click', '#mark-came', function(e) {
            e.preventDefault();
            const selectedRows = table.rows('.selected').data().toArray();
            if (!selectedRows.length) {
                alert('No rows selected.');
                return;
            }
            const appointmentIds = selectedRows.map(row => row.id);
            updateAppointments('came', appointmentIds, status);
            $('#actionDropdownMenu').removeClass('show');
        });

        $(document).on('click', '#mark-missed', function(e) {
            e.preventDefault();
            const selectedRows = table.rows('.selected').data().toArray();
            if (!selectedRows.length) {
                alert('No rows selected.');
                return;
            }
            const appointmentIds = selectedRows.map(row => row.id);
            updateAppointments('missed', appointmentIds, status);
            $('#actionDropdownMenu').removeClass('show');
        });

        $('#export-csv-btn').on('click', () => {
            const visibleData = table.rows({
                search: 'applied'
            }).data().toArray();
            if (!visibleData.length) {
                alert('No data to export.');
                return;
            }

            let headers = [];
            if (status === 'all') {
                headers = ['Appointment No.', 'Pt Name', 'Pt No.', 'Phone', 'Email', 'Date', 'Time', 'Doctor', 'Specialization', 'Branch', 'Booking Type', 'Appointment Status', 'Notes', 'Doctor Comments', 'Cancellation Reason'];
            } else if (status === 'external_pending') {
                headers = ['Appointment No.', 'Pt Name', 'Pt No.', 'Phone', 'Email', 'Date', 'Specialization', 'Appointment Status'];
            } else if (status === 'external_approved') {
                headers = ['Pt Name', 'Pt No.', 'Phone', 'Email', 'Date', 'Time', 'Doctor', 'Specialization', 'Booking Type', 'Appointment Status'];
            } else if (status === 'cancelled') {
                headers = ['Appointment No.', 'Pt Name', 'Pt No.', 'Phone', 'Date', 'Time', 'Doctor', 'Specialization', 'Branch', 'Booking Type', 'Appointment Status'];
            } else if (status === 'rescheduled') {
                headers = ['Prev App No.', 'Pt Name.', 'Prev Spec.', 'Prev Appt Date.', 'Prev Appt Time.', 'To.', 'Cur App No.', 'Cur Spec.', 'Cur Appt Date.', 'Cur Appt Time.', 'Reason.'];
            } else {
                headers = ['Appointment No.', 'Pt Name', 'Pt No.', 'Phone', 'Email', 'Date', 'Time', 'Doctor', 'Specialization', 'Booking Type', 'Appointment Status'];
            }

            const csvRows = [headers];
            visibleData.forEach((row, index) => {
                let rowData = [];
                if (status === 'all') {
                    rowData = [
                        row.appointment_number || '-',
                        row.full_name || '-',
                        row.patient_number || '-',
                        row.phone || '-',
                        row.email || '-',
                        normalizeDate(row.appointment_date) || '-',
                        row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : '-',
                        row.doctor || '-',
                        row.specialization || '-',
                        row.hospital_branch || '-',
                        row.booking_type || '-',
                        row.appointment_status || '-',
                        row.notes || '-',
                        row.doctor_comments || '-',
                        row.cancellation_reason || '-'
                    ];
                } else if (status === 'external_pending') {
                    rowData = [
                        row.appointment_number || '-',
                        row.full_name || '-',
                        row.patient_number || '-',
                        row.phone || '-',
                        row.email || '-',
                        normalizeDate(row.appointment_date) || '-',
                        row.specialization || '-',
                        row.appointment_status || '-'
                    ];
                } else if (status === 'external_approved') {
                    rowData = [
                        row.full_name || '-',
                        row.patient_number || '-',
                        row.phone || '-',
                        row.email || '-',
                        normalizeDate(row.appointment_date) || '-',
                        row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : '-',
                        row.doctor || '-',
                        row.specialization || '-',
                        row.booking_type || '-',
                        row.appointment_status || '-'
                    ];
                } else if (status === 'cancelled') {
                    rowData = [
                        row.appointment_number || '-',
                        row.full_name || '-',
                        row.patient_number || '-',
                        row.phone || '-',
                        normalizeDate(row.appointment_date) || '-',
                        row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : '-',
                        row.doctor || '-',
                        row.specialization || '-',
                        row.hospital_branch || '-',
                        row.booking_type || '-',
                    ];
                } else if (status === 'rescheduled') {
                    rowData = [
                        row.previous_number || '-',
                        row.full_name || '-',
                        row.previous_specialization || '-',
                        normalizeDate(row.previous_date) || '-',
                        row.previous_time ? new Date('1970-01-01T' + row.previous_time).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : '-',
                        row.from_to || '-',
                        row.current_number || '-',
                        row.current_specialization || '-',
                        normalizeDate(row.current_date) || '-',
                        row.current_time ? new Date('1970-01-01T' + row.current_time).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : '-',
                        row.reason || '-'
                    ];
                } else {
                    rowData = [
                        row.appointment_number || '-',
                        row.full_name || '-',
                        row.patient_number || '-',
                        row.phone || '-',
                        row.email || '-',
                        normalizeDate(row.appointment_date) || '-',
                        row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : '-',
                        row.doctor || '-',
                        row.specialization || '-',
                        row.booking_type || '-',
                        row.appointment_status || '-'
                    ];
                }
                csvRows.push(rowData);
            });

            const csvContent = csvRows.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${status}_appointments_${new Date().toISOString().replace(/[-:T]/g, '').split('.')[0]}.csv`;
            link.click();
        });

        window.printTable = function() {
            const visibleData = table.rows({
                search: 'applied'
            }).data().toArray();
            let columnCount = status === 'all' ? 16 :
                status === 'external_pending' ? 9 :
                status === 'cancelled' ? 12 :
                status === 'external_approved' ? 12 :
                status === 'rescheduled' ? 12 : 11;

            const baseWidth = Math.min(100 / columnCount, 8);
            let tableHtml = `
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            ${status === 'all' || status === 'cancelled' || status === 'external_pending' ? '<th>Appointment No.</th>' : ''}
                            <th>Pt Name</th>
                            <th>Pt No.</th>
                            <th>Phone</th>
                            ${status === 'external_pending' || status !== 'all' && status !== 'cancelled' && status !== 'rescheduled' ? '<th>Email</th>' : ''}
                            <th>Date</th>
                            ${status !== 'external_pending' ? `
                                <th>Time</th>
                                <th>Doctor</th>
                            ` : ''}
                            <th>Specialization</th>
                            ${status === 'all' || status === 'cancelled' ? '<th>Branch</th>' : ''}
                            ${status !== 'external_pending' && status !== 'rescheduled' ? '<th>Booking Type</th>' : ''}
                            ${status !== 'rescheduled' ? '<th>Appointment Status</th>' : ''}
                            ${status === 'all' ? `
                                <th>Notes</th>
                                <th>Doctor Comments</th>
                                <th>Cancellation Reason</th>
                            ` : ''}
                            ${status === 'rescheduled' ? `
                                <th>Prev Spec.</th>
                                <th>Prev Date</th>
                                <th>Prev Time</th>
                                <th>To</th>
                                <th>Cur App No.</th>
                                <th>Cur Spec.</th>
                                <th>Cur Date</th>
                                <th>Cur Time</th>
                                <th>Reason</th>
                            ` : ''}
                        </tr>
                    </thead>
                    <tbody>
            `;

            visibleData.forEach((row, index) => {
                tableHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        ${status === 'all' || status === 'cancelled' || status === 'external_pending' ? `<td>${row.appointment_number || '-'}</td>` : ''}
                        <td>${row.full_name || '-'}</td>
                        <td>${row.patient_number || '-'}</td>
                        <td>${row.phone || '-'}</td>
                        ${status === 'external_pending' || status !== 'all' && status !== 'cancelled' && status !== 'rescheduled' ? `<td>${row.email || '-'}</td>` : ''}
                        <td>${normalizeDate(status === 'rescheduled' ? row.previous_date : row.appointment_date) || '-'}</td>
                        ${status !== 'external_pending' ? `
                            <td>${(status === 'rescheduled' ? row.previous_time : row.appointment_time) ? new Date('1970-01-01T' + (status === 'rescheduled' ? row.previous_time : row.appointment_time)).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                            <td>${row.doctor || '-'}</td>
                        ` : ''}
                        <td>${(status === 'rescheduled' ? row.previous_specialization : row.specialization) || '-'}</td>
                        ${status === 'all' || status === 'cancelled' ? `<td>${row.hospital_branch || '-'}</td>` : ''}
                        ${status !== 'external_pending' && status !== 'rescheduled' ? `<td>${row.booking_type || '-'}</td>` : ''}
                        ${status !== 'rescheduled' ? `<td>${row.appointment_status || '-'}</td>` : ''}
                        ${status === 'all' ? `
                            <td>${row.notes || '-'}</td>
                            <td>${row.doctor_comments || '-'}</td>
                            <td>${row.cancellation_reason || '-'}</td>
                        ` : ''}
                        ${status === 'rescheduled' ? `
                            <td>${row.previous_specialization || '-'}</td>
                            <td>${normalizeDate(row.previous_date) || '-'}</td>
                            <td>${row.previous_time ? new Date('1970-01-01T' + row.previous_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                            <td>${row.from_to || '-'}</td>
                            <td>${row.current_number || '-'}</td>
                            <td>${row.current_specialization || '-'}</td>
                            <td>${normalizeDate(row.current_date) || '-'}</td>
                            <td>${row.current_time ? new Date('1970-01-01T' + row.current_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                            <td>${row.reason || '-'}</td>
                        ` : ''}
                    </tr>
                `;
            });

            tableHtml += '</tbody></table>';

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print ${status} Appointments</title>
                    <style>
                        body { font-family: 'Arial', sans-serif; padding: 10mm; margin: 0; }
                        .table { width: 100%; max-width: 100%; border-collapse: collapse; font-size: 0.65rem; line-height: 1.1; }
                        .table th, .table td { padding: 3px 5px; border: 1px solid #dee2e6; vertical-align: top; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: ${baseWidth}%; }
                        .table td { max-width: 0; overflow: hidden; text-overflow: ellipsis; }
                        @media print {
                            body { width: 210mm; height: auto; margin: 8mm; }
                            .table { font-size: 0.6rem; line-height: 1.0; }
                            .table th, .table td { padding: 2px 4px; }
                        }
                        @page { size: A4; margin: 8mm; }
                    </style>
                </head>
                <body>
                    <h4>${status} Appointments</h4>
                    ${tableHtml}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        };
    });
</script>
@endsection