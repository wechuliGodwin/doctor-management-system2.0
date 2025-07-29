<div class="modal fade" id="rescheduleAppointmentModal" tabindex="-1"
    aria-labelledby="rescheduleAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="font-family: Arial, sans-serif; font-size: 14px;">
            <div class="modal-header" style="background-color: #159ed5; color: white;">
                <h5 class="modal-title" id="rescheduleAppointmentModalLabel">Reschedule Appointment</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="general-reschedule-error" class="alert alert-danger d-none" role="alert"></div>

                <form method="POST" id="rescheduleForm" class="appointment-form">
                    @csrf
                    @method('POST')
                    <div class="row g-3">
                        <input type="hidden" name="appointment_id" id="modal_appointment_id">
                        <div class="col-md-4 mb-2">
                            <label for="modal_full_name" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="modal_full_name" name="full_name" readonly>
                            @error('full_name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_patient_number" class="form-label">Patient Number</label>
                            <input type="text" class="form-control" id="modal_patient_number" name="patient_number" readonly>
                            @error('patient_number') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="modal_email" name="email" readonly>
                            @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="modal_phone" name="phone" required readonly>
                            @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_appointment_date" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" id="modal_appointment_date" name="appointment_date" required>
                            <div class="text-danger mt-1" id="appointment_date-error-message" style="display: none;"></div>
                            @error('appointment_date') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_appointment_time" class="form-label">Appointment Time</label>
                            <input type="time" class="form-control" id="modal_appointment_time" name="appointment_time">
                            @error('appointment_time') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_specialization" class="form-label">Specialization</label>
                            <select class="form-control form-select select2-specialization" id="modal_specialization"
                                name="specialization" required>
                                <option value="">Select Specialization</option>
                                @foreach($specializations as $specialization)
                                <option value="{{ $specialization->name }}">{{ $specialization->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger mt-1" id="specialization-error-message" style="display: none;"></div>
                            @error('specialization') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_doctor_name" class="form-label">Doctor</label>
                            <input type="text" class="form-control" id="modal_doctor_name" name="doctor_name" readonly>
                            @error('doctor_name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_booking_type" class="form-label">Booking Type</label>
                            <select class="form-control" id="modal_booking_type" name="booking_type" required>
                                <option value="new">New</option>
                                <option value="review">Review</option>
                                <option value="post_op">Post-Op</option>
                            </select>
                            @error('booking_type') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="modal_reason" class="form-label">Reschedule Reason</label>
                            <textarea class="form-control" id="modal_reason" name="reason" rows="3" required></textarea>
                            <div class="text-danger mt-1" id="reason-error-message" style="display: none;"></div>
                            @error('reason') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="modal_hospital_branch" class="form-label">Hospital Branch</label>
                            <select class="form-control" id="modal_hospital_branch" name="hospital_branch" required>
                                <option value="kijabe">Kijabe</option>
                                <option value="westlands">Westlands</option>
                                <option value="naivasha">Naivasha</option>
                                <option value="marira">Marira</option>
                            </select>
                            <div class="text-danger mt-1" id="hospital_branch-error-message" style="display: none;"></div>
                            @error('hospital_branch') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-tertiary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Reschedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('booking.suggested_dates_modal')

<style>
    .modal-content {
        border-radius: 12px;
        border: none;
        font-family: 'Inter', sans-serif;
    }

    .modal-header {
        background: linear-gradient(90deg, #159ed5, #0d6a9f);
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .modal-title {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-body .alert {
        font-size: 0.9rem;
        padding: 0.75rem;
        border-radius: 6px;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
    }

    .modal-body .text-muted {
        font-size: 0.9rem;
    }

    .list-group-item {
        cursor: pointer;
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
        border: 1px solid #d1e9f5;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .list-group-item:hover {
        background-color: #e6f4fa;
        transform: translateY(-1px);
    }

    .list-group-item:active {
        background-color: #d1e9f5;
    }

    .modal-footer {
        border-top: none;
        padding: 1rem 1.5rem;
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
</style>

<script src="https://cdn.jsdelivr.net/npm/@select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-specialization').select2({
            placeholder: "Select or type to filter specialization",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#rescheduleAppointmentModal')
        });

        $('#rescheduleAppointmentModal').on('shown.bs.modal', function() {
            $('.is-invalid').removeClass('is-invalid');
            $('.text-danger').text('').hide();
            $('#general-reschedule-error').text('').addClass('d-none');
        });

        $('#rescheduleForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();
            const appointmentId = $('#modal_appointment_id').val();
            const url = `/appointments/reschedule/${appointmentId}`;

            $('.is-invalid').removeClass('is-invalid');
            $('.text-danger').text('').hide();
            $('#general-reschedule-error').text('').addClass('d-none');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#rescheduleAppointmentModal').modal('hide');
                        toastr.success(response.success);
                        setTimeout(() => {
                            window.location.href = response.redirect || '/dashboard';
                        }, 500);
                    }
                },
                error: function(xhr) {
                    const responseJson = xhr.responseJSON;

                    if (responseJson && responseJson.errors) {
                        $.each(responseJson.errors, function(key, value) {
                            $(`#modal_${key}`).addClass('is-invalid');
                            const errorDiv = $(`#${key}-error-message`);
                            if (errorDiv.length) {
                                errorDiv.text(value[0]).show();
                            } else {
                                $(`#modal_${key}`).after(`<div class="text-danger mt-1">${value[0]}</div>`);
                            }
                        });
                        toastr.error('Please correct the errors in the form.');
                    } else if (responseJson && responseJson.error && responseJson.action === 'show_suggested_dates_modal') {
                        $('#suggested-dates-error-message').text(responseJson.error);
                        const suggestedDatesList = $('#suggested-dates-list');
                        suggestedDatesList.empty();

                        if (responseJson.suggested_dates && responseJson.suggested_dates.length > 0) {
                            $.each(responseJson.suggested_dates, function(index, suggestion) {
                                suggestedDatesList.append(`
                                    <tr>
                                        <td>${new Date(suggestion.date + 'T00:00:00').toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' })}</td>
                                        <td>${new Date(suggestion.date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'long' })}</td>
                                        <td>${suggestion.limit}</td>
                                        <td>${suggestion.bookings}</td>
                                        <td><button class="btn btn-sm btn-primary select-date-btn" data-date="${suggestion.date}" onclick="selectSuggestedDate('${suggestion.date}');">Select</button></td>
                                    </tr>
                                `);
                            });
                        } else {
                            suggestedDatesList.append('<tr><td colspan="5" class="text-center">No alternative dates available.</td></tr>');
                        }

                        $('#rescheduleAppointmentModal').modal('hide');
                        $('#suggestedDatesModal').modal('show');
                    } else if (responseJson && responseJson.error) {
                        $('#general-reschedule-error').text(responseJson.error).removeClass('d-none');
                        toastr.error(responseJson.error);
                    } else {
                        $('#general-reschedule-error').text('An unexpected error occurred. Please try again.').removeClass('d-none');
                        toastr.error('An unexpected error occurred. Please try again.');
                    }
                }
            });
        });

        @if(session('show_suggested_dates_modal_on_load'))
        $(window).on('load', function() {
            const suggestedDates = @json(session('suggested_dates'));
            const errorMessage = @json(session('error'));
            const appointmentId = @json(session('appointment_id'));
            const oldInput = @json(session() -> getOldInput());

            if (appointmentId) {
                $('#modal_appointment_id').val(appointmentId);
                $.each(oldInput, function(key, value) {
                    let inputElement = $(`#modal_${key}`);
                    if (inputElement.is('select.select2-specialization')) {
                        inputElement.val(value).trigger('change');
                    } else if (inputElement.is('select')) {
                        inputElement.val(value);
                    } else if (inputElement.is(':checkbox')) {
                        inputElement.prop('checked', value == 1);
                    } else if (inputElement.is(':radio')) {
                        $(`input[name="${key}"][value="${value}"]`).prop('checked', true);
                    } else {
                        inputElement.val(value);
                    }
                });
            }

            if (errorMessage) {
                $('#general-reschedule-error').text(errorMessage).removeClass('d-none');
                toastr.error(errorMessage);
                $('#suggested-dates-error-message').text(errorMessage);
                $('#modal_appointment_date').addClass('is-invalid');
            }

            const rescheduleModal = new bootstrap.Modal(document.getElementById('rescheduleAppointmentModal'));
            rescheduleModal.show();
        });
        @endif
    });
</script>