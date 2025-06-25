<!-- resources/views/booking/view.blade.php -->
<div class="modal fade" id="viewAppointmentModal{{ $appointment->id }}" tabindex="-1"
    aria-labelledby="viewAppointmentModalLabel{{ $appointment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
            <div class="modal-header" style="background-color: #159ed5; color: white;">
                <span class="modal-title" id="viewAppointmentModalLabel{{ $appointment->id }}" style="font-size: 16px;">
                    View Appointment:  {{ $appointment->full_name ?? $appointment->name ?? 'N/A' }}
                </span>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card compact-card">
                    <div class="card-body">
                        <span class="card-title" style="font-size: 14px; margin-bottom: 0.5rem;">Appointment Details</span>
                        <form method="POST" action="{{ route('booking.update', $appointment->id) }}"
                            id="update-form-{{ $appointment->id }}" class="appointment-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="all_details">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <input type="hidden" name="source_table" value="{{ $appointment->source_table ?? $status }}">
                            <input type="hidden" name="branch" value="{{ $branch ?? ($appointment->hospital_branch ?? '') }}">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="full_name_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Patient Name</label>
                                    <input type="text" class="form-control" id="full_name_{{ $appointment->id }}"
                                        name="full_name"
                                        value="{{ $appointment->full_name ?? $appointment->name ?? '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="patient_number_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Patient Number</label>
                                    <input type="text" class="form-control" id="patient_number_{{ $appointment->id }}"
                                        name="patient_number" value="{{ $appointment->patient_number ?? '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="email_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Email</label>
                                    <input type="email" class="form-control" id="email_{{ $appointment->id }}"
                                        name="email" value="{{ $appointment->email ?? '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="phone_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Phone</label>
                                    <input type="text" class="form-control" id="phone_{{ $appointment->id }}"
                                        name="phone" value="{{ $appointment->phone ?? '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="appointment_date_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Appointment Date</label>
                                    <input type="date" class="form-control" id="appointment_date_{{ $appointment->id }}"
                                        name="appointment_date"
                                        value="{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" readonly>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="appointment_time_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Appointment Time</label>
                                    <input type="time" class="form-control" id="appointment_time_{{ $appointment->id }}"
                                        name="appointment_time"
                                        value="{{ $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') : '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" readonly>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="specialization_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Specialization</label>
                                    <select class="form-control form-select" id="specialization_{{ $appointment->id }}"
                                        name="specialization_disabled"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" disabled>
                                        <option value="">Select Specialization</option>
                                        @foreach($specializations as $specialization)
                                            <option value="{{ $specialization->name }}"
                                                {{ ($appointment->specialization ?? '') === $specialization->name ? 'selected' : '' }}>
                                                {{ $specialization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- Hidden field to retain selected value for form submission -->
                                    <input type="hidden" name="specialization" value="{{ $appointment->specialization }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="doctor_name_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Doctor</label>
                                    <input type="text" class="form-control" id="doctor_name_{{ $appointment->id }}"
                                        name="doctor_name"
                                        value="{{ $appointment->doctor_name ?? $appointment->doctor ?? '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="appointment_status_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Status</label>
                                    <select class="form-control form-select" id="appointment_status_{{ $appointment->id }}"
                                        name="appointment_status"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                        <option value="pending"
                                            {{ ($appointment->appointment_status ?? 'pending') === 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="honoured"
                                            {{ ($appointment->appointment_status ?? 'pending') === 'honoured' ? 'selected' : '' }}>
                                            Honoured</option>
                                        <option value="missed"
                                            {{ ($appointment->appointment_status ?? 'pending') === 'missed' ? 'selected' : '' }}>
                                            Missed</option>
                                        <option value="late"
                                            {{ ($appointment->appointment_status ?? 'pending') === 'late' ? 'selected' : '' }}>
                                            Late</option>
                                        <option value="cancelled"
                                            {{ ($appointment->appointment_status ?? 'pending') === 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="visit_date_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Visit Date</label>
                                    <input type="date" class="form-control" id="visit_date_{{ $appointment->id }}"
                                        name="visit_date"
                                        value="{{ $appointment->visit_date ? \Carbon\Carbon::parse($appointment->visit_date)->format('Y-m-d') : '' }}"
                                        style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" >
                                </div>
                                
                                @if(in_array($status === 'all' ? $appointment->source_table : $status, ['new', 'review', 'postop']))
                                    <div class="col-md-4 mb-2">
                                        <label for="booking_type_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Booking Type</label>
                                        <select class="form-control form-select" id="booking_type_{{ $appointment->id }}"
                                            name="booking_type"
                                            style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required>
                                            <option value="new"
                                                {{ ($appointment->booking_type === 'new' ? 'selected' : '' )}}>
                                                New</option>
                                            <option value="review"
                                                {{ ($appointment->booking_type  === 'review' ? 'selected' : '') }}>
                                                Review</option>
                                            <option value="post_op"
                                                {{ ($appointment->booking_type === 'post_op' ? 'selected' : '') }}>
                                                Post-Op</option>
                                        </select>
                                    </div>

                                    
                                            
                                
                                @endif
                                @if(($status === 'all' ? $appointment->source_table : $status) === 'external_approved')
                                    @if(isset($appointment->booking_id) || isset($appointment->appointment_number))
                                        <div class="col-md-4 mb-2">
                                            <label for="booking_id_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Booking ID</label>
                                            <input type="text" class="form-control" id="booking_id_{{ $appointment->id }}"
                                                name="booking_id"
                                                value="{{ $appointment->booking_id ?? $appointment->appointment_number ?? '' }}"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                        </div>
                                    @endif
                                    @if(isset($appointment->patient_notified))
                                        <div class="col-md-4 mb-2">
                                            <label for="patient_notified_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Notified</label>
                                            <select class="form-control form-select" id="patient_notified_{{ $appointment->id }}"
                                                name="patient_notified"
                                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                                <option value="0"
                                                    {{ ($appointment->patient_notified ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                                <option value="1"
                                                    {{ ($appointment->patient_notified ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                            </select>
                                        </div>
                                    @endif
                                @endif
                                @if(($status === 'all' ? $appointment->source_table : $status) === 'external_pending')
                                    <div class="col-md-4 mb-2">
                                        <label for="status_field_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Pending Status</label>
                                        <select class="form-control form-select" id="status_field_{{ $appointment->id }}"
                                            name="status_field"
                                            style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">
                                            <option value="pending"
                                                {{ ($appointment->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved"
                                                {{ ($appointment->status ?? 'pending') === 'approved' ? 'selected' : '' }}>Approved</option>
                                            </select>
                                        </div> 
                                @endif
                                @if(in_array($status === 'all' ? $appointment->source_table : $status, ['external_pending', 'external_approved', 'cancelled']))
                                    <div class="col-md-6 mb-2">
                                        <label for="notes_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Notes</label>
                                        <textarea class="form-control" id="notes_{{ $appointment->id }}" name="notes"
                                            rows="2" style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">{{ $appointment->notes ?? '' }}</textarea>
                                    </div>
                                @endif
                                @if(isset($appointment->doctor_comments))
                                    <div class="col-md-6 mb-2">
                                        <label for="doctor_comments_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Doctor Comments</label>
                                        <textarea class="form-control" id="doctor_comments_{{ $appointment->id }}"
                                            name="doctor_comments" rows="2"
                                            style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;">{{ $appointment->doctor_comments ?? '' }}</textarea>
                                    </div>
                                @endif
                                @if(($status === 'all' ? $appointment->source_table : $status) === 'cancelled' || ($appointment->appointment_status ?? '') === 'cancelled')
                                    <div class="col-md-12 mb-2">
                                        <label for="cancellation_reason_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Cancellation Reason</label>
                                        <textarea class="form-control" id="cancellation_reason_{{ $appointment->id }}"
                                            name="cancellation_reason" rows="2"
                                            style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" {{ ($appointment->appointment_status ?? '') === 'cancelled' ? 'required' : '' }}>{{ $appointment->cancellation_reason ?? '' }}</textarea>
                                    </div>
                                @endif
                            </div>
                            @if ($errors->hasAny(['full_name', 'phone', 'appointment_date', 'specialization', 'appointment_status', 'booking_type', 'cancellation_reason', 'hospital_branch']))
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" style="background-color: #6c757d;" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                <button type="submit" form="update-form-{{ $appointment->id }}" class="btn btn-sm" style="background-color: #5bbbe1;"><i
                        class="fas fa-save"></i> Save</button>
                @if(($status === 'all' ? $appointment->source_table : $status) === 'cancelled')
                    <form action="{{ route('booking.reapprove', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) }}" method="POST"
                        style="display: inline-block;" class="appointment-form"
                        onsubmit="return confirm('Are you sure you want to reapprove this appointment? It will be moved back to its original status.');">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="status" value="{{ $status === 'all' ? $appointment->source_table : $status }}">
                        <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-check"></i> Reapprove</button>
                    </form>
                @endif
                @if(Route::is('booking.reminders') && ($status !== 'cancelled' && ($status !== 'all' || $appointment->source_table !== 'cancelled')) && $appointment->appointment_status !== 'cancelled')
                    <form action="{{ route('booking.clear', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) }}" method="POST"
                        style="display: inline-block;" class="appointment-form"
                        onsubmit="return confirm('Are you sure you want to clear this reminder? It will be removed from the reminder list.');">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="status" value="{{ $status === 'all' ? $appointment->source_table : $status }}">
                        <button type="submit" class="btn btn-sm" style="background-color: #f0ad4e;"><i class="fas fa-check"></i> Clear</button>
                    </form>
                @endif
                <form action="{{ route('booking.delete', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) }}" method="POST"
                    style="display: inline-block;" class="appointment-form"
                    onsubmit="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="status" value="{{ $status === 'all' ? $appointment->source_table : $status }}">
                    <button type="submit" class="btn btn-sm" style="background-color: #dc3545;"><i class="fas fa-trash-alt"></i> Delete</button>
                </form>
                <div>
                    @if(!Route::is('booking.reminders') && ($status !== 'cancelled' && ($status !== 'all' || $appointment->source_table !== 'cancelled')) && $appointment->appointment_status !== 'cancelled')
                        <button type="button" class="btn btn-sm" style="background-color: #6c757d;" data-bs-toggle="modal"
                            data-bs-target="#cancelAppointmentModal{{ $appointment->id }}"><i class="fas fa-ban"></i> Cancel</button>
                    @endif
                </div>
                <div>
                    @if($status !== 'cancelled')
                            <button type="button" class="btn btn-sm btn-info openRescheduleModal"
                                style="background-color: #6c757d;" 
                                data-bs-toggle="modal" data-bs-target="#rescheduleAppointmentModal"
                                data-action="{{ route('booking.reschedule', [$appointment->id, 'all']) }}"
                                data-id="{{ $appointment->id }}"
                                data-full_name="{{ $appointment->full_name }}"
                                data-patient_number="{{ $appointment->patient_number }}"
                                data-email="{{ $appointment->email }}"
                                data-phone="{{ $appointment->phone }}"
                                data-appointment_date="{{ $appointment->appointment_date }}"
                                data-appointment_time="{{ $appointment->appointment_time }}"
                                data-specialization="{{ $appointment->specialization }}"
                                data-doctor_name="{{ $appointment->doctor }}"
                                data-booking_type="{{ $appointment->booking_type }}">
                                <i class="fas fa-calendar-alt"></i>
                                Reschedule
                            </button>
                            

                    @endif
                </div>
                
                
            </div>
        </div>
    </div>
</div>

@if($status !== 'cancelled' && ($status !== 'all' || $appointment->source_table !== 'cancelled') && $appointment->appointment_status !== 'cancelled')
    <div class="modal fade" id="cancelAppointmentModal{{ $appointment->id }}" tabindex="-1"
        aria-labelledby="cancelAppointmentModalLabel{{ $appointment->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
                <div class="modal-header" style="background-color: #159ed5; color: white;">
                    <span class="modal-title" id="cancelAppointmentModalLabel{{ $appointment->id }}" style="font-size: 16px;">Cancel Appointment</span>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('booking.cancel', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) }}"
                    class="appointment-form">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="status" value="{{ $status === 'all' ? $appointment->source_table : $status }}">
                    <input type="hidden" name="branch" value="{{ $branch ?? ($appointment->hospital_branch ?? '') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: normal;">Patient Name</label>
                            <input type="text" class="form-control"
                                value="{{ $appointment->full_name ?? $appointment->name ?? 'N/A' }}"
                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: normal;">Patient Number</label>
                            <input type="text" class="form-control" value="{{ $appointment->patient_number ?? 'N/A' }}"
                                style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="cancellation_reason_{{ $appointment->id }}" class="form-label" style="font-weight: normal;">Cancellation Reason</label>
                            <textarea class="form-control" id="cancellation_reason_{{ $appointment->id }}"
                                name="cancellation_reason" rows="3"
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
@endif