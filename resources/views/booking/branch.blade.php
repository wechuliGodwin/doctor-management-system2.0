<!-- resources/views/booking/branch.blade.php -->
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
                            <a href="{{ route('booking.branch', $branch) }}?export_csv=1" class="btn btn-secondary btn-sm shadow-sm" id="export-csv-btn">
                                <i class="fas fa-file-csv me-1"></i>CSV
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success m-3 rounded-0">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

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

            <div class="card-body p-0">
                @if ($appointments->isEmpty())
                    <div class="p-4 text-center text-muted">
                        No appointments found for {{ $title }}.
                    </div>
                @else
                    <table class="table table-bordered table-sm" id="appointmentsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="select-all"></th>
                                <th>S.No</th>
                                <th>Pt Name</th>
                                <th>Pt No.</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Booking Type</th>
                                <th>Status</th>
                                <th>Hospital Branch</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTable will populate this -->
                        </tbody>
                    </table>
                @endif
            </div>

            @foreach ($appointments as $appointment)
                @include('booking.view', [
                    'appointment' => $appointment,
                    'specializations' => $specializations,
                    'status' => 'branch',
                    'branch' => $branch
                ])
            @endforeach
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

   <script>
    $(document).ready(function () {
        console.log('Document ready, initializing scripts for branch: {{ $branch }}');

        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken) {
            console.error('CSRF token not found.');
            alert('CSRF token missing. Please contact the administrator.');
        }

        const $dropdownButton = $('#actionDropdown');
        const $dropdownMenu = $('#actionDropdownMenu');
        $dropdownButton.on('click', function (e) {
            e.preventDefault();
            $dropdownMenu.toggleClass('show');
        });

        $(document).on('click', function (e) {
            if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {
                $dropdownMenu.removeClass('show');
            }
        });

        const rawAppointments = @json($appointments);
        const table = $('#appointmentsTable').DataTable({
            data: rawAppointments,
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'select-checkbox',
                    render: (data, type, row) => `<input type="checkbox" class="row-checkbox" value="${row.id}-${row.source_table}">`
                },
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
                { data: 'hospital_branch', defaultContent: '-', render: (data) => data ? data.charAt(0).toUpperCase() + data.slice(1) : '-' },
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
            ],
            pageLength: 50,
            lengthMenu: [[50, 100, 200, -1], ["50", "100", "200", "All"]],
            responsive: true,
            ordering: true,
            searching: true,
            order: [[6, 'desc']],
            language: {
                emptyTable: 'No appointments found for this branch.'
            },
            dom: 'lfrtip',
            rowCallback: function(row, data) {
                if (data.appointment_status === 'honoured') {
                    $(row).addClass('table-success');
                }
            }
        });

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

        const debouncedApplyFilters = _.debounce(applyFilters, 300);

        function applyFilters() {
            const startDateInput = $('#start_date').val();
            const endDateInput = $('#end_date').val();
            const searchTerm = $('#search').val().toLowerCase();

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

            $.fn.dataTable.ext.search.pop();
            $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
                const row = table.row(dataIndex).data();
                let appointmentDate = normalizeDate(row.appointment_date);

                let dateMatch = true;
                if (appointmentDate) {
                    if (startDate && !endDate) {
                        dateMatch = appointmentDate === startDate;
                    } else if (startDate && endDate) {
                        dateMatch = appointmentDate >= startDate && appointmentDate <= endDate;
                    } else if (startDate) {
                        dateMatch = appointmentDate >= startDate;
                    } else if (endDate) {
                        dateMatch = appointmentDate <= endDate;
                    }
                } else {
                    dateMatch = !startDate && !endDate;
                }

                let searchMatch = true;
                if (searchTerm) {
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
                        row.hospital_branch,
                        row.booking_type,
                        row.appointment_status,
                        row.notes,
                        row.doctor_comments
                    ].map(val => (val || '').toString().toLowerCase());

                    searchMatch = searchableFields.some(field => field.includes(searchTerm));
                }

                return dateMatch && searchMatch;
            });

            table.draw();
        }

        $('#apply-filters').on('click', () => {
            $('#apply-filters').html('<i class="fas fa-spinner fa-spin me-1"></i>Applying...');
            debouncedApplyFilters();
            setTimeout(() => {
                $('#apply-filters').html('<i class="fas fa-filter me-1"></i>Apply Filter');
            }, 500);
        });

        $('#start_date, #end_date, #search').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#apply-filters').html('<i class="fas fa-spinner fa-spin me-1"></i>Applying...');
                debouncedApplyFilters();
                setTimeout(() => {
                    $('#apply-filters').html('<i class="fas fa-filter me-1"></i>Apply Filter');
                }, 500);
            }
        });

        $('#reset-filters').on('click', () => {
            $('#filter-form')[0].reset();
            $.fn.dataTable.ext.search.pop();
            table.search('').draw();
        });

        $('#search').on('keyup', debouncedApplyFilters);

        $('#appointmentsTable').on('click', '.row-checkbox', function () {
            $(this).closest('tr').toggleClass('selected');
        });

        $('#appointmentsTable').on('click', '.select-all', function () {
            const isChecked = $(this).prop('checked');
            $('#appointmentsTable tbody .row-checkbox').prop('checked', isChecked).closest('tr').toggleClass('selected', isChecked);
        });

        function updateAppointments(action, ids) {
            $.ajax({
                url: action === 'came' ? '{{ route("booking.mark-came") }}' : '{{ route("booking.mark-missed") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    ids: ids,
                    status: 'branch',
                    _token: csrfToken
                },
                beforeSend: function () {
                    $('#actionDropdown').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
                },
                success: function (response) {
                    alert(response.message || `Appointments marked as ${action === 'came' ? 'honoured' : 'missed'} successfully.`);
                    window.location.reload();
                },
                error: function (xhr) {
                    let errorMessage = `Failed to mark appointments as ${action === 'came' ? 'honoured' : 'missed'}.`;
                    if (xhr.status === 419) {
                        errorMessage = 'Session expired or CSRF token mismatch. Please refresh the page and try again.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'You do not have permission to perform this action.';
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    alert(`Error: ${errorMessage}`);
                },
                complete: function () {
                    $('#actionDropdown').prop('disabled', false).html('<i class="fas fa-cog me-1"></i> Actions');
                }
            });
        }

        $(document).on('click', '#mark-came', function (e) {
            e.preventDefault();
            const selectedRows = table.rows('.selected').nodes().to$().find('.row-checkbox:checked');
            if (!selectedRows.length) {
                alert('No rows selected.');
                return;
            }
            const ids = selectedRows.map((i, el) => $(el).val()).get();
            updateAppointments('came', ids);
            $('#actionDropdownMenu').removeClass('show');
        });

        $(document).on('click', '#mark-missed', function (e) {
            e.preventDefault();
            const selectedRows = table.rows('.selected').nodes().to$().find('.row-checkbox:checked');
            if (!selectedRows.length) {
                alert('No rows selected.');
                return;
            }
            const ids = selectedRows.map((i, el) => $(el).val()).get();
            updateAppointments('missed', ids);
            $('#actionDropdownMenu').removeClass('show');
        });

        $('#export-csv-btn').on('click', function (e) {
            e.preventDefault();
            window.location.href = '{{ route("booking.branch", $branch) }}?export_csv=1';
        });

        window.printTable = function () {
            const visibleData = table.rows({ search: 'applied' }).data().toArray();
            const columnCount = 12;

            const baseWidth = Math.min(100 / columnCount, 8);
            let tableHtml = `
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Pt Name</th>
                            <th>Pt No.</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Booking Type</th>
                            <th>Status</th>
                            <th>Hospital Branch</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            visibleData.forEach((row, index) => {
                tableHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.full_name || '-'}</td>
                        <td>${row.patient_number || '-'}</td>
                        <td>${row.phone || '-'}</td>
                        <td>${row.email || '-'}</td>
                        <td>${normalizeDate(row.appointment_date) || '-'}</td>
                        <td>${row.appointment_time ? new Date('1970-01-01T' + row.appointment_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                        <td>${row.doctor || '-'}</td>
                        <td>${row.specialization || '-'}</td>
                        <td>${row.booking_type || '-'}</td>
                        <td>${row.appointment_status || '-'}</td>
                        <td>${row.hospital_branch ? row.hospital_branch.charAt(0).toUpperCase() + row.hospital_branch.slice(1) : '-'}</td>
                    </tr>
                `;
            });

            tableHtml += '</tbody></table>';

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print {{ $title }} Appointments</title>
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
                            @page {
                                size: A4;
                                margin: 8mm;
                            }
                        }
                    </style>
                </head>
                <body>
                    <h4>{{ $title }} Appointments</h4>
                    ${tableHtml}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        };

        // Show/hide cancellation reason field
        $('select[name="appointment_status"]').on('change', function () {
            const appointmentId = $(this).attr('id').split('_')[2];
            const cancellationField = $(`#cancellation_reason_${appointmentId}`);
            if ($(this).val() === 'cancelled') {
                cancellationField.prop('required', true).closest('.mb-2').show();
            } else {
                cancellationField.prop('required', false).closest('.mb-2').hide();
            }
        });
    });
</script>
@endsection