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
                                <i class="fas fa-calendar-   alt me-1"></i>Start Date
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
                        <div class="col-md-3">
                            <label for="search" class="form-label small fw-semibold text-muted">
                                <i class="fas fa-search me-1"></i>Search
                            </label>
                            <input type="text" name="search" id="search" class="form-control form-control-sm shadow-sm"
                                value="{{ request('search') }}" placeholder="Search by any detail">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="button" class="btn btn-primary btn-sm flex-grow-1 shadow-sm" id="apply-filters">
                                <i class="fas fa-filter me-1"></i>Apply Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm" id="reset-filters">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                            <button type="button" class="btn btn-info btn-sm shadow-sm" onclick="printTable()">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                            <button type="button" id="export-csv-btn" class="btn btn-secondary btn-sm shadow-sm">
                                <i class="fas fa-file-csv me-1"></i>CSV
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

            @if (in_array($status, ['new', 'review', 'postop', 'external_approved']))
                <div class="mt-2 ms-3 pb-2 action-dropdown">
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

            @include("booking.tables.{$status}", ['appointments' => $appointments, 'specializations' => $specializations])

            @if($status !== 'rescheduled')
                @foreach ($appointments as $appointment)
                    @include('booking.view', [
                        'appointment' => $appointment,
                        'specializations' => $specializations,
                        'status' => $status,
                    ])
                @endforeach
            @endif
        </div>
    </div>

    <style>
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
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    @include('booking.reschedule')

    <script>
        $(document).ready(function () {
            console.log('Document ready, initializing scripts for status: {{ $status }}');

            // Reschedule Appointment Modal JavaScript
            const modal = document.getElementById('rescheduleAppointmentModal');
            const form = document.getElementById('rescheduleForm');

            document.querySelectorAll('.openRescheduleModal').forEach(button => {
                button.addEventListener('click', () => {
                    form.action = button.dataset.action;
                    document.getElementById('modal_appointment_id').value = button.dataset.id;
                    document.getElementById('modal_full_name').value = button.dataset.full_name;
                    document.getElementById('modal_patient_number').value = button.dataset.patient_number;
                    document.getElementById('modal_email').value = button.dataset.email;
                    document.getElementById('modal_phone').value = button.dataset.phone;
                    document.getElementById('modal_appointment_date').value = button.dataset.appointment_date;
                    document.getElementById('modal_appointment_time').value = button.dataset.appointment_time;
                    document.getElementById('modal_specialization').value = button.dataset.specialization;
                    document.getElementById('modal_doctor_name').value = button.dataset.doctor_name;
                    document.getElementById('modal_booking_type').value = button.dataset.booking_type;
                });
            });

            // Verify CSRF token
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!csrfToken) {
                console.error('CSRF token not found. Ensure <meta name="csrf-token"> is included in the layout.');
                alert('CSRF token missing. Please contact the administrator.');
            } else {
                console.log('CSRF token found:', csrfToken);
            }

            // Custom dropdown toggle
            try {
                const $dropdownButton = $('#actionDropdown');
                const $dropdownMenu = $('#actionDropdownMenu');

                if ($dropdownButton.length && $dropdownMenu.length) {
                    console.log('Action dropdown elements found.');
                    $dropdownButton.on('click', function (e) {
                        e.preventDefault();
                        console.log('Action dropdown button clicked.');
                        $dropdownMenu.toggleClass('show');
                    });

                    $(document).on('click', function (e) {
                        if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {
                            $dropdownMenu.removeClass('show');
                            console.log('Dropdown closed due to outside click.');
                        }
                    });
                } else {
                    console.warn('Action dropdown elements not found: button=', $dropdownButton.length, 'menu=', $dropdownMenu.length);
                }
            } catch (error) {
                console.error('Error setting up custom dropdown:', error);
            }

            // Normalize status
            let status = '{{ $status }}';
            status = status === 'post-op' ? 'postop' : status;
            console.log('Normalized status:', status);

            let columns = [];
            const checkboxColumn = {
                data: null,
                orderable: false,
                className: 'select-checkbox',
                render: () => '<input type="checkbox" class="row-checkbox">'
            };

            if (status === 'all') {
                columns = [
                    checkboxColumn,
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'appointment_number', defaultContent: '-' },
                    { data: 'full_name', defaultContent: '-' },
                    { data: 'patient_number', defaultContent: '-' },
                    { data: 'phone', defaultContent: '-' },
                    { data: 'email', defaultContent: '-' },
                    {
                        data: 'appointment_date',
                        defaultContent: '-',
                        render: (data) => normalizeDate(data) || '-'
                    },
                    {
                        data: 'appointment_time',
                        defaultContent: '-',
                        render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                    },
                    { data: 'doctor', defaultContent: '-' },
                    { data: 'specialization', defaultContent: '-' },
                    { data: 'hospital_branch', defaultContent: '-' },
                    { data: 'booking_type', defaultContent: '-' },
                    { data: 'appointment_status', defaultContent: '-' },
                    { data: 'notes', defaultContent: '-' },
                    { data: 'doctor_comments', defaultContent: '-' },
                    { data: 'cancellation_reason', defaultContent: '-' },
                    {
                        data: null,
                        orderable: false,
                        render: (data, type, row) => {
                            return `
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal${row.id}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                            `;
                        }
                    }
                ];
            } else if (status === 'external_pending') {
                columns = [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'appointment_number', defaultContent: '-' },
                    { data: 'full_name', defaultContent: '-' },
                    { data: 'patient_number', defaultContent: '-' },
                    { data: 'phone', defaultContent: '-' },
                    { data: 'email', defaultContent: '-' },
                    {
                        data: 'appointment_date',
                        defaultContent: '-',
                        render: (data) => normalizeDate(data) || '-'
                    },
                    { data: 'specialization', defaultContent: '-' },
                    { data: 'appointment_status', defaultContent: '-' },
                    {
                        data: null,
                        orderable: false,
                        render: (data, type, row) => {
                            return `
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#approveModal${row.appointment_number}" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>

                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal${row.id}" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            `;
                        }
                    }
                ];
            } else if (status === 'external_approved') {
                columns = [
                    checkboxColumn,
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'full_name', defaultContent: '-' },
                    { data: 'patient_number', defaultContent: '-' },
                    { data: 'phone', defaultContent: '-' },
                    { data: 'email', defaultContent: '-' },
                    {
                        data: 'appointment_date',
                        defaultContent: '-',
                        render: (data) => normalizeDate(data) || '-'
                    },
                    {
                        data: 'appointment_time',
                        defaultContent: '-',
                        render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                    },
                    { data: 'doctor', defaultContent: '-' },
                    { data: 'specialization', defaultContent: '-' },
                    { data: 'booking_type', defaultContent: '-' },
                    { data: 'appointment_status', defaultContent: '-' },
                    {
                        data: null,
                        orderable: false,
                        render: (data, type, row) => {
                            return `
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal${row.id}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                            `;
                        }
                    }
                ];
            } else if (status === 'cancelled') {
                columns = [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'appointment_number', defaultContent: '-' },
                    { data: 'full_name', defaultContent: '-' },
                    { data: 'patient_number', defaultContent: '-' },
                    { data: 'phone', defaultContent: '-' },
                    {
                        data: 'appointment_date',
                        defaultContent: '-',
                        render: (data) => normalizeDate(data) || '-'
                    },
                    {
                        data: 'appointment_time',
                        defaultContent: '-',
                        render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                    },
                    { data: 'doctor', defaultContent: '-' },
                    { data: 'specialization', defaultContent: '-' },
                    { data: 'hospital_branch', defaultContent: '-' },
                    { data: 'booking_type', defaultContent: '-' },
                    { data: 'appointment_status', defaultContent: '-' },
                    {
                        data: null,
                        orderable: false,
                        render: (data, type, row) => {
                            return `
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal${row.id}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                            `;
                        }
                    }
                ];
            } else if (status === 'rescheduled') {
                columns = [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'previous_number', defaultContent: '-' },
                    { data: 'full_name', defaultContent: '-' },
                    { data: 'previous_specialization', defaultContent: '-' },
                    {
                        data: 'previous_date',
                        defaultContent: '-',
                        render: (data) => normalizeDate(data) || '-'
                    },
                    {
                        data: 'previous_time',
                        defaultContent: '-',
                        render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                    },
                    { data: 'from_to', defaultContent: '-' },
                    { data: 'current_number', defaultContent: '-' },
                    { data: 'current_specialization', defaultContent: '-' },
                    {
                        data: 'current_date',
                        defaultContent: '-',
                        render: (data) => normalizeDate(data) || '-'
                    },
                    {
                        data: 'current_time',
                        defaultContent: '-',
                        render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                    },
                    { data: 'reason', defaultContent: '-' },
                ];
            } else {
                columns = [
                    checkboxColumn,
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'appointment_number', defaultContent: '-' },
                    { data: 'full_name', defaultContent: '-' },
                    { data: 'patient_number', defaultContent: '-' },
                    { data: 'phone', defaultContent: '-' },
                    { data: 'email', defaultContent: '-' },
                    {
                        data: 'appointment_date',
                        defaultContent: '-',
                        render: (data) => normalizeDate(data) || '-'
                    },
                    {
                        data: 'appointment_time',
                        defaultContent: '-',
                        render: (data) => data ? new Date('1970-01-01T' + data).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                    },
                    { data: 'doctor', defaultContent: '-' },
                    { data: 'specialization', defaultContent: '-' },
                    { data: 'booking_type', defaultContent: '-' },
                    { data: 'appointment_status', defaultContent: '-' },
                    {
                        data: null,
                        orderable: false,
                        render: (data, type, row) => {
                            return `
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal${row.id}" title="View">
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

    // Initialize DataTable with AJAX for server-side date filtering
    const table = $('.table').DataTable({
        ajax: {
            url: `/appointments/status-filter/${status}`,
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.branch = $('#branch').val(); // Only if user is superadmin
                d.ajax = 1; // Helps the Laravel controller know it's an AJAX request
            }
        },
        columns: columns,
        pageLength: 50,
        lengthMenu: [[50, 100, 200, -1], ["50", "100", "200", "All"]],
        responsive: true,
        ordering: true,
        searching: true,
        order: [[status === 'external_pending' || status === 'all' ? 7 : 6, 'desc']],
        language: {
            emptyTable: `No ${status} appointments found.`
        },
        dom: 'lfrtip',
        initComplete: function () {
            console.log('DataTable initialized with', this.api().rows().count(), 'rows');
        }
    });

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

        // Reload table data via AJAX with date filters
        table.ajax.reload(function () {
            console.log('Server-side date filters applied:', { startDate, endDate });
            // Re-apply client-side search filter after reloading
            applySearchFilter();
        });
    }

    function applySearchFilter() {
        const searchTerm = $('#search').val().toLowerCase();

        // Remove previous custom search
        $.fn.dataTable.ext.search.pop();

        // Add new custom search for client-side filtering
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

            const searchMatch = !searchTerm || searchableFields.some(field => field.includes(searchTerm));

            return searchMatch;
        });

        table.draw();
        console.log('Client-side search filter applied:', { searchTerm });
    }

    $('#apply-filters').on('click', () => {
        $('#apply-filters').html('<i class="fas fa-spinner fa-spin me-1"></i>Applying...');
        debouncedApplyDateFilters();
        setTimeout(() => {
            $('#apply-filters').html('<i class="fas fa-filter me-1"></i>Apply Filter');
        }, 500);
    });

    $('#start_date, #end_date').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#apply-filters').html('<i class="fas fa-spinner fa-spin me-1"></i>Applying...');
            debouncedApplyDateFilters();
            setTimeout(() => {
                $('#apply-filters').html('<i class="fas fa-filter me-1"></i>Apply Filter');
            }, 500);
        }
    });

    $('#search').on('keypress', function (e) {
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

    $('.table').on('click', '.row-checkbox', function () {
        $(this).closest('tr').toggleClass('selected');
    });

    $('.table').on('click', 'thead .select-checkbox input', function () {
        const isChecked = $(this).prop('checked');
        $('.table tbody .row-checkbox').prop('checked', isChecked).closest('tr').toggleClass('selected', isChecked);
    });


            function updateAppointments(action, ids, status) {
                console.log(`Preparing AJAX request to mark as ${action}:`, { ids, status });
                console.log('CSRF token:', csrfToken);
                console.log('Request URL:', action === 'came' ? '{{ route("booking.mark-came") }}' : '{{ route("booking.mark-missed") }}');
                console.log('Request data:', { ids, status, _token: csrfToken });

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
                    beforeSend: function () {
                        $('#actionDropdown').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
                        console.log(`Sending AJAX request to mark as ${action}`);
                    },
                    success: function (response) {
                        console.log(`Mark as ${action} success:`, response);
                        alert(response.message || `Appointments marked as ${action} successfully.`);
                        window.location.reload();
                    },
                    error: function (xhr) {
                        console.error(`Mark as ${action} failed:`, {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            response: xhr.responseJSON,
                            responseText: xhr.responseText
                        });
                        let errorMessage = `Failed to mark appointments as ${action}.`;
                        if (xhr.status === 419) {
                            errorMessage = 'Session expired or CSRF token mismatch. Please refresh the page and try again.';
                        } else if (xhr.status === 403) {
                            errorMessage = 'You do not have permission to perform this action.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'The requested endpoint was not found. Please check the server configuration.';
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        alert(`Error: ${errorMessage}`);
                    },
                    complete: function () {
                        $('#actionDropdown').prop('disabled', false).html('<i class="fas fa-cog me-1"></i> Actions');
                        console.log(`AJAX request to mark as ${action} completed`);
                    }
                });
            }

            $(document).on('click', '#mark-came', function (e) {
                e.preventDefault();
                console.log('Mark as came clicked.');
                const selectedRows = table.rows('.selected').data().toArray();
                if (!selectedRows.length) {
                    alert('No rows selected.');
                    return;
                }
                const appointmentIds = selectedRows.map(row => row.id);
                console.log('Selected appointment IDs:', appointmentIds);
                updateAppointments('came', appointmentIds, status);
                $('#actionDropdownMenu').removeClass('show');
            });

            $(document).on('click', '#mark-missed', function (e) {
                e.preventDefault();
                console.log('Mark as missed clicked.');
                const selectedRows = table.rows('.selected').data().toArray();
                if (!selectedRows.length) {
                    alert('No rows selected.');
                    return;
                }
                const appointmentIds = selectedRows.map(row => row.id);
                console.log('Selected appointment IDs:', appointmentIds);
                updateAppointments('missed', appointmentIds, status);
                $('#actionDropdownMenu').removeClass('show');
            });

            $('#export-csv-btn').on('click', () => {
                const visibleData = table.rows({ search: 'applied' }).data().toArray();
                if (!visibleData.length) {
                    alert('No data to export.');
                    return;
                }

                let headers = [];
                if (status === 'all') {
                    headers = [
                        'S.No',
                        'Appointment No.',
                        'Pt Name',
                        'Pt No.',
                        'Phone',
                        'Email',
                        'Date',
                        'Time',
                        'Doctor',
                        'Specialization',
                        'Branch',
                        'Booking Type',
                        'Appointment Status',
                        'Notes',
                        'Doctor Comments',
                        'Cancellation Reason'
                    ];
                } else if (status === 'external_pending') {
                    headers = [
                        'S.No',
                        'Appointment No.',
                        'Pt Name',
                        'Pt No.',
                        'Phone',
                        'Email',
                        'Date',
                        'Specialization',
                        'Appointment Status'
                    ];
                } else if (status === 'external_approved') {
                    headers = [
                        'S.No',
                        'Pt Name',
                        'Pt No.',
                        'Phone',
                        'Email',
                        'Date',
                        'Time',
                        'Doctor',
                        'Specialization',
                        'Booking Type',
                        'Appointment Status'
                    ];
                } else if (status === 'cancelled') {
                    headers = [
                        'S.No',
                        'Appointment No.',
                        'Pt Name',
                        'Pt No.',
                        'Phone',
                        'Date',
                        'Time',
                        'Doctor',
                        'Specialization',
                        'Branch',
                        'Booking Type',
                        'Appointment Status'
                    ];
                } else if (status === 'rescheduledocios') {
                    headers = [
                        'S.No',
                        'Prev App No.',
                        'Pt Name.',
                        'Prev Spec.',
                        'Prev Appt Date.',
                        'Prev Appt Time.',
                        'To.',
                        'Cur App No.',
                        'Cur Spec.',
                        'Cur Appt Date.',
                        'Cur Appt Time.',
                        'Reason.'
                    ];
                } else {
                    headers = [
                        'S.No',
                        'Appointment No.',
                        'Pt Name',
                        'Pt No.',
                        'Phone',
                        'Email',
                        'Date',
                        'Time',
                        'Doctor',
                        'Specialization',
                        'Booking Type',
                        'Appointment Status'
                    ];
                }

                const csvRows = [headers];

                visibleData.forEach((row, index) => {
                    let rowData = [];
                    if (status === 'all') {
                        rowData = [
                            index + 1,
                            row.appointment_number || '-',
                            row.full_name || '-',
                            row.patient_number || '-',
                            row.phone || '-',
                            row.email || '-',
                            normalizeDate(row.appointment_date) || '-',
                            row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-',
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
                            index + 1,
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
                            index + 1,
                            row.full_name || '-',
                            row.patient_number || '-',
                            row.phone || '-',
                            row.email || '-',
                            normalizeDate(row.appointment_date) || '-',
                            row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-',
                            row.doctor || '-',
                            row.specialization || '-',
                            row.booking_type || '-',
                            row.appointment_status || '-'
                        ];
                    } else if (status === 'cancelled') {
                        rowData = [
                            index + 1,
                            row.appointment_number || '-',
                            row.full_name || '-',
                            row.patient_number || '-',
                            row.phone || '-',
                            normalizeDate(row.appointment_date) || '-',
                            row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-',
                            row.doctor || '-',
                            row.specialization || '-',
                            row.hospital_branch || '-',
                            row.booking_type || '-',
                            row.appointment_status || '-'
                        ];
                    } else if (status === 'rescheduled') {
                        rowData = [
                            index + 1,
                            row.previous_number || '-',
                            row.full_name || '-',
                            row.previous_specialization || '-',
                            normalizeDate(row.previous_date) || '-',
                            row.previous_time ? new Date('1970-01-01T' + row.previous_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-',
                            row.from_to || '-',
                            row.current_number || '-',
                            row.current_specialization || '-',
                            normalizeDate(row.current_date) || '-',
                            row.current_time ? new Date('1970-01-01T' + row.current_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-',
                            row.reason || '-'
                        ];
                    } else {
                        rowData = [
                            index + 1,
                            row.appointment_number || '-',
                            row.full_name || '-',
                            row.patient_number || '-',
                            row.phone || '-',
                            row.email || '-',
                            normalizeDate(row.appointment_date) || '-',
                            row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-',
                            row.doctor || '-',
                            row.specialization || '-',
                            row.booking_type || '-',
                            row.appointment_status || '-'
                        ];
                    }
                    csvRows.push(rowData);
                });

                const csvContent = csvRows.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `${status}_appointments_${new Date().toISOString().replace(/[-:T]/g, '').split('.')[0]}.csv`;
                link.click();
            });

            window.printTable = function () {
                const visibleData = table.rows({ search: 'applied' }).data().toArray();
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
                            body {
                                font-family: 'Arial', sans-serif;
                                padding: 10mm;
                                margin: 0;
                            }
                            .table {
                                width: 100%;
                                max-width: 100%;
                                border-collapse: collapse;
                                font-size: 0.65rem;
                                line-height: 1.1;
                            }
                            .table th, .table td {
                                padding: 3px 5px;
                                border: 1px solid #dee2e6;
                                vertical-align: top;
                                word-wrap: break-word;
                                overflow-wrap: break-word;
                                white-space: normal;
                                width: ${baseWidth}%;
                            }
                            .table td {
                                max-width: 0;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }
                            @media print {
                                body {
                                    width: 210mm;
                                    height: auto;
                                    margin: 8mm;
                                }
                                .table {
                                    font-size: 0.6rem;
                                    line-height: 1.0;
                                }
                                .table th, .table td {
                                    padding: 2px 4px;
                                }
                            }
                            @page {
                                size: A4;
                                margin: 8mm;
                            }
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