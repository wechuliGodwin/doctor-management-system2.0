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
                <div class="widget"
                    style="background: #ffffff; border-radius: 15px; padding: 30px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);">
                    <h2 class="text-center mb-4"
                        style="font-family: 'Roboto', sans-serif; font-weight: 500; color: #333; font-size: 1.8rem;">
                        Book an Appointment
                    </h2>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert"
                            style="font-family: 'Roboto', sans-serif; border-radius: 5px;">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"
                            style="font-family: 'Roboto', sans-serif; border-radius: 5px;">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"
                            style="font-family: 'Roboto', sans-serif; border-radius: 5px;">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form role="form" method="post" action="{{ route('booking.submitInternal') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-6 col-12">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="name" class="form-control" placeholder="Full name"
                                    value="{{ old('full_name') }}" required>
                                <span id="nameError" class="error"></span>
                            </div>

                            <div class="col-lg-6 col-12">
                                <label for="PatientNumber" class="form-label">Patient Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="patient_number" id="PatientNumber" class="form-control"
                                    placeholder="Patient Number" value="{{ old('patient_number') }}" required>
                            </div>

                            <div class="col-lg-6 col-12">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control"
                                    placeholder="Email address" value="{{ old('email') }}">
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
                                <label for="appointment_date" class="form-label">Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="appointment_date" id="date" value="{{ old('appointment_date') }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-lg-6 col-12">
                                <label for="appointment_time" class="form-label">Time</label>
                                <input type="time" name="appointment_time" id="time" value="{{ old('appointment_time') }}"
                                    class="form-control">
                            </div>

                            <div class="col-lg-6 col-12">
                                <label for="specialization" class="form-label">Specialization <span
                                        class="text-danger">*</span></label>
                                <select name="specialization" id="specialization" class="form-control" required>
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
                            </div>

                            <div class="col-lg-6 col-12">
                                @php
                                    // Get the user's hospital branch
                                    $userBranch = Auth::guard('booking')->user()->hospital_branch;
                                @endphp
                                <label for="doctor" class="form-label">Doctor's Name</label>
                                @if($userBranch === 'kijabe')
                                    {{-- Render normal input field for Kijabe branch --}}
                                    <input type="text" name="doctor_name" id="doctor_text_input" class="form-control"
                                        placeholder="Enter Doctor's Name" value="{{ old('doctor_name') }}">
                                @else
                                    {{-- Render searchable select field for other branches --}}
                                    <select name="doctor_name" id="doctor_select2" class="form-control">
                                        <option value="">-- Select a Doctor --</option>
                                        @foreach($doctors as $doctor)
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
                                <label for="booking_type" class="form-label">Type of Booking <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" name="booking_type" required>
                                    <option value="" selected>Please Select Type of Booking</option>
                                    <option value="new" {{ old('booking_type') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="review" {{ old('booking_type') == 'Review' ? 'selected' : '' }}>Review
                                    </option>
                                    <option value="post_op" {{ old('booking_type') == 'Post-Op' ? 'selected' : '' }}>Post-Op
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-6 col-12">
                                <label class="form-label d-block">Consent</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consent[]" value="sms"
                                            id="consent_sms" {{ is_array(old('consent')) && in_array('sms', old('consent')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="consent_sms">SMS</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consent[]" value="whatsapp"
                                            id="consent_whatsapp" {{ is_array(old('consent')) && in_array('whatsapp', old('consent')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="consent_whatsapp">WhatsApp</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consent[]" value="email"
                                            id="consent_email" {{ is_array(old('consent')) && in_array('email', old('consent')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="consent_email">Email</label>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" name="hospital_branch"
                                value="{{ Auth::guard('booking')->check() ? Auth::guard('booking')->user()->hospital_branch : 'kijabe' }}">

                            <div class="col-lg-3 col-md-4 col-6 mx-auto mt-3">
                                <button type="submit" name="submit" id="submit-button"
                                    style="background-color: #159ed5; width: 100%;border: none;padding: 12px; border-radius: 8px;">Book
                                    Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#doctor_select2').select2({
                placeholder: "-- Select a Doctor --",
                allowClear: true,
                width: '100%'  // Ensure full-width styling
            });
        });
        function addSeparator() {
            const input = document.getElementById('phone');
            if (input.value && !input.value.trim().endsWith('|')) {
                input.value += ' | ';
                input.focus();
            }
        }
    </script>
@endsection