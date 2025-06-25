@extends('layouts.dashboard')

@section('title', 'Reminders')

@section('content')
    <div class="container-fluid px-0">
        <div class="widget shadow-sm mb-0 border-0 rounded-3">
            <div class="card-header text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top"
                style="background-color: #159ed5;">
                <h4 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-bell me-2"></i>Appointment Reminders
                </h4>
                <!-- Bulk Clear Button -->
                <button id="bulkClearBtn" class="btn btn-danger" disabled>
                    <i class="fas fa-check"></i> Clear Selected
                </button>
            </div>

            <div class="p-3">
                <form id="bulkClearForm" action="{{ route('booking.bulkClearReminders') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to clear the selected reminders? They will be removed from the reminder list.');">
                    @csrf
                    <!-- <input type="hidden" name="_method" value="DELETE"> -->
                    <div style="max-height: 500px; overflow-y: auto;">
                        @forelse ($upcoming->concat($missed) as $appt)
                                            @php
                                                // Determine color based on type or status
                                                $color = $appt->appointment_status === 'missed' ? '#dc3545' : match ($appt->booking_type) {
                                                    'new' => '#28a745',
                                                    'review' => '#007bff',
                                                    'postop' => '#ffc107',
                                                    'external_approved' => '#17a2b8',
                                                    default => '#17a2b8',
                                                };
                                                $textColor = $appt->booking_type === 'postop' ? '#000' : '#fff';
                                                $status = $appt->appointment_status === 'missed' ? 'Missed' : ($appt->appointment_status === 'honoured' ? 'Honoured' : 'Upcoming');
                                                $typeDisplay = match ($appt->booking_type) {
                                                    'new' => 'Initial Consultation',
                                                    'review' => 'Review',
                                                    'postop' => 'Post-Op',
                                                    'external_approved' => 'External',
                                                    default => ucfirst(str_replace('_', ' ', $appt->booking_type)),
                                                };
                                                $reminderSent = isset($appt->patient_notified) ? $appt->patient_notified : ($appt->appointment_status === 'approved');
                                            @endphp
                                            <div class="card mb-2 border-0 shadow-sm">
                                                <div class="card-body p-2 d-flex align-items-start">
                                                    <div class="me-2">
                                                        <input type="checkbox" class="form-check-input reminder-checkbox"
                                                            name="appointment_ids[]" value="{{ $appt->id }}-{{ $appt->booking_type }}">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <strong>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('l, M d, Y') }}</strong><br>
                                                                <span>{{ $appt->appointment_time ?? 'N/A' }}</span>
                                                                <span class="ms-2"
                                                                    style="color: {{ $textColor }}; background-color: {{ $color }}; padding: 2px 5px; border-radius: 3px;">
                                                                    {{ $status }}
                                                                </span>
                                                                <span class="ms-2"
                                                                    style="color: {{ $textColor }}; background-color: {{ $color }}; padding: 2px 5px; border-radius: 3px;">
                                                                    {{ $typeDisplay }}
                                                                </span>
                                                                <span class="ms-2">
                                                                    @if ($reminderSent)
                                                                        <i class="fas fa-check-circle text-success" title="Reminder Sent"></i>
                                                                        Reminder Sent
                                                                    @else
                                                                        <i class="fas fa-times-circle text-danger" title="Reminder Not Sent"></i>
                                                                        Reminder Not Sent
                                                                    @endif
                                                                </span>
                                                                <br>
                                                                <small class="d-flex flex-wrap align-items-center gap-2">
                                                                    <strong>{{ $appt->full_name ?? 'N/A' }}</strong>
                                                                    <span class="text-muted">•</span>
                                                                    <span>Patient Number: {{ $appt->patient_number ?? 'N/A' }}</span>
                                                                    <span class="text-muted">•</span>
                                                                    <span>Email: {{ $appt->email ?? 'N/A' }}</span>
                                                                    <span class="text-muted">•</span>
                                                                    <span>Phone: {{ $appt->phone ?? 'N/A' }}</span>
                                                                    <span class="text-muted">•</span>
                                                                    <span>Specialization: {{ $appt->specialization ?? 'N/A' }}</span>
                                                                    <span class="text-muted">•</span>
                                                                    <span>Doctor: {{ $appt->doctor_name ?? 'N/A' }}</span>
                                                                </small>
                                                            </div>
                                                            <div class="d-flex gap-1">
                                                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal"
                                                                    data-bs-target="#viewAppointmentModal{{ $appt->id }}" title="View/Edit">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <form
                                                                    action="{{ route('booking.clear', $appt->id . '-' . $appt->booking_type) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Are you sure you want to clear this reminder? It will be removed from the reminder list.');">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <input type="hidden" name="status" value="{{ $appt->booking_type }}">
                                                                    <button type="submit" class="btn btn-sm btn-light" title="Clear Reminder">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @include('booking.view', ['appointment' => $appt, 'status' => $appt->booking_type, 'specializations' => $specializations])
                        @empty
                            <div class="alert alert-info text-center">
                                No upcoming or missed appointments found.
                            </div>
                        @endforelse
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card-body {
            font-size: 0.9rem;
        }

        .btn-light {
            background-color: rgba(255, 255, 255, 0.9);
        }

        .btn-light:hover {
            background-color: #fff;
        }

        .text-muted {
            color: #6c757d !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reminderAlert = document.getElementById('reminderAlert');
            if (reminderAlert) {
                console.log('Reminder count on page load:', reminderAlert.textContent);
            }

            // Handle checkbox selection for bulk clear
            const checkboxes = document.querySelectorAll('.reminder-checkbox');
            const bulkClearBtn = document.getElementById('bulkClearBtn');
            const bulkClearForm = document.getElementById('bulkClearForm');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const checkedCount = document.querySelectorAll('.reminder-checkbox:checked').length;
                    bulkClearBtn.disabled = checkedCount === 0;
                });
            });

            bulkClearBtn.addEventListener('click', () => {
                if (confirm('Are you sure you want to clear the selected reminders? They will be removed from the reminder list.')) {
                    bulkClearForm.submit();
                }
            });
        });
    </script>
@endsection