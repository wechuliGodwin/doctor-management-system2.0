<!-- Single Shared Reschedule Modal -->
<div class="modal fade" id="rescheduleAppointmentModal" tabindex="-1"
    aria-labelledby="rescheduleAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
            <div class="modal-header" style="background-color: #159ed5; color: white;">
                <span class="modal-title" id="rescheduleAppointmentModalLabel" style="font-size: 16px;">Reschedule Appointment</span>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="rescheduleForm" class="appointment-form">
                @csrf
                @method('POST')
                <div class="row p-3">
                    <input type="hidden" name="appointment_id" id="modal_appointment_id">
                    
                    <div class="col-md-4 mb-2">
                        <label for="modal_full_name" class="form-label">Patient Name</label>
                        <input type="text" class="form-control" id="modal_full_name" name="full_name" >
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_patient_number" class="form-label">Patient Number</label>
                        <input type="text" class="form-control" id="modal_patient_number" name="patient_number">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="modal_email" name="email">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="modal_phone" name="phone" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_appointment_date" class="form-label">Appointment Date</label>
                        <input type="date" class="form-control" id="modal_appointment_date" name="appointment_date" >
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_appointment_time" class="form-label">Appointment Time</label>
                        <input type="time" class="form-control" id="modal_appointment_time" name="appointment_time" >
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_specialization" class="form-label">Specialization</label>
                        <label for="modal_specialization" class="form-label" style="font-weight: normal;">Specialization</label>
                        <select class="form-control form-select" id="modal_specialization"
                            name="specialization"
                            style="font-weight: normal; font-family: Arial, sans-serif; font-size: 14px;" required>
                            <option value="">Select Specialization</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->name }}">
                                    {{ $specialization->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_doctor_name" class="form-label">Doctor</label>
                        <input type="text" class="form-control" id="modal_doctor_name" name="doctor_name">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="modal_booking_type" class="form-label">Booking Type</label>
                        <select class="form-control" id="modal_booking_type" name="booking_type" required>
                            <option value="new">New</option>
                            <option value="review">Review</option>
                            <option value="post_op">Post-Op</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="modal_reason" class="form-label">Reschedule Reason</label>
                        <textarea class="form-control" id="modal_reason" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
