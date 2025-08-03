@extends('layouts.dashboard')

@section('title', 'Add Appointment')

@section('content')
<style>
    .input-with-icon {
        position: relative;
    }

    .input-with-icon input {
        padding-right: 2.5rem;
        /* space for icon */
    }

    .input-with-icon .add-icon {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        border: circular;
        background: none;
        font-size: 1.2rem;
        color: black;
        /* Bootstrap primary */
        cursor: pointer;
    }

    .input-with-icon .add-icon:hover {
        color: #0a58ca;
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="widget" style="background: #ffffff; border-radius: 15px; padding: 30px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);">
                <h2 class="text-center mb-4" style="font-family: 'Roboto', sans-serif; font-weight: 500; color: #333; font-size: 1.8rem;">
                    Book an Appointment
                </h2>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-family: 'Roboto', sans-serif; border-radius: 5px;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-family: 'Roboto', sans-serif; border-radius: 5px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {{-- No need for @elseif(session('error') && !session('suggested_dates')) here as the modal handles it --}}
                @endif

                <form role="form" method="POST" action="{{ route('booking.submitInternal') }}" id="appointmentForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6 col-12">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="name" class="form-control" placeholder="Full name"
                                value="{{ old('full_name') }}" required>
                            @error('full_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            <label for="PatientNumber" class="form-label">Patient Number <span class="text-danger">*</span></label>
                            <input type="text" name="patient_number" id="PatientNumber" class="form-control"
                                placeholder="Patient Number" value="{{ old('patient_number') }}" required>
                            @error('patient_number')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control"
                                placeholder="Email address" value="{{ old('email') }}">
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            <label for="phone" class="form-label">Phone Number <span
                                    class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <input type="text" name="phone" id="phone" class="form-control"
                                    placeholder="Enter Phone Number(s)" maxlength="50" value="{{ old('phone') }}"
                                    required>
                                <button type="button" class="add-icon" onclick="addSeparator()">+</button>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <label for="appointment_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}"
                                class="form-control" required>
                            @error('appointment_date')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            <label for="appointment_time" class="form-label">Time</label>
                            <input type="time" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}"
                                class="form-control">
                            @error('appointment_time')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            <label for="specialization" class="form-label">Specialization <span class="text-danger">*</span></label>
                            <select name="specialization" id="specialization" class="form-control select2-specialization" required>
                                <option value="">Select specialization</option>
                                @foreach($bk_specializations ?? [] as $specialization)
                                @if(is_object($specialization))
                                <option value="{{ $specialization->name }}" {{ old('specialization') == $specialization->name ? 'selected' : '' }}>
                                    {{ $specialization->name }}
                                </option>
                                @else
                                <option value="{{ $specialization }}" {{ old('specialization') == $specialization ? 'selected' : '' }}>
                                    {{ $specialization }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                            @error('specialization')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            @php
                            $userBranch = Auth::guard('booking')->user()->hospital_branch;
                            @endphp
                            <label for="doctor_name" class="form-label">Doctor's Name</label>
                            @if($userBranch === 'kijabe')
                            <input type="text" name="doctor_name" id="doctor_text_input" class="form-control"
                                placeholder="Enter Doctor's Name" value="{{ old('doctor_name') }}">
                            @else
                            <select name="doctor_name" id="doctor_select2" class="form-control select2-specialization">
                                <option value="">-- Select a Doctor --</option>
                                @foreach($doctors ?? [] as $doctor) {{-- Added null coalescing for $doctors --}}
                                <option value="{{ $doctor->doctor_name }}" {{ old('doctor_name') == $doctor->doctor_name ? 'selected' : '' }}>
                                    {{ $doctor->doctor_name }} - ({{ $doctor->department }})
                                </option>
                                @endforeach
                            </select>
                            @endif
                            @error('doctor_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            <label for="booking_type" class="form-label">Type of Booking <span class="text-danger">*</span></label>
                            <select class="form-control" name="booking_type" required>
                                <option value="" selected>Please Select Type of Booking</option>
                                <option value="new" {{ old('booking_type') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="review" {{ old('booking_type') == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="post_op" {{ old('booking_type') == 'post_op' ? 'selected' : '' }}>Post-Op</option>
                            </select>
                            @error('booking_type')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-12">
                            <label class="form-label d-block">Consent</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="consent[]" value="sms"
                                        id="consent_sms" {{ is_array(old('consent', [])) && in_array('sms', old('consent', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consent_sms">SMS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="consent[]" value="whatsapp"
                                        id="consent_whatsapp" {{ is_array(old('consent', [])) && in_array('whatsapp', old('consent', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consent_whatsapp">WhatsApp</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="consent[]" value="email"
                                        id="consent_email" {{ is_array(old('consent', [])) && in_array('email', old('consent', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consent_email">Email</label>
                                </div>
                            </div>
                            @error('consent')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="hospital_branch" id="hospital_branch_input"
                            value="{{ Auth::guard('booking')->check() ? Auth::guard('booking')->user()->hospital_branch : 'kijabe' }}">

                        <div class="col-lg-3 col-md-4 col-6 mx-auto mt-3">
                            <button type="submit" name="book_appointment_submit" id="submit-button"
                                style="background-color: #159ed5; width: 100%; border: none; padding: 12px; border-radius: 8px;">Book Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function addSeparator() {
        const input = document.getElementById('phone');
        if (input.value && !input.value.trim().endsWith('|')) {
            input.value += ' | ';
            input.focus();
        }
    }

    // Function to update the main form fields with data from old input (e.g., from session)
    function updateFormFields(formData) {
        for (const key in formData) {
            const inputElement = document.querySelector(`[name="${key}"]`);
            if (inputElement) {
                if (inputElement.type === 'checkbox' || inputElement.type === 'radio') {
                    if (Array.isArray(formData[key])) {
                        document.querySelectorAll(`[name="${key}"]`).forEach(checkbox => {
                            checkbox.checked = formData[key].includes(checkbox.value);
                        });
                    } else {
                        inputElement.checked = (formData[key] == inputElement.value);
                    }
                } else if (inputElement.tagName === 'SELECT') {
                    $(inputElement).val(formData[key]).trigger('change');
                } else {
                    inputElement.value = formData[key];
                }
            } else if (key === 'consent' && Array.isArray(formData[key])) {
                document.querySelectorAll('input[name="consent[]"]').forEach(checkbox => {
                    checkbox.checked = formData[key].includes(checkbox.value);
                });
            }
        }

        const hospitalBranch = document.getElementById('hospital_branch_input').value;
        if (hospitalBranch !== 'kijabe' && formData.doctor_name) {
            $('#doctor_select2').val(formData.doctor_name).trigger('change');
        }
    }

    $(document).ready(function() {
        $('.select2-specialization').select2({
            placeholder: "-- Select a Specialization --",
            allowClear: true,
            width: '100%'
        });

        // Check if suggested_dates are present in the session (from the controller redirect)
        @if(session('suggested_dates') && session('error') && session('modal_context') === 'booking')
            // Get the modal instance
            var suggestedDatesModal = new bootstrap.Modal(document.getElementById('suggestedDatesModal'));

            // Set the error message in the modal
            $('#modal-error-message').text("{{ session('error') }}");

            // Populate hidden fields in the modal's form with current old input data
            var oldInput = @json(session('_old_input'));
            var modalForm = $('#suggested-dates-form');
            modalForm.find('input[name^="form_data"]').remove(); // Clear previous hidden inputs

            for (var key in oldInput) {
                if (oldInput.hasOwnProperty(key)) {
                    if (Array.isArray(oldInput[key])) {
                        oldInput[key].forEach(function(subValue, subKey) {
                            modalForm.append('<input type="hidden" name="form_data[' + key + '][' + subKey + ']" value="' + subValue + '">');
                        });
                    } else {
                        modalForm.append('<input type="hidden" name="form_data[' + key + ']" value="' + oldInput[key] + '">');
                    }
                }
            }

            // Show the modal
            suggestedDatesModal.show();
        @endif

        // When the suggestedDatesModal is hidden (closed), clear relevant session data
        $('#suggestedDatesModal').on('hidden.bs.modal', function () {
            // Optionally, you might want to clear the form data if the user closes the modal without selecting a date.
            // For now, we'll rely on the server-side clearSuggestedDates route if a page refresh happens.
            // If you want to clear client-side without refresh, you'd need AJAX to hit a route.
            // Since you want to retain functionality with less JS, server-side clear on close is handled via the button.
            // The `window.location.href` in the modal's close button already triggers a redirect to clear session data.
        });
    });
</script>
@endsection