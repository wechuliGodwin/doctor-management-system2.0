@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid px-0">
        <div class="card shadow-sm mb-0 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                <h5 class="mb-0">{{ $title }}</h5>
                <span>{{ now()->format('F d, Y') }}</span>
            </div>
            <div class="card-body p-0">
                <!-- Filters Form -->
                <form method="GET" action="{{ route('booking.reports') }}" id="reports-filter-form"
                    class="bg-light p-2 border-bottom">
                    <div class="row g-2 mb-0">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="report_type" class="small fw-bold">Report Type</label>
                                <select name="report_type" id="report_type" class="form-select form-select-sm">
                                    <option value="all" {{ request('report_type', 'all') == 'all' ? 'selected' : '' }}>All
                                        Appointments</option>
                                    <option value="regular" {{ request('report_type') == 'regular' ? 'selected' : '' }}>
                                        Regular Appointments</option>
                                    <option value="new" {{ request('report_type') == 'new' ? 'selected' : '' }}>New
                                        Appointments</option>
                                    <option value="review" {{ request('report_type') == 'review' ? 'selected' : '' }}>Review
                                        Appointments</option>
                                    <option value="postop" {{ request('report_type') == 'postop' ? 'selected' : '' }}>Post-Op
                                        Appointments</option>
                                    <option value="external_approved" {{ request('report_type') == 'external_approved' ? 'selected' : '' }}>External Approved</option>
                                    <option value="external_pending" {{ request('report_type') == 'external_pending' ? 'selected' : '' }}>External Pending</option>
                                    <option value="cancelled" {{ request('report_type') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled Appointments</option>
                                    <option value="rescheduled" {{ request('report_type') == 'rescheduled' ? 'selected' : '' }}>Rescheduled Appointments</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="start_date" class="small fw-bold">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                                    value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="end_date" class="small fw-bold">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="global-search" class="small fw-bold">Global Search</label>
                                <input type="text" id="global-search" class="form-control form-control-sm"
                                    placeholder="Search all fields">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="per_page" class="small fw-bold">Per Page</label>
                                <select name="per_page" id="per_page" class="form-select form-select-sm">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-1">
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            <button type="button" id="export-csv" class="btn btn-secondary btn-sm">CSV</button>
                            <button type="button" class="btn btn-info btn-sm" onclick="printTable()">Print</button>
                            <button type="button" id="reset-filters" class="btn btn-warning btn-sm">Reset</button>
                        </div>
                    </div>
                </form>

                <!-- Reports Table -->
                <div class="table-responsive">
                    <table id="reports-table" class="table table-striped table-bordered table-sm table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Appointment Number</th>
                                <th>Full Name</th>
                                <th>Patient Number</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Specialization</th>
                                <th>Doctor</th>
                                <th>Booking Type</th>
                                <th>Status</th>
                                <th>Hospital Branch</th>
                                <th>Source</th>
                                <th>Cancellation Reason</th>
                                <th>Reschedule Reason</th>
                                <th>Action Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .card {
            border-radius: 0;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: linear-gradient(90deg, #159ed5 0%, #159ed5 100%);
            padding: 0.5rem 1rem;
            border-bottom: none;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-control-sm,
        .form-select-sm {
            padding: 0.2rem 0.5rem;
            font-size: 0.85rem;
            height: 28px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }

        .btn-sm {
            padding: 0.2rem 0.5rem;
            font-size: 0.85rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #159ed5;
            border-color: #159ed5;
        }

        .btn-primary:hover {
            background-color: #127ea6;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem 0.5rem;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .table-responsive {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            border: none;
            position: relative;
        }

        .table-hover tbody tr:hover {
            background-color: #e6eef5;
        }

        #reports-table_wrapper .dataTables_filter {
            display: none;
        }
    </style>

    <script>
        $(document).ready(function () {
            console.log('Document ready, initializing DataTables');
            console.log('Appointments count: {{ $allAppointments->count() }}');

            // Suppress DataTables warning alerts
            $.fn.dataTable.ext.errMode = 'none';

            // DataTables configuration
            const table = $('#reports-table').DataTable({
                data: @json($allAppointments),
                columns: [
                    { data: 'appointment_number', defaultContent: '' },
                    { data: 'full_name', defaultContent: '' },
                    { data: 'patient_number', defaultContent: '' },
                    { data: 'phone', defaultContent: '' },
                    { data: 'email', defaultContent: '' },
                    {
                        data: 'appointment_date',
                        defaultContent: '',
                        render: (data) => data ? new Date(data).toLocaleDateString('en-CA') : ''
                    },
                    {
                        data: 'appointment_time',
                        defaultContent: '',
                        render: (data) => {
                            if (!data) return '';
                            // Handle both time formats (HH:MM:SS and HH:MM)
                            const timeParts = data.split(':');
                            if (timeParts.length >= 2) {
                                const hours = parseInt(timeParts[0]);
                                const minutes = parseInt(timeParts[1]);
                                const date = new Date();
                                date.setHours(hours, minutes, 0);
                                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            }
                            return data;
                        }
                    },
                    { data: 'specialization', defaultContent: '' },
                    { data: 'doctor', defaultContent: '' },
                    { data: 'booking_type', defaultContent: '' },
                    {
                        data: 'appointment_status',
                        defaultContent: '',
                        render: (data) => {
                            if (!data) return '';
                            // Add color coding for different statuses
                            const statusColors = {
                                'pending': 'warning',
                                'confirmed': 'success',
                                'cancelled': 'danger',
                                'completed': 'info',
                                'rescheduled': 'secondary'
                            };
                            const colorClass = statusColors[data.toLowerCase()] || 'primary';
                            return `<span class="badge bg-${colorClass}">${data}</span>`;
                        }
                    },
                    { data: 'hospital_branch', defaultContent: '' },
                    {
                        data: 'source_table',
                        defaultContent: '',
                        render: (data) => {
                            if (!data) return '';
                            return `<small class="text-muted">${data}</small>`;
                        }
                    },
                    { data: 'cancellation_reason', defaultContent: '' },
                    { data: 'rescheduled_reason', defaultContent: '' },
                    {
                        data: 'rescheduled_at',
                        defaultContent: '',
                        render: (data) => {
                            if (!data) return '';
                            const date = new Date(data);
                            return date.toLocaleDateString('en-CA') + ' ' +
                                date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        }
                    }
                ],
                pageLength: {{ request('per_page', 10) }},
                lengthMenu: [10, 25, 50, 100],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                autoWidth: false,
                scrollX: true,
                scrollY: 'calc(100vh - 200px)',
                scrollCollapse: true,
                dom: 'rtip',
                order: [[5, 'desc']], // Sort by appointment_date
                language: {
                    emptyTable: 'No appointments found',
                    info: 'Showing _START_ to _END_ of _TOTAL_ appointments',
                    infoEmpty: 'Showing 0 to 0 of 0 appointments',
                    infoFiltered: '(filtered from _MAX_ total appointments)'
                },
                initComplete: function () {
                    console.log('DataTables initialized');
                }
            });

            // Apply client-side filters
            function applyFilters() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const globalSearch = $('#global-search').val().toLowerCase();

                $.fn.dataTable.ext.search.pop(); // Clear previous filters
                $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
                    const row = table.row(dataIndex).data();
                    const appointmentDate = row.appointment_date ? new Date(row.appointment_date).toISOString().split('T')[0] : null;

                    // Date range filter
                    if (startDate && appointmentDate < startDate) return false;
                    if (endDate && appointmentDate > endDate) return false;

                    // Global search filter
                    if (globalSearch) {
                        const searchableFields = [
                            row.appointment_number,
                            row.full_name,
                            row.patient_number,
                            row.phone,
                            row.email,
                            row.specialization,
                            row.doctor,
                            row.booking_type,
                            row.appointment_status,
                            row.hospital_branch,
                            row.source_table,
                            row.cancellation_reason,
                            row.rescheduled_reason
                        ].map(val => (val || '').toString().toLowerCase());

                        if (!searchableFields.some(field => field.includes(globalSearch))) {
                            return false;
                        }
                    }

                    return true;
                });

                table.draw();
            }

            // Event listeners
            $('#global-search').on('input', applyFilters);

            // Reset filters
            $('#reset-filters').on('click', function () {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#global-search').val('');
                $('#report_type').val('all');
                $('#per_page').val('10');
                $.fn.dataTable.ext.search.pop();
                table.search('').columns().search('').draw();
            });

            // CSV export (client-side)
            $('#export-csv').on('click', function () {
                const visibleData = table.rows({ search: 'applied' }).data().toArray();
                if (!visibleData.length) {
                    alert('No data to export.');
                    return;
                }

                const headers = [
                    'Appointment Number', 'Full Name', 'Patient Number', 'Phone', 'Email',
                    'Appointment Date', 'Appointment Time', 'Specialization', 'Doctor',
                    'Booking Type', 'Status', 'Hospital Branch', 'Source',
                    'Cancellation Reason', 'Reschedule Reason', 'Action Date'
                ];

                const csvRows = [headers];
                visibleData.forEach(row => {
                    csvRows.push([
                        row.appointment_number || '',
                        row.full_name || '',
                        row.patient_number || '',
                        row.phone || '',
                        row.email || '',
                        row.appointment_date ? new Date(row.appointment_date).toLocaleDateString('en-CA') : '',
                        row.appointment_time || '',
                        row.specialization || '',
                        row.doctor || '',
                        row.booking_type || '',
                        row.appointment_status || '',
                        row.hospital_branch || '',
                        row.source_table || '',
                        row.cancellation_reason || '',
                        row.rescheduled_reason || '',
                        row.rescheduled_at ? new Date(row.rescheduled_at).toLocaleDateString('en-CA') + ' ' + new Date(row.rescheduled_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''
                    ]);
                });

                const csvContent = csvRows.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `appointments_report_{{ request('report_type', 'all') }}_${new Date().toISOString().replace(/[-:T]/g, '').split('.')[0]}.csv`;
                link.click();
            });

            // Print function
            window.printTable = function () {
                const visibleData = table.rows({ search: 'applied' }).data().toArray();
                let tableHtml = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Appointment #</th><th>Full Name</th><th>Patient #</th>
                                    <th>Phone</th><th>Email</th><th>Date</th><th>Time</th>
                                    <th>Specialization</th><th>Doctor</th><th>Type</th>
                                    <th>Status</th><th>Branch</th><th>Source</th>
                                    <th>Cancel Reason</th><th>Reschedule Reason</th><th>Action Date</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                visibleData.forEach(row => {
                    tableHtml += `
                            <tr>
                                <td>${row.appointment_number || ''}</td>
                                <td>${row.full_name || ''}</td>
                                <td>${row.patient_number || ''}</td>
                                <td>${row.phone || ''}</td>
                                <td>${row.email || ''}</td>
                                <td>${row.appointment_date ? new Date(row.appointment_date).toLocaleDateString('en-CA') : ''}</td>
                                <td>${row.appointment_time || ''}</td>
                                <td>${row.specialization || ''}</td>
                                <td>${row.doctor || ''}</td>
                                <td>${row.booking_type || ''}</td>
                                <td>${row.appointment_status || ''}</td>
                                <td>${row.hospital_branch || ''}</td>
                                <td>${row.source_table || ''}</td>
                                <td>${row.cancellation_reason || ''}</td>
                                <td>${row.rescheduled_reason || ''}</td>
                                <td>${row.rescheduled_at ? new Date(row.rescheduled_at).toLocaleDateString('en-CA') + ' ' + new Date(row.rescheduled_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''}</td>
                            </tr>
                        `;
                });
                tableHtml += '</tbody></table>';

                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                                <html>
                                <head>
                                    <title>Print {{ $title }}</title>
                                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                                    <style>
                                        body {
                                            font-family: 'Helvetica', 'Arial', sans-serif;
                                            padding: 15mm;
                                            margin: 0;
                                            color: #333;
                                        }
                                        .header {
                                            text-align: center;
                                            margin-bottom: 10mm;
                                            border-bottom: 2px solid #159ed5;
                                            padding-bottom: 5mm;
                                        }
                                        .header h1 {
                                            font-size: 1.5rem;
                                            margin: 0;
                                            color: #159ed5;
                                        }
                                        .header p {
                                            font-size: 0.9rem;
                                            margin: 2mm 0;
                                            color: #555;
                                        }
                                        .table {
                                            width: 100%;
                                            max-width: 100%;
                                            border-collapse: collapse;
                                            font-size: 0.7rem;
                                            line-height: 1.2;
                                        }
                                        .table th, .table td {
                                            border: 1px solid #ccc;
                                            padding: 3px 5px;
                                            vertical-align: top;
                                            word-wrap: break-word;
                                            overflow-wrap: break-word;
                                            white-space: normal;
                                            width: 6.67%;
                                        }
                                        .table th {
                                            background: #343a40;
                                            color: #fff;
                                            font-weight: 600;
                                            text-transform: uppercase;
                                            font-size: 0.65rem;
                                            border-bottom: 2px solid #159ed5;
                                        }
                                        .table td {
                                            max-width: 0;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            background: #fff;
                                        }
                                        .table tr:nth-child(even) td {
                                            background: #f8f9fa;
                                        }
                                        .table th:nth-child(2), .table td:nth-child(2) { width: 10%; }
                                        .table th:nth-child(5), .table td:nth-child(5) { width: 8%; }
                                        .table th:nth-child(7), .table td:nth-child(7) { width: 9%; }
                                        .table th:nth-child(13), .table td:nth-child(13) { width: 8%; }
                                        .table th:nth-child(14), .table td:nth-child(14) { width: 8%; }
                                        .table th:nth-child(15), .table td:nth-child(15) { width: 10%; }
                                        .footer {
                                            position: fixed;
                                            bottom: 10mm;
                                            right: 15mm;
                                            font-size: 0.8rem;
                                            color: #555;
                                        }
                                        @media print {
                                            body {
                                                width: 210mm;
                                                height: auto;
                                                margin: 0;
                                            }
                                            .header {
                                                position: static;
                                                margin-bottom: 8mm;
                                            }
                                            .table {
                                                font-size: 0.65rem;
                                                line-height: 1.1;
                                            }
                                            .table th, .table td {
                                                padding: 2px 4px;
                                            }
                                            @page {
                                                size: A4 landscape;
                                                margin: 10mm;
                                            }
                                            .footer {
                                                position: fixed;
                                                bottom: 5mm;
                                            }
                                            .table tr {
                                                page-break-inside: avoid;
                                            }
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class="header">
                                        <h1>{{ $title }}</h1>
                                        <p>Generated on {{ now()->format('F d, Y') }}</p>
                                        ${filterText ? `<p>Filters: ${filterText}</p>` : ''}
                                    </div>
                                    ${tableHtml}
                                    <div class="footer">
                                        Page <span class="page-number"></span> of <span class="total-pages"></span>
                                    </div>
                                </body>
                                </html>
                            `);

                // Add page numbers
                const pageNumbers = printWindow.document.querySelectorAll('.page-number');
                const totalPages = printWindow.document.querySelectorAll('.total-pages');
                pageNumbers.forEach((el, index) => el.textContent = index + 1);
                totalPages.forEach(el => el.textContent = pageNumbers.length);

                printWindow.document.close();
                printWindow.print();
                printWindow.close();
            };
        });
    </script>
@endsection