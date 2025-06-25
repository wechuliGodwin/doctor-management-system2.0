@extends('layouts.dashboard')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $title }}</h4>
                        @if($status == 'alerts')
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#raiseAlertModal">
                                Raise Alert
                            </button>
                        @endif
                    </div>
                    @if(count($alerts))
                        <div class="mb-3" id="bulk-action-buttons" style="display: none;">
                            @if($status == 'alerts')
                                <button type="button" class="btn btn-primary" id="bulk-resolve">Mark Selected as Resolved</button>
                            @else
                                <button type="button" class="btn btn-primary" id="bulk-reopen">Reopen Selected Alerts</button>
                            @endif
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive loaded" style="visibility: visible !important;">
                            <table id="alertsTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alerts as $alert)
                                        <tr>
                                            <td><input type="checkbox" class="alert-checkbox" value="{{ $alert['id'] }}"></td>
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
                                                        <span class="badge bg-warning">Reopened</span>
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
                                                        <button class="btn btn-warning btn-sm re-resolve-alert"
                                                            data-id="{{ $alert['id'] }}">Resolve</button>
                                                    @else
                                                        <button class="btn btn-success btn-sm resolve-alert"
                                                            data-id="{{ $alert['id'] }}">Resolve</button>
                                                    @endif
                                                @else
                                                    <button class="btn btn-warning btn-sm reopen-alert"
                                                        data-id="{{ $alert['id'] }}">Reopen</button>
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="raiseAlertForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="raiseAlertModalLabel">Raise New Alert</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="patientSelect" class="form-label">Select Patient</label>
                                <input type="text" class="form-control" id="patientSearch" list="patientList"
                                    placeholder="Type to search patients...">
                                <datalist id="patientList">
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient['text'] }}" data-id="{{ $patient['id'] }}">
                                    @endforeach
                                </datalist>
                                <select class="form-select" id="patientSelect" name="appointment_id" required
                                    style="display: none;">
                                    <option value="">Select a patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient['id'] }}">{{ $patient['text'] }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Filter by name, patient No. or phone No.</small>
                            </div>
                            <div class="mb-3">
                                <label for="urgent_message" class="form-label">Message</label>
                                <textarea class="form-control" id="urgent_message" name="urgent_message" rows="4"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="sender_name" class="form-label">Sender Name</label>
                                <input type="text" class="form-control" id="sender_name" name="sender_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="sender_department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="sender_department" name="sender_department"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit Alert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Feedback Modal for Resolving Alerts -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="feedbackForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="feedbackModalLabel">Resolve Alert - Add Feedback</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="feedback" class="form-label">Feedback/Resolution Notes</label>
                                <textarea class="form-control" id="feedback" name="feedback" rows="4"
                                    placeholder="Enter details about how this alert was resolved..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="recipient" class="form-label">Recipient (Optional)</label>
                                <input type="text" class="form-control" id="recipient" name="recipient"
                                    placeholder="Enter recipient name or department">
                                <small class="form-text text-muted">Who should be notified about this resolution?</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Resolve Alert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Re-Resolve Modal for Reopened Alerts -->
        <div class="modal fade" id="reResolveModal" tabindex="-1" aria-labelledby="reResolveModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="reResolveForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="reResolveModalLabel">Re-Resolve Alert - Update Feedback</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> This alert was previously resolved. You can update the
                                feedback below.
                            </div>
                            <div class="mb-3">
                                <label for="re_feedback" class="form-label">Updated Feedback/Resolution Notes</label>
                                <textarea class="form-control" id="re_feedback" name="feedback" rows="4"
                                    placeholder="Update details about how this alert was resolved..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="re_recipient" class="form-label">Recipient (Optional)</label>
                                <input type="text" class="form-control" id="re_recipient" name="recipient"
                                    placeholder="Enter recipient name or department">
                                <small class="form-text text-muted">Who should be notified about this resolution?</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Update & Resolve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Feedback Modal -->
        <div class="modal fade" id="bulkFeedbackModal" tabindex="-1" aria-labelledby="bulkFeedbackModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="bulkFeedbackForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="bulkFeedbackModalLabel">Bulk Resolve Alerts - Add Feedback</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <span id="bulk-count">0</span> alerts will be resolved with this feedback.
                            </div>
                            <div class="mb-3">
                                <label for="bulk_feedback" class="form-label">Feedback/Resolution Notes</label>
                                <textarea class="form-control" id="bulk_feedback" name="bulk_feedback" rows="4"
                                    placeholder="Enter details about how these alerts were resolved..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="bulk_recipient" class="form-label">Recipient (Optional)</label>
                                <input type="text" class="form-control" id="bulk_recipient" name="bulk_recipient"
                                    placeholder="Enter recipient name or department">
                                <small class="form-text text-muted">Who should be notified about these resolutions?</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Resolve All Selected</button>
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

    <script>
        $(document).ready(function () {
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
            $('#selectAll').on('change', function () {
                $('.alert-checkbox').prop('checked', this.checked);
                toggleBulkActionButtons();
            });

            // Individual checkbox change
            $('.alert-checkbox').on('change', function () {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                }
                toggleBulkActionButtons();
            });

            // Bulk resolve for active alerts - show feedback modal
            $('#bulk-resolve').on('click', function (e) {
                e.preventDefault();
                selectedAlertIds = $('.alert-checkbox:checked').map(function () {
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
            $('#bulkFeedbackForm').on('submit', function (e) {
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
                    success: function () {
                        $('#bulkFeedbackModal').modal('hide');
                        $('#bulkFeedbackForm')[0].reset();
                        // Redirect to resolved alerts page instead of reload
                        setTimeout(function () {
                            window.location.href = '{{ route("booking.resolved_alerts") }}';
                        }, 100);
                    },
                    error: function (xhr) {
                        let errMsg = 'Failed to resolve alerts.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errMsg = xhr.responseJSON.error;
                        }
                        alert(errMsg);
                    }
                });
            });

            // Bulk reopen for resolved alerts
            $('#bulk-reopen').on('click', function (e) {
                e.preventDefault();
                let selected = $('.alert-checkbox:checked').map(function () {
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
                        data: { alert_ids: selected },
                        success: function () {
                            // Redirect to active alerts page instead of reload
                            setTimeout(function () {
                                window.location.href = '{{ route("booking.alerts") }}';
                            }, 100);
                        },
                        error: function (xhr) {
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
                // Sync patient search input with select
                $('#patientSearch').on('blur keyup', function (e) {
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
                    }
                });

                // Clear search on select change
                $('#patientSelect').on('change', function () {
                    console.log('Patient select changed:', this.value);
                    var patientSearch = document.getElementById('patientSearch');
                    if (!this.value) {
                        patientSearch.value = '';
                        console.log('Cleared search input');
                    }
                });

                $('#raiseAlertForm').submit(function (e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    console.log('Submitting form:', formData);

                    $.post('{{ route("alerts.store") }}', formData)
                        .done(function (response) {
                            console.log('Form submission success:', response);
                            $('#raiseAlertModal').modal('hide');
                            $('#raiseAlertForm')[0].reset();
                            $('#patientSearch').val('');
                            $('#patientSelect').val('');
                            window.location.href = '{{ route("booking.alerts") }}';
                        })
                        .fail(function (xhr) {
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

                // Handle feedback form submission
                $('#feedbackForm').on('submit', function (e) {
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
                        success: function () {
                            $('#feedbackModal').modal('hide');
                            $('#feedbackForm')[0].reset();
                            setTimeout(function () {
                                window.location.href = '{{ route("booking.resolved_alerts") }}';
                            }, 100);
                        },
                        error: function (xhr) {
                            let errMsg = 'Failed to resolve alert.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errMsg = xhr.responseJSON.error;
                            }
                            alert(errMsg);
                        }
                    });
                });

                // Handle re-resolve form submission
                $('#reResolveForm').on('submit', function (e) {
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
                        success: function () {
                            $('#reResolveModal').modal('hide');
                            $('#reResolveForm')[0].reset();
                            // Stay on same page since we're re-resolving an already resolved alert
                            setTimeout(function () {
                                window.location.reload();
                            }, 100);
                        },
                        error: function (xhr) {
                            let errMsg = 'Failed to re-resolve alert.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errMsg = xhr.responseJSON.error;
                            }
                            alert(errMsg);
                        }
                    });
                });
            @endif

                        // FIXED DATATABLES INITIALIZATION - This fixes the column count error
                        // Destroy existing DataTable if it exists
                        if ($.fn.DataTable.isDataTable('#alertsTable')) {
                $('#alertsTable').DataTable().destroy();
            }

            // Initialize DataTable with proper configuration
            var table = $('#alertsTable').DataTable({
                processing: true,
                destroy: true, // Allow reinitialization
                language: {
                    emptyTable: @if($status == 'alerts') 'No active alerts found' @else'No resolved alerts found' @endif
                            },
                columnDefs: [
                    {
                        targets: 0, // First column (checkbox)
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [], // Disable initial sorting to avoid issues with checkbox column
                pageLength: 25, // Optional: set default page length
                responsive: true // Optional: make table responsive
            });

            // Individual resolve alert - show feedback modal
            $('#alertsTable tbody').on('click', '.resolve-alert', function () {
                currentAlertId = $(this).data('id');

                // Clear form and show regular feedback modal for new alerts
                $('#feedback').val('');
                $('#recipient').val('');
                $('#feedbackModal').modal('show');
            });

            // Individual re-resolve alert - show re-resolve modal with prefilled data
            $('#alertsTable tbody').on('click', '.re-resolve-alert', function () {
                currentAlertId = $(this).data('id');

                // Fetch existing alert details to pre-populate re-resolve form
                $.ajax({
                    url: `/alerts/${currentAlertId}/details`,
                    method: 'GET',
                    success: function (response) {
                        console.log('Alert details fetched:', response);
                        // Pre-populate form with existing feedback
                        $('#re_feedback').val(response.feedback || '');
                        $('#re_recipient').val(response.recipient || '');
                        $('#reResolveModal').modal('show');
                    },
                    error: function (xhr) {
                        console.error('Error fetching alert details:', xhr);
                        // Still show modal with empty form if fetch fails
                        $('#re_feedback').val('');
                        $('#re_recipient').val('');
                        $('#reResolveModal').modal('show');
                    }
                });
            });

            $('#alertsTable tbody').on('click', '.reopen-alert', function () {
                var alertId = $(this).data('id');
                if (confirm('Are you sure you want to reopen this alert?')) {
                    $.ajax({
                        url: `/alerts/${alertId}/reopen`,
                        method: 'PATCH',
                        success: function () {
                            setTimeout(function () {
                                window.location.href = '{{ route("booking.alerts") }}';
                            }, 100);
                        },
                        error: function (xhr) {
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