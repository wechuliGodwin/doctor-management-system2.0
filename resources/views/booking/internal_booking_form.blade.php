<!doctype html>
<html lang="en">

<head>
    <title>Doctor Appointment Management System || Home Page</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            padding-top: 80px;
            /* Adjusted for fixed navbar */
            margin: 0;
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(90deg, #159ed5 0%, #1278a8 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-brand img {
            margin-right: 10px;
            border-radius: 5px;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            padding: 8px 15px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            color: #e0e0e0 !important;
        }

        .navbar-toggler {
            border-color: #ffffff;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Booking Section Styling */
        .section-padding {
            padding: 60px 0;
        }

        .booking-form {
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .booking-form h2 {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .booking-form .alert {
            font-family: 'Roboto', sans-serif;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .booking-form .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .booking-form .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .booking-form .form-label {
            font-family: 'Roboto', sans-serif;
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .booking-form input,
        .booking-form select {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            width: 100%;
            font-family: 'Roboto', sans-serif;
            font-size: 0.95rem;
            color: #333;
            transition: border-color 0.3s ease;
        }

        .booking-form input:focus,
        .booking-form select:focus {
            border-color: #159ed5;
            box-shadow: 0 0 5px rgba(21, 158, 213, 0.2);
            outline: none;
        }

        .booking-form .row {
            row-gap: 15px;
            /* Adds spacing between rows */
        }

        .booking-form button {
            background-color: #159ed5;
            border: none;
            color: #ffffff;
            padding: 12px;
            border-radius: 5px;
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .booking-form button:hover {
            background-color: #1278a8;
        }

        .booking-form p {
            font-family: 'Roboto', sans-serif;
            color: #555;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 15px;
        }

        .booking-form span.error {
            color: red;
            font-size: 0.85rem;
            display: block;
            margin-top: 5px;
        }

        /* Footer Styling */
        .site-footer {
            background-color: #ffffff;
            padding: 40px 0;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.08);
            margin-top: 40px;
        }

        .site-footer h5 {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .site-footer p,
        .site-footer .list-group-item {
            font-family: 'Roboto', sans-serif;
            color: #555;
            font-size: 0.95rem;
        }

        .site-footer .list-group-item {
            background: none;
            border: none;
            padding: 5px 0;
        }

        .site-footer .social-icon {
            display: flex;
            gap: 15px;
        }

        .site-footer .social-icon-link {
            color: #159ed5;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .site-footer .social-icon-link:hover {
            color: #1278a8;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand img {
                width: 80px;
                height: 40px;
            }

            .navbar-nav .nav-link {
                padding: 10px;
                font-size: 0.95rem;
            }

            .booking-form {
                padding: 20px;
            }

            .booking-form h2 {
                font-size: 1.5rem;
            }

            .site-footer {
                padding: 30px 0;
            }

            .site-footer h5 {
                font-size: 1.1rem;
            }

            .site-footer p,
            .site-footer .list-group-item {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body id="top">
    <main>
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <a class="navbar-brand d-none d-lg-block" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="100" height="50">
                    <span>DMS System</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">Nairobi Clinic</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Check Appointment</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('faq') }}">KH FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#booking">Booking</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('booking.dashboard') }}">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="section-padding" id="booking">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-12 mx-auto">
                        <div class="booking-form">
                            <h2 class="text-center">Book an Appointment</h2>

                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                                        <label for="name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="full_name" id="name" class="form-control"
                                            placeholder="Full name" value="{{ old('full_name') }}" required>
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
                                        <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*"
                                            class="form-control" placeholder="Email address" value="{{ old('email') }}">
                                    </div>

                                    <div class="col-lg-6 col-12">
                                        <label for="phone" class="form-label">Phone Number <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" name="phone" id="phone" class="form-control"
                                            placeholder="Enter Phone Number" maxlength="10" value="{{ old('phone') }}" required>
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
                                                {{ $specialization->specialization_name }}
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
                                        <label for="doctor" class="form-label">Doctor's Name</label>
                                        <input type="text" name="doctor_name" id="doctor" class="form-control"
                                            placeholder="Enter Doctor's Name" value="{{ old('doctor_name') }}">
                                    </div>

                                    <div class="col-12">
                                        <label for="booking_type" class="form-label">Type of Booking <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="booking_type" required>
                                            <option value="" selected>Please Select Type of Booking</option>
                                            <option value="new" {{ old('booking_type') == 'New' ? 'selected' : '' }}>New</option>
                                            <option value="review" {{ old('booking_type') == 'Review' ? 'selected' : '' }}>Review</option>
                                            <option value="post_op" {{ old('booking_type') == 'Post-Op' ? 'selected' : '' }}>Post-Op</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="hospital_branch" value="kijabe">

                                    <div class="col-lg-3 col-md-4 col-6 mx-auto mt-3">
                                        <button type="submit" name="submit" id="submit-button">Book Now</button>
                                    </div>
                                </div>
                            </form>

                            <p><strong>DMS v1.7 © Kijabe Devops</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer section-padding" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 me-auto col-12">
                    <h5 class="mb-lg-4 mb-3">Email</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex">info@kijabehospital.org</li>
                        <br>
                        <h5 class="mb-lg-4 mb-3">Contact Number</h5>
                        <li class="list-group-item d-flex">7896541239</li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 col-12 my-4 my-lg-0">
                    <h5 class="mb-lg-4 mb-3">Our Clinics</h5>
                    <p>20, Kijabe, Kiambu County</p>
                </div>

                <div class="col-lg-3 col-md-6 col-12 ms-auto">
                    <h5 class="mb-lg-4 mb-2">Socials</h5>
                    <ul class="social-icon">
                        <li><a href="#" class="social-icon-link bi bi-facebook"></a></li>
                        <li><a href="#" class="social-icon-link bi bi-twitter"></a></li>
                        <li><a href="#" class="social-icon-link bi bi-instagram"></a></li>
                        <li><a href="#" class="social-icon-link bi bi-youtube"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        function validateName() {
            var nameInput = document.getElementById("name");
            var nameError = document.getElementById("nameError");
            var regex = /^[A-Za-z\s]+$/;

            if (!regex.test(nameInput.value)) {
                nameError.textContent = "Please enter a valid name.";
                nameInput.focus();
            } else {
                nameError.textContent = "";
            }
        }

        // $(document).ready(function () {
        //     $('#specialization').change(function () {
        //         var specialization = $(this).val();
        //         if (specialization) {
        //             $.ajax({
        //                 url: '/get-doctors',
        //                 type: 'POST',
        //                 data: {
        //                     specialization: specialization,
        //                     _token: '{{ csrf_token() }}'
        //                 },
        //                 success: function (data) {
        //                     $('#doctor').val(data);
        //                 }
        //             });
        //         }
        //     });
        // });

        document.querySelector('form').addEventListener('submit', function(e) {
            const timeInput = document.getElementById('time');
            if (timeInput.value && !timeInput.value.includes(':')) {
                const hours = timeInput.value.substring(0, 2);
                const minutes = timeInput.value.substring(2, 4);
                timeInput.value = `${hours}:${minutes}`;
            }
        });
    </script>
</body>

</html>