@extends('layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary fw-bold">{{ $title }}</h4>
                        @if($status == 'alerts')
                        <button type="button" class="btn btn-primary px-4 py-2" data-bs-toggle="modal"
                            data-bs-target="#raiseAlertModal">
                            <i class="fas fa-plus me-2"></i>Raise Alert
                        </button>
                        @endif
                    </div>
                </div>
                
                @if(count($alerts))
                <div class="px-4 py-3" id="bulk-action-buttons" style="display: none;">
                    <div class="bg-light p-3 rounded">
                        @if($status == 'alerts')
                        <button type="button" class="btn btn-primary px-4" id="bulk-resolve">
                            <i class="fas fa-check me-2"></i>Mark Selected as Resolved
                        </button>
                        @else
                        <button type="button" class="btn btn-warning px-4" id="bulk-reopen">
                            <i class="fas fa-undo me-2"></i>Reopen Selected Alerts
                        </button>
                        @endif
                    </div>
                </div>
                @endif
                
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="alertsTable" class="table table-striped table-hover" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50"><input type="checkbox" id="selectAll" class="form-check-input"></th>
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
                                    <td><input type="checkbox" class="alert-checkbox form-check-input" value="{{ $alert['id'] }}"></td>
                                    <td class="fw-medium">{{ $alert['full_name'] }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $alert['patient_number'] }}</span></td>
                                    <td>{{ $alert['phone'] }}</td>
                                    <td title="{{ $alert['urgent_message'] }}">
                                        @if(strlen($alert['urgent_message']) > 50)
                                        {{ substr($alert['urgent_message'], 0, 50) }}...
                                        @else
                                        {{ $alert['urgent_message'] }}
                                        @endif
                                    </td>
                                    <td class="fw-medium">{{ $alert['sender_name'] }}</td>
                                    <td><span class="badge bg-secondary">{{ $alert['sender_department'] }}</span></td>
                                    <td>
                                        @if($alert['messaging_date'] && $alert['messaging_date'] !== '-')
                                        <small>{{ date('Y-m-d H:i:s', strtotime($alert['messaging_date'])) }}</small>
                                        @else
                                        <span class="text-muted">-</span>
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
                                        <small>{{ date('Y-m-d H:i:s', strtotime($alert['feedback_date'])) }}</small>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @endif
                                    <td>
                                        @if($status == 'alerts')
                                        @if(isset($alert['status']) && $alert['status'] == 'reopened')
                                        <span class="badge bg-warning text-dark">Reopened</span>
                                        @elseif(isset($alert['status']) && $alert['status'] == 'new_patient')
                                        <span class="badge bg-primary">New Patient</span>
                                        @else
                                        <span class="badge bg-info">New</span>
                                        @endif
                                        @else
                                        <span class="badge bg-success">Resolved</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($status == 'alerts')
                                        @if(isset($alert['status']) && $alert['status'] == 'reopened')
                                        <button class="btn btn-warning btn-sm re-resolve-alert" data-id="{{ $alert['id'] }}">
                                            <i class="fas fa-check"></i> Resolve
                                        </button>
                                        @else
                                        <button class="btn btn-success btn-sm resolve-alert" data-id="{{ $alert['id'] }}">
                                            <i class="fas fa-check"></i> Resolve
                                        </button>
                                        @endif
                                        @else
                                        <button class="btn btn-warning btn-sm reopen-alert" data-id="{{ $alert['id'] }}">
                                            <i class="fas fa-undo"></i> Reopen
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
    </div>
</div>

