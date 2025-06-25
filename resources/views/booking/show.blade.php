<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Appointment at Kijabe Hospital</title>
    <meta name="description"
        content="Schedule your appointment with Kijabe Hospital easily using our online booking form. Book your consultation with our specialists today.">
    <meta name="keywords"
        content="Kijabe Hospital, book appointment, online booking, doctor appointment, healthcare, Kenya, medical services, schedule consultation">
    <link rel="canonical" href="https://www.kijabehospital.org/booking">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Slider Styles */
        .slider {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .slider img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slider img.active {
            opacity: 1;
        }

        /* Button Hover Effect */
        .booking-button {
            display: inline-block;
            background-color: #159ed5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .booking-button:hover {
            background-color: #117ea8;
            transform: translateY(-2px);
        }

        /* Loader Styles */
        .spinner {
            display: inline-block;
            width: 1.5rem;
            height: 1.5rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Honeypot Field Styles */
        .honeypot {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Include Header (Navigation) -->
    @include('layouts.navigation')

    <!-- Booking Form Section -->
    <div class="container mx-auto my-10 px-6">
        <div class="flex flex-col md:flex-row bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Image Slider Section -->
            <div class="md:w-1/2 relative h-64 md:h-auto">
                <div class="slider">
                    <img src="images/derma.jpg" alt="Dermatology Services" class="active">
                    <img src="images/tele.jpg" alt="Telemedicine Services">
                    <img src="images/newsletter.jpg" alt="Hospital Newsletter">
                </div>
            </div>

            <!-- Booking Form Section -->
            <div class="md:w-1/2 p-8">
                <h2 class="text-3xl font-bold text-center text-[#159ed5] mb-4">Book Your Appointment</h2>
                <p class="text-center text-gray-600 mb-6">Please fill in the form below to schedule an appointment.</p>

                <!-- Display success/error messages -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="bookingForm" action="{{ route('booking.submitExternal') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Honeypot Fields -->
                    <div class="honeypot">
                        <label for="honey_name">Leave this field blank</label>
                        <input type="text" name="honey_name" id="honey_name" value="">
                    </div>
                    <input type="hidden" name="honey_time" id="honey_time" value="">

                    <!-- Patient number -->
                    <div>
                        <label for="patient_number" class="block text-sm font-medium text-gray-700">Patient Number
                            (optional if new patient)</label>
                        <input type="text" name="patient_number" id="patient_number"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]"
                            placeholder="0*******">
                    </div>
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="full_name" id="name"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]"
                            placeholder="John Doe" required>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span
                                class="text-red-600">*</span></label>
                        <input type="email" name="email" id="email"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]"
                            placeholder="you@example.com" required>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span
                                class="text-red-600">*</span></label>
                        <input type="tel" name="phone" id="phone"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]"
                            placeholder="+123456789" required>
                    </div>

                    <!-- Date of Appointment -->
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700">Preferred
                            Date <span class="text-red-600">*</span></label>
                        <input type="date" name="appointment_date" id="appointment_date"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]"
                            required>
                    </div>

                    <!-- Service Type Awareness -->
                    <div>
                        <label for="specialization_awareness" class="block text-sm font-medium text-gray-700">Are you
                            sure of the service type needed? <span class="text-red-600">*</span></label>
                        <select name="specialization_awareness" id="specialization_awareness"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]"
                            required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="not_aware">Not Sure</option>
                            <option value="aware">Sure – Proceed to Selection</option>
                        </select>
                        @error('specialization_awareness')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Specialization Selection (Hidden by Default) -->
                    <div id="specialization_container" class="hidden">
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Service Type <span
                                class="text-red-600">*</span></label>
                        <select name="specialization" id="specialization"
                            class="form-control @error('specialization') is-invalid @enderror mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]">
                            <option value="" disabled selected>Select a service</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization }}">{{ $specialization }}</option>
                            @endforeach
                        </select>
                        @error('specialization')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes <span
                                class="text-red-600">*</span></label>
                        <textarea name="notes" id="notes" rows="4"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]"
                            placeholder="Please explain your request in detail. This information helps us serve you better."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" id="submitButton"
                            class="w-full bg-[#159ed5] text-white py-3 rounded-md shadow-md hover:bg-blue-600 transition duration-300 flex items-center justify-center">
                            <span id="buttonText">Submit Booking</span>
                            <span id="buttonLoader" class="spinner hidden"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    @include('layouts.footer')

    <!-- JavaScript for Dynamic Specialization Selection, Form Submission, and Honeypot -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const awarenessSelect = document.getElementById('specialization_awareness');
            const specializationContainer = document.getElementById('specialization_container');
            const specializationSelect = document.getElementById('specialization');
            const form = document.getElementById('bookingForm');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoader = document.getElementById('buttonLoader');
            const honeyTime = document.getElementById('honey_time');

            // Set honeypot timestamp
            honeyTime.value = Math.floor(Date.now() / 1000); // Current timestamp in seconds

            // Dynamic specialization selection
            awarenessSelect.addEventListener('change', function () {
                if (this.value === 'aware') {
                    specializationContainer.classList.remove('hidden');
                    specializationSelect.setAttribute('required', 'required');
                } else {
                    specializationContainer.classList.add('hidden');
                    specializationSelect.removeAttribute('required');
                    specializationSelect.value = ''; // Reset selection
                }
            });

            // Form submission with loader
            form.addEventListener('submit', function (event) {
                // Prevent multiple submissions
                if (submitButton.disabled) {
                    event.preventDefault();
                    return;
                }

                // Show loader and disable button
                submitButton.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoader.classList.remove('hidden');
            });

            // Simple Image Slider
            const images = document.querySelectorAll('.slider img');
            let currentImage = 0;

            function showNextImage() {
                images[currentImage].classList.remove('active');
                currentImage = (currentImage + 1) % images.length;
                images[currentImage].classList.add('active');
            }

            setInterval(showNextImage, 3000); // Change image every 3 seconds
        });
    </script>

</body>

</html>