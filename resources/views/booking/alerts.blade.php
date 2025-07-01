@extends('layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid px-0">
    <div class="widget shadow-sm mb-0 border-0 rounded-3">
        <div class="card-header text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top" style="background-color: #159ed5;">
            <h4 class="mb-0 d-flex align-items-center">
                <i class="fas fa-table me-2"></i>{{ $title }}
            </h4>
            @if($status == 'alerts')
            <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#raiseAlertModal" style="background-color: #159ed5; border-color: #159ed5;">
                <i class="fas fa-plus me-1"></i>Raise Alert
            </button>
            @endif
        </div>

        <div class="p-3 bg-light border-bottom">
            <div class="d-flex align-items-center gap-3">
                @if($status == 'alerts')
                <div class="action-dropdown">
                    <div class="custom-dropdown d-inline-block">
                        <button class="btn btn-sm btn-primary dropdown-toggle shadow-sm" type="button" id="actionDropdown" style="background-color: #159ed5; border-color: #159ed5;">
                            <i class="fas fa-cog me-1"></i>Actions
                        </button>
                        <ul class="custom-dropdown-menu" id="actionDropdownMenu">
                            <li><a class="custom-dropdown-item" href="#" id="bulk-resolve">Mark Selected as Resolved</a></li>
                        </ul>
                    </div>
                </div>
                @else
                <div class="action-dropdown">
                    <div class="custom-dropdown d-inline-block">
                        <button class="btn btn-sm btn-primary dropdown-toggle shadow-sm" type="button" id="actionDropdown" style="background-color: #159ed5; border-color: #159ed5;">
                            <i class="fas fa-cog me-1"></i>Actions
                        </button>
                        <ul class="custom-dropdown-menu" id="actionDropdownMenu">
                            <li><a class="custom-dropdown-item" href="#" id="bulk-reopen">Reopen Selected Alerts</a></li>
                        </ul>
                    </div>
                </div>
                @endif
                <div class="ms-auto d-flex align-items-center gap-2">
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

        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="alertsTable" class="table table-striped" style="width:100%; font-family: Arial, sans-serif; font-size: 14px;">
                    <thead>
                        <tr>
                            <th width="50"><input type="checkbox" id="selectAll" class="form-check-input select-checkbox"></th>
                            <th>Patient Name</th>
                            <th>Patient Number</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Sender</th>
                            <th>Department</th>
                            <th>Date</th>
                            @if($status == 'resolved_alerts')
                            <th>Feedback</th>
                            <th>Recipient</th>
                            <th>Resolved On</th>
                            @endif
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alerts as $alert)
                        <tr>
                            <td><input type="checkbox" class="alert-checkbox form-check-input row-checkbox" value="{{ $alert['id'] }}"></td>
                            <td>{{ $alert['full_name'] }}</td>
                            <td>{{ $alert['patient_number'] }}</td>
                            <td>{{ $alert['phone'] }}</td>
                            <td title="{{ $alert['urgent_message'] }}">
                                @if(strlen($alert['urgent_message']) > 50)
                                {{ substr($alert['urgent_message'], 0, 50) }}...
                                @else
                                {{ $alert['urgent_message'] }}
                                @endif
                            </td>
                            <td>{{ $alert['sender_name'] }}</td>
                            <td>{{ $alert['sender_department'] }}</td>
                            <td>
                                @if($alert['messaging_date'] && $alert['messaging_date'] !== '-')
                                {{ date('Y-m-d H:i:s', strtotime($alert['messaging_date'])) }}
                                @else
                                -
                                @endif
                            </td>
                            @if($status == 'resolved_alerts')
                            <td title="{{ $alert['feedback'] ?? '-' }}">
                                @if($alert['feedback'] && $alert['feedback'] !== '-' && strlen($alert['feedback']) > 30)
                                {{ substr($alert['feedback'], 0, 30) }}...
                                @else
                                {{ $alert['feedback'] ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $alert['recipient'] ?? '-' }}</td>
                            <td>
                                @if($alert['feedback_date'] && $alert['feedback_date'] !== '-')
                                {{ date('Y-m-d H:i:s', strtotime($alert['feedback_date'])) }}
                                @else
                                -
                                @endif
                            </td>
                            @endif
                            <td>
                                @if($status == 'alerts')
                                @if(isset($alert['status']) && $alert['status'] == 'reopened')
                                <!-- <span style="color: #e60000">Reopened</span> -->
                                <span class="badge rounded-pill bg-danger text-white">Reopened</span>
                                @elseif(isset($alert['status']) && $alert['status'] == 'new_patient')
                                <!-- <span style="color: #159ed5;">New Patient</span> -->
                                <span class="badge rounded-pill" style="background-color: #159ed5; color: #fff;">New Patient</span>
                                @else
                                <span class="badge rounded-pill" style="background-color: #159ed5; color: #fff;">New</span>
                                <!-- <span style="color: #159ed5;">New</span> -->
                                @endif
                                @else
                                <!-- <span style="color: #159ed5;">Resolved</span> -->
                                <span class="badge rounded-pill" style="background-color: #159ed5; color: #fff;">Resolved</span>
                                @endif
                            </td>
                            <td>
                                @if($status == 'alerts')
                                <button class="btn btn-sm shadow-sm {{ isset($alert['status']) && $alert['status'] == 'reopened' ? 're-resolve-alert' : 'resolve-alert' }}" data-id="{{ $alert['id'] }}" style="background-color: #159ed5; border-color: #159ed5; color: white;">
                                    <i class="fas fa-check me-1"></i>Resolve
                                </button>
                                @else
                                <button class="btn btn-sm shadow-sm reopen-alert" data-id="{{ $alert['id'] }}" style="background-color: #159ed5; border-color: #159ed5; color: white;">
                                    <i class="fas fa-undo me-1"></i>Reopen
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($status == 'alerts')
<!-- Raise Alert Modal -->
<div class="modal fade" id="raiseAlertModal" tabindex="-1" aria-labelledby="raiseAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
            <div class="modal-header" style="background-color: #159ed5; color: white;">
                <h5 class="modal-title" id="raiseAlertModalLabel">Raise New Alert (Existing Patient)</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Existing Patient Form -->
            <form id="raiseAlertForm" style="display: none;">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="patientSelect" class="form-label" style="font-weight: normal;">Select Patient</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="patientSearch" list="patientList"
                                placeholder="Search patients by name, number, or phone\" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            <button type="button" class="btn btn-primary btn-sm" id="newPatientBtn" style="display: none; background-color: #159ed5; border-color: #159ed5;">
                                <i class="fas fa-plus me-1"></i>New Patient
                            </button>
                        </div>
                        <datalist id="patientList">
                            @foreach($patients as $patient)
                            <option value="{{ $patient['text'] }}" data-id="{{ $patient['id'] }}">
                                @endforeach
                        </datalist>
                        <select class="form-control form-select form-select-sm mt-2" id="patientSelect" name="appointment_id" required style="display: none; font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            <option value="">Select a patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}">{{ $patient['text'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="urgent_message" class="form-label" style="font-weight: normal;">Urgent Message</label>
                        <textarea class="form-control form-control-sm" id="urgent_message" name="urgent_message" rows="4"
                            required placeholder="Your message." style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sender_name" class="form-label" style="font-weight: normal;">Sender Name</label>
                                <input type="text" class="form-control form-control-sm" id="sender_name" name="sender_name"
                                    required placeholder="Enter name" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sender_department" class="form-label" style="font-weight: normal;">Department</label>
                                <input type="text" class="form-control form-control-sm" id="sender_department" name="sender_department"
                                    required placeholder="Enter department" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm" style="background-color: #159ed5; border-color: #159ed5;"><i class="fas fa-paper-plane me-1"></i>Submit Alert</button>
                </div>
            </form>

            <!-- New Patient Form -->
            <form id="raiseNewPatientAlertForm" style="display: none;">
                @csrf
                <div class="modal-header" style="background-color: #159ed5; color: white;">
                    <h5 class="modal-title" id="raiseNewPatientAlertModalLabel">
                        <button type="button" class="btn btn-outline-secondary btn-sm shadow-sm me-3" id="backToExistingPatient"><i class="fas fa-arrow-left me-1"></i>Back</button>
                        Raise New Alert (New Patient)
                    </h5>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="patient_name" class="form-label" style="font-weight: normal;">Patient Full Name</label>
                        <input type="text" class="form-control form-control-sm" id="patient_name" name="patient_name"
                            required placeholder="Enter patient's complete name" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="patient_number" class="form-label" style="font-weight: normal;">Patient Number <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control form-control-sm" id="patient_number" name="patient_number"
                                placeholder="Enter Pt.Number" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label" style="font-weight: normal;">Phone Number</label>
                            <input type="tel" class="form-control form-control-sm" id="phone" name="phone" required
                                placeholder="Enter number" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_urgent_message" class="form-label" style="font-weight: normal;">Urgent Message</label>
                        <textarea class="form-control form-control-sm" id="new_urgent_message" name="urgent_message" rows="4"
                            required placeholder="Your message" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_sender_name" class="form-label" style="font-weight: normal;">Sender Name</label>
                                <input type="text" class="form-control form-control-sm" id="new_sender_name" name="sender_name"
                                    required placeholder="Your name" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_sender_department" class="form-label" style="font-weight: normal;">Department</label>
                                <input type="text" class="form-control form-control-sm" id="new_sender_department" name="sender_department"
                                    required placeholder="Your department" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="background-color: #159ed5; border-color: #159ed5;"><i class="fas fa-paper-plane me-1"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Feedback Modal for Resolving Alerts -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
            <div class="modal-header" style="background-color: #159ed5; color: white;">
                <h5 class="modal-title" id="feedbackModalLabel">Resolve Alert - Add Feedback</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="feedbackForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="feedback" class="form-label" style="font-weight: normal;">Feedback/Resolution Notes</label>
                        <textarea class="form-control form-control-sm" id="feedback" name="feedback" rows="4"
                            placeholder="Enter detailed notes about how this alert was resolved" required style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="recipient" class="form-label" style="font-weight: normal;">Recipient <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control form-control-sm" id="recipient" name="recipient"
                            placeholder="Enter recipient name or department" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm" style="background-color: #159ed5; border-color: #159ed5;"><i class="fas fa-check me-1"></i>Resolve Alert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Re-Resolve Modal for Reopened Alerts -->
<div class="modal fade" id="reResolveModal" tabindex="-1" aria-labelledby="reResolveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
            <div class="modal-header" style="background-color: #159ed5; color: white;">
                <h5 class="modal-title" id="reResolveModalLabel">Re-Resolve Alert - Update Feedback</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reResolveForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="re_feedback" class="form-label" style="font-weight: normal;">Updated Feedback/Resolution Notes</label>
                        <textarea class="form-control form-control-sm " id="re_feedback" name="feedback" rows="4"
                            placeholder="Update details about how this alert was resolved" required style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="re_recipient" class="form-label" style="font-weight: normal;">Recipient <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control form-control-sm" id="re_recipient" name="recipient"
                            placeholder="Enter recipient name or department" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm " data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="background-color: #159ed5; border-color: #159ed5;"><i class="fas fa-check me-1"></i>Update & Resolve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Feedback Modal -->
<div class="modal fade" id="bulkFeedbackModal" tabindex="-1" aria-labelledby="bulkFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
            <div class="modal-header" style="background-color: #159ed5; color: white;">
                <h5 class="modal-title" id="bulkFeedbackModalLabel">Bulk Resolve Alerts - Add Feedback</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkFeedbackForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_feedback" class="form-label" style="font-weight: normal;">Feedback/Resolution Notes</label>
                        <textarea class="form-control form-control-sm shadow-sm" id="bulk_feedback" name="bulk_feedback" rows="4"
                            placeholder="Enter details about how these alerts were resolved" required style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="bulk_recipient" class="form-label" style="font-weight: normal;">Recipient <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control form-control-sm shadow-sm" id="bulk_recipient" name="bulk_recipient"
                            placeholder="Enter recipient name or department" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm" style="background-color: #159ed5; border-color: #159ed5;"><i class="fas fa-check me-1"></i>Resolve All Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<style>
    .btn-outline-secondary {
        border-color: #d1e9f5;
        color: #159ed5;
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
        color: #159ed5;
    }

    /* Ensure table headers and cells use consistent font and color */
    #alertsTable th,
    #alertsTable td {
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #333;
    }
</style>

<script>
    $(document).ready(function() {
        let currentAlertId = null;
        let selectedAlertIds = [];

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

        if ($.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().destroy();
        }

        var table = $('#alertsTable').DataTable({
            processing: true,
            destroy: true,
            searching: false, // Disable default DataTables search
            language: {
                emptyTable: @if($status == 'alerts')
                'No active alerts found'
                @else 'No resolved alerts found'
                @endif
            },
            columnDefs: [{
                targets: 0,
                orderable: false,
                searchable: false,
                className: 'select-checkbox'
            }],
            order: [],
            pageLength: parseInt($('#dataTable_length').val()) || 50,
            lengthMenu: [
                [50, 100, 200, -1],
                ["50", "100", "200", "All"]
            ],
            responsive: true,
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

        function applySearchFilter() {
            const searchTerm = $('#search').val().toLowerCase();
            $.fn.dataTable.ext.search.pop();
            $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
                const row = table.row(dataIndex).data();
                if (!row) return false;

                const searchableFields = [
                    row.full_name,
                    row.patient_number,
                    row.phone,
                    row.urgent_message,
                    row.sender_name,
                    row.sender_department,
                    row.messaging_date,
                    row.feedback || '',
                    row.recipient || '',
                    row.feedback_date || '',
                    row.status
                ].map(val => (val || '').toString().toLowerCase());

                return !searchTerm || searchableFields.some(field => field.includes(searchTerm));
            });
            table.draw();
            console.log('Client-side search filter applied:', {
                searchTerm
            });
        }

        $('#search').on('keyup', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                applySearchFilter();
            }
        });

        $('#alertsTable tbody').on('click', '.row-checkbox', function() {
            $(this).closest('tr').toggleClass('selected');
            toggleBulkActionButtons();
        });

        $('#selectAll').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.alert-checkbox').prop('checked', isChecked).closest('tr').toggleClass('selected', isChecked);
            toggleBulkActionButtons();
        });

        function toggleBulkActionButtons() {
            const checkedCount = $('.alert-checkbox:checked').length;
            $('.action-dropdown').toggle(checkedCount > 0);
        }

        $('#bulk-resolve').on('click', function(e) {
            e.preventDefault();
            selectedAlertIds = $('.alert-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedAlertIds.length === 0) {
                alert('Please select at least one alert to resolve.');
                return;
            }

            $('#bulk-count').text(selectedAlertIds.length);
            $('#bulkFeedbackModal').modal('show');
            $('#actionDropdownMenu').removeClass('show');
        });

        $('#bulk-reopen').on('click', function(e) {
            e.preventDefault();
            selectedAlertIds = $('.alert-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedAlertIds.length === 0) {
                alert('Please select at least one alert to reopen.');
                return;
            }

            if (confirm('Are you sure you want to reopen the selected alerts?')) {
                $.ajax({
                    url: '{{ route("alerts.bulkReopen") }}',
                    method: 'PATCH',
                    data: {
                        alert_ids: selectedAlertIds
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function() {
                        setTimeout(function() {
                            window.location.href = '{{ route("booking.alerts") }}';
                        }, 100);
                    },
                    error: function(xhr) {
                        let errMsg = 'Failed to reopen alerts.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errMsg = xhr.responseJSON.error;
                        }
                        alert(errMsg);
                    }
                });
            }
            $('#actionDropdownMenu').removeClass('show');
        });

        $('#bulkFeedbackForm').on('submit', function(e) {
            e.preventDefault();
            const feedbackData = {
                alert_ids: selectedAlertIds,
                feedback: $('#bulk_feedback').val(),
                recipient: $('#bulk_recipient').val(),
                feedback_date: new Date().toISOString().slice(0, 19).replace('T', ' ')
            };

            $.ajax({
                url: '{{ route("alerts.bulkResolve") }}',
                method: 'PATCH',
                data: feedbackData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function() {
                    $('#bulkFeedbackModal').modal('hide');
                    $('#bulkFeedbackForm')[0].reset();
                    setTimeout(function() {
                        window.location.href = '{{ route("booking.resolved_alerts") }}';
                    }, 100);
                },
                error: function(xhr) {
                    let errMsg = 'Failed to resolve alerts.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errMsg = xhr.responseJSON.error;
                    }
                    alert(errMsg);
                }
            });
        });

        @if($status == 'alerts')

        function showExistingPatientForm() {
            $('#raiseAlertForm').show();
            $('#raiseNewPatientAlertForm').hide();
        }

        function showNewPatientForm() {
            $('#raiseAlertForm').hide();
            $('#raiseNewPatientAlertForm').show();
        }

        function toggleNewPatientButton() {
            const patientSelect = $('#patientSelect').val();
            $('#newPatientBtn').toggle(!patientSelect);
        }

        $('#patientSearch').on('blur keyup', function(e) {
            if (e.type === 'blur' || e.key === 'Enter') {
                const searchValue = this.value.trim();
                const options = document.getElementById('patientList').options;
                const select = document.getElementById('patientSelect');

                let found = false;
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value.trim().toLowerCase() === searchValue.toLowerCase()) {
                        select.value = options[i].getAttribute('data-id');
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    select.value = '';
                }
                toggleNewPatientButton();
            }
        });

        $('#patientSelect').on('change', function() {
            var patientSearch = document.getElementById('patientSearch');
            if (!this.value) {
                patientSearch.value = '';
            }
            toggleNewPatientButton();
        });

        $('#newPatientBtn').on('click', function() {
            showNewPatientForm();
        });

        $('#backToExistingPatient').on('click', function() {
            showExistingPatientForm();
            $('#raiseAlertForm')[0].reset();
            $('#patientSearch').val('');
            $('#patientSelect').val('');
            toggleNewPatientButton();
        });

        $('#raiseAlertForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.post('{{ route("alerts.store") }}', formData)
                .done(function() {
                    $('#raiseAlertModal').modal('hide');
                    $('#raiseAlertForm')[0].reset();
                    $('#patientSearch').val('');
                    $('#patientSelect').val('');
                    window.location.href = '{{ route("booking.alerts") }}';
                })
                .fail(function(xhr) {
                    let errMsg = 'Failed to create alert.';
                    if (xhr.status === 409 && xhr.responseJSON?.error) {
                        errMsg = xhr.responseJSON.error;
                        alert(errMsg);
                        window.location.href = '{{ route("booking.alerts") }}';
                        return;
                    } else if (xhr.responseJSON?.errors) {
                        errMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    alert(errMsg);
                });
        });

        $('#raiseNewPatientAlertForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.post('{{ route("alerts.storeNewPatient") }}', formData)
                .done(function() {
                    $('#raiseAlertModal').modal('hide');
                    $('#raiseNewPatientAlertForm')[0].reset();
                    window.location.href = '{{ route("booking.alerts") }}';
                })
                .fail(function(xhr) {
                    let errMsg = 'Failed to create alert for new patient.';
                    if (xhr.status === 409 && xhr.responseJSON?.error) {
                        errMsg = xhr.responseJSON.error;
                        alert(errMsg);
                        window.location.href = '{{ route("booking.alerts") }}';
                        return;
                    } else if (xhr.responseJSON?.errors) {
                        errMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    alert(errMsg);
                });
        });

        $('#feedbackForm').on('submit', function(e) {
            e.preventDefault();
            const feedbackData = {
                feedback: $('#feedback').val(),
                recipient: $('#recipient').val(),
                feedback_date: new Date().toISOString().slice(0, 19).replace('T', ' ')
            };

            $.ajax({
                url: `/alerts/${currentAlertId}/resolve`,
                method: 'PATCH',
                data: feedbackData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function() {
                    $('#feedbackModal').modal('hide');
                    $('#feedbackForm')[0].reset();
                    setTimeout(function() {
                        window.location.href = '{{ route("booking.resolved_alerts") }}';
                    }, 100);
                },
                error: function(xhr) {
                    let errMsg = 'Failed to resolve alert.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errMsg = xhr.responseJSON.error;
                    }
                    alert(errMsg);
                }
            });
        });

        $('#reResolveForm').on('submit', function(e) {
            e.preventDefault();
            const feedbackData = {
                feedback: $('#re_feedback').val(),
                recipient: $('#re_recipient').val(),
                feedback_date: new Date().toISOString().slice(0, 19).replace('T', ' ')
            };

            $.ajax({
                url: `/alerts/${currentAlertId}/resolve`,
                method: 'PATCH',
                data: feedbackData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function() {
                    $('#reResolveModal').modal('hide');
                    $('#reResolveForm')[0].reset();
                    setTimeout(function() {
                        window.location.reload();
                    }, 100);
                },
                error: function(xhr) {
                    let errMsg = 'Failed to re-resolve alert.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errMsg = xhr.responseJSON.error;
                    }
                    alert(errMsg);
                }
            });
        });

        $('#raiseAlertModal').on('show.bs.modal', function() {
            showExistingPatientForm();
            $('#raiseAlertForm')[0].reset();
            $('#patientSearch').val('');
            $('#patientSelect').val('');
            toggleNewPatientButton();
        });
        @endif

        $('#alertsTable tbody').on('click', '.resolve-alert, .re-resolve-alert', function() {
            currentAlertId = $(this).data('id');
            $('#feedback').val('');
            $('#recipient').val('');
            if ($(this).hasClass('re-resolve-alert')) {
                $.ajax({
                    url: `/alerts/${currentAlertId}/details`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        $('#re_feedback').val(response.feedback || '');
                        $('#re_recipient').val(response.recipient || '');
                        $('#reResolveModal').modal('show');
                    },
                    error: function() {
                        $('#re_feedback').val('');
                        $('#re_recipient').val('');
                        $('#reResolveModal').modal('show');
                    }
                });
            } else {
                $('#feedbackModal').modal('show');
            }
        });

        $('#alertsTable tbody').on('click', '.reopen-alert', function() {
            var alertId = $(this).data('id');
            if (confirm('Are you sure you want to reopen this alert?')) {
                $.ajax({
                    url: `/alerts/${alertId}/reopen`,
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function() {
                        setTimeout(function() {
                            window.location.href = '{{ route("booking.alerts") }}';
                        }, 100);
                    },
                    error: function(xhr) {
                        let errMsg = 'Failed to reopen alert.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errMsg = xhr.responseJSON.error;
                        }
                        alert(errMsg);
                    }
                });
            }
        });
    });
</script>
@endsection