@if($status == 'alerts')
<!-- Raise Alert Modal -->
<div class="modal fade" id="raiseAlertModal" tabindex="-1" aria-labelledby="raiseAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Existing Patient Form -->
            <form id="raiseAlertForm" style="display: none;">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="raiseAlertModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Raise New Alert (Existing Patient)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="patientSelect" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-user me-2"></i>Select Patient
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg" id="patientSearch" list="patientList"
                                placeholder="Type to search patients by name, number, or phone">
                            <button type="button" class="btn btn-outline-primary" id="newPatientBtn" style="display: none;">
                                <i class="fas fa-plus me-1"></i>New Patient
                            </button>
                        </div>
                        <datalist id="patientList">
                            @foreach($patients as $patient)
                            <option value="{{ $patient['text'] }}" data-id="{{ $patient['id'] }}">
                            @endforeach
                        </datalist>
                        <select class="form-select form-select-lg mt-2" id="patientSelect" name="appointment_id" required style="display: none;">
                            <option value="">Select a patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}">{{ $patient['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Search tip:</strong> Filter by name, patient number, or phone number. 
                            Select 'New Patient' if the patient is not listed in the database.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="urgent_message" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-comment-medical me-2"></i>Urgent Message
                        </label>
                        <textarea class="form-control form-control-lg" id="urgent_message" name="urgent_message" rows="4"
                            required placeholder="Describe the urgent medical situation or concern..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sender_name" class="form-label fw-bold text-dark mb-2">
                                    <i class="fas fa-user-md me-2"></i>Sender Name
                                </label>
                                <input type="text" class="form-control form-control-lg" id="sender_name" name="sender_name" 
                                    required placeholder="Enter your full name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sender_department" class="form-label fw-bold text-dark mb-2">
                                    <i class="fas fa-building me-2"></i>Department
                                </label>
                                <input type="text" class="form-control form-control-lg" id="sender_department" name="sender_department"
                                    required placeholder="Enter your department">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-paper-plane me-2"></i>Submit Alert
                    </button>
                </div>
            </form>
            
            <!-- New Patient Form -->
            <form id="raiseNewPatientAlertForm" style="display: none;">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="raiseNewPatientAlertModalLabel">
                        <button type="button" class="btn btn-outline-light btn-sm me-3" id="backToExistingPatient">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <i class="fas fa-user-plus me-2"></i>Raise New Alert (New Patient)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>New Patient:</strong> Enter details for a patient not currently in the database.
                    </div>
                    
                    <div class="mb-4">
                        <label for="patient_name" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-user me-2"></i>Patient Full Name
                        </label>
                        <input type="text" class="form-control form-control-lg" id="patient_name" name="patient_name" 
                            required placeholder="Enter patient's complete name">
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="patient_number" class="form-label fw-bold text-dark mb-2">
                                <i class="fas fa-id-card me-2"></i>Patient Number
                                <span class="text-muted fw-normal">(Optional)</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="patient_number" name="patient_number"
                                placeholder="Enter patient number if available">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-bold text-dark mb-2">
                                <i class="fas fa-phone me-2"></i>Phone Number
                            </label>
                            <input type="tel" class="form-control form-control-lg" id="phone" name="phone" required
                                placeholder="Enter phone number (10-15 digits)">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="new_urgent_message" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-comment-medical me-2"></i>Urgent Message
                        </label>
                        <textarea class="form-control form-control-lg" id="new_urgent_message" name="urgent_message" rows="4"
                            required placeholder="Describe the urgent medical situation or concern..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_sender_name" class="form-label fw-bold text-dark mb-2">
                                    <i class="fas fa-user-md me-2"></i>Sender Name
                                </label>
                                <input type="text" class="form-control form-control-lg" id="new_sender_name" name="sender_name" 
                                    required placeholder="Enter your full name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_sender_department" class="form-label fw-bold text-dark mb-2">
                                    <i class="fas fa-building me-2"></i>Department
                                </label>
                                <input type="text" class="form-control form-control-lg" id="new_sender_department" name="sender_department"
                                    required placeholder="Enter your department">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-paper-plane me-2"></i>Submit Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Feedback Modal for Resolving Alerts -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="feedbackForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="feedbackModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Resolve Alert - Add Feedback
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="feedback" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-comment-dots me-2"></i>Feedback/Resolution Notes
                        </label>
                        <textarea class="form-control form-control-lg" id="feedback" name="feedback" rows="4"
                            placeholder="Enter detailed notes about how this alert was resolved..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="recipient" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-user-tag me-2"></i>Recipient
                            <span class="text-muted fw-normal">(Optional)</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" id="recipient" name="recipient"
                            placeholder="Enter recipient name or department">
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Notification:</strong> Who should be notified about this resolution?
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-check me-2"></i>Resolve Alert
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Re-Resolve Modal for Reopened Alerts -->
<div class="modal fade" id="reResolveModal" tabindex="-1" aria-labelledby="reResolveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="reResolveForm">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="reResolveModalLabel">
                        <i class="fas fa-redo me-2"></i>Re-Resolve Alert - Update Feedback
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Previously Resolved:</strong> This alert was previously resolved. You can update the feedback below.
                    </div>
                    <div class="mb-4">
                        <label for="re_feedback" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-comment-dots me-2"></i>Updated Feedback/Resolution Notes
                        </label>
                        <textarea class="form-control form-control-lg" id="re_feedback" name="feedback" rows="4"
                            placeholder="Update details about how this alert was resolved..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="re_recipient" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-user-tag me-2"></i>Recipient
                            <span class="text-muted fw-normal">(Optional)</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" id="re_recipient" name="recipient"
                            placeholder="Enter recipient name or department">
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Notification:</strong> Who should be notified about this resolution?
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="fas fa-sync me-2"></i>Update & Resolve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Feedback Modal -->
<div class="modal fade" id="bulkFeedbackModal" tabindex="-1" aria-labelledby="bulkFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="bulkFeedbackForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bulkFeedbackModalLabel">
                        <i class="fas fa-tasks me-2"></i>Bulk Resolve Alerts - Add Feedback
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-primary mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong><span id="bulk-count">0</span> alerts</strong> will be resolved with this feedback.
                    </div>
                    <div class="mb-4">
                        <label for="bulk_feedback" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-comment-dots me-2"></i>Feedback/Resolution Notes
                        </label>
                        <textarea class="form-control form-control-lg" id="bulk_feedback" name="bulk_feedback" rows="4"
                            placeholder="Enter details about how these alerts were resolved..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="bulk_recipient" class="form-label fw-bold text-dark mb-2">
                            <i class="fas fa-user-tag me-2"></i>Recipient
                            <span class="text-muted fw-normal">(Optional)</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" id="bulk_recipient" name="bulk_recipient"
                            placeholder="Enter recipient name or department">
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Notification:</strong> Who should be notified about these resolutions?
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check-double me-2"></i>Resolve All Selected
                    </button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
    $(document).ready(function() {
        console.log('Document ready, initializing scripts');

        let currentAlertId = null;
        let selectedAlertIds = [];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Function to toggle bulk action buttons
        function toggleBulkActionButtons() {
            const checkedCount = $('.alert-checkbox:checked').length;
            console.log('Checked checkboxes:', checkedCount);
            $('#bulk-action-buttons').toggle(checkedCount > 0);
        }

        // Select/Deselect all checkboxes
        $('#selectAll').on('change', function() {
            $('.alert-checkbox').prop('checked', this.checked);
            toggleBulkActionButtons();
        });

        // Individual checkbox change
        $('.alert-checkbox').on('change', function() {
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            }
            toggleBulkActionButtons();
        });

        // Bulk resolve for active alerts - show feedback modal
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
        });

        // Handle bulk feedback form submission
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

        // Bulk reopen for resolved alerts
        $('#bulk-reopen').on('click', function(e) {
            e.preventDefault();
            let selected = $('.alert-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selected.length === 0) {
                alert('Please select at least one alert to reopen.');
                return;
            }

            if (confirm('Are you sure you want to reopen the selected alerts?')) {
                $.ajax({
                    url: '{{ route("alerts.bulkReopen") }}',
                    method: 'PATCH',
                    data: {
                        alert_ids: selected
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
        });

        @if($status == 'alerts')
        // Toggle between existing and new patient forms
        function showExistingPatientForm() {
            $('#raiseAlertForm').show();
            $('#raiseNewPatientAlertForm').hide();
            console.log('Switched to existing patient form');
        }

        function showNewPatientForm() {
            $('#raiseAlertForm').hide();
            $('#raiseNewPatientAlertForm').show();
            console.log('Switched to new patient form');
        }

        // Toggle New Patient Alert button visibility
        function toggleNewPatientButton() {
            const patientSelect = $('#patientSelect').val();
            $('#newPatientBtn').toggle(!patientSelect);
            console.log('New patient button visibility:', !patientSelect ? 'shown' : 'hidden');
        }

        // Sync patient search input with select
        $('#patientSearch').on('blur keyup', function(e) {
            if (e.type === 'blur' || e.key === 'Enter') {
                console.log('Patient search input triggered:', this.value);
                const searchValue = this.value.trim();
                const options = document.getElementById('patientList').options;
                const select = document.getElementById('patientSelect');

                let found = false;
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value.trim().toLowerCase() === searchValue.toLowerCase()) {
                        select.value = options[i].getAttribute('data-id');
                        console.log('Matched and selected patient ID:', select.value);
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    select.value = '';
                    console.log('No matching patient found, cleared select');
                }
                toggleNewPatientButton();
            }
        });

        // Clear search on select change and toggle button
        $('#patientSelect').on('change', function() {
            console.log('Patient select changed:', this.value);
            var patientSearch = document.getElementById('patientSearch');
            if (!this.value) {
                patientSearch.value = '';
                console.log('Cleared search input');
            }
            toggleNewPatientButton();
        });

        // Switch to new patient form
        $('#newPatientBtn').on('click', function() {
            showNewPatientForm();
        });

        // Switch back to existing patient form
        $('#backToExistingPatient').on('click', function() {
            showExistingPatientForm();
            $('#raiseAlertForm')[0].reset();
            $('#patientSearch').val('');
            $('#patientSelect').val('');
            toggleNewPatientButton();
        });

        // Handle existing patient alert form submission
        $('#raiseAlertForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log('Submitting existing patient alert form:', formData);

            $.post('{{ route("alerts.store") }}', formData)
                .done(function(response) {
                    console.log('Form submission success:', response);
                    $('#raiseAlertModal').modal('hide');
                    $('#raiseAlertForm')[0].reset();
                    $('#patientSearch').val('');
                    $('#patientSelect').val('');
                    window.location.href = '{{ route("booking.alerts") }}';
                })
                .fail(function(xhr) {
                    console.error('Form submission error:', xhr.responseJSON);
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

        // Handle new patient alert form submission
        $('#raiseNewPatientAlertForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log('Submitting new patient alert form:', formData);

            $.post('{{ route("alerts.storeNewPatient") }}', formData)
                .done(function(response) {
                    console.log('New patient alert submission success:', response);
                    $('#raiseAlertModal').modal('hide');
                    $('#raiseNewPatientAlertForm')[0].reset();
                    window.location.href = '{{ route("booking.alerts") }}';
                })
                .fail(function(xhr) {
                    console.error('New patient alert submission error:', xhr.responseJSON);
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

        // Handle feedback form submission
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

        // Handle re-resolve form submission
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

        // Initialize new patient button visibility on modal show
        $('#raiseAlertModal').on('show.bs.modal', function() {
            showExistingPatientForm();
            $('#raiseAlertForm')[0].reset();
            $('#patientSearch').val('');
            $('#patientSelect').val('');
            toggleNewPatientButton();
        });
        @endif

        // Initialize DataTable with proper configuration
        if ($.fn.DataTable.isDataTable('#alertsTable')) {
            $('#alertsTable').DataTable().destroy();
        }

        var table = $('#alertsTable').DataTable({
            processing: true,
            destroy: true,
            language: {
                emptyTable: @if($status == 'alerts')
                'No active alerts found'
                @else 'No resolved alerts found'
                @endif
            },
            columnDefs: [{
                targets: 0,
                orderable: false,
                searchable: false
            }],
            order: [],
            pageLength: 25,
            responsive: true
        });

        // Individual resolve alert - show feedback modal
        $('#alertsTable tbody').on('click', '.resolve-alert', function() {
            currentAlertId = $(this).data('id');
            $('#feedback').val('');
            $('#recipient').val('');
            $('#feedbackModal').modal('show');
        });

        // Individual re-resolve alert - show re-resolve modal with prefilled data
        $('#alertsTable tbody').on('click', '.re-resolve-alert', function() {
            currentAlertId = $(this).data('id');
            $.ajax({
                url: `/alerts/${currentAlertId}/details`,
                method: 'GET',
                success: function(response) {
                    console.log('Alert details fetched:', response);
                    $('#re_feedback').val(response.feedback || '');
                    $('#re_recipient').val(response.recipient || '');
                    $('#reResolveModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error fetching alert details:', xhr);
                    $('#re_feedback').val('');
                    $('#re_recipient').val('');
                    $('#reResolveModal').modal('show');
                }
            });
        });

        $('#alertsTable tbody').on('click', '.reopen-alert', function() {
            var alertId = $(this).data('id');
            if (confirm('Are you sure you want to reopen this alert?')) {
                $.ajax({
                    url: `/alerts/${alertId}/reopen`,
                    method: 'PATCH',
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