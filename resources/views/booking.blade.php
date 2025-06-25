<!-- resources/views/booking.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Appointment - Kijabe Hospital</title>
    <meta name="description" content="Schedule your appointment with Kijabe Hospital easily using our online booking form. Book your consultation with our specialists today.">
    <meta name="keywords" content="Kijabe Hospital, book appointment, online booking, doctor appointment, healthcare, Kenya, medical services, schedule consultation">
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
            object-fit: contain; /* Show full image */
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .slider img.active {
            opacity: 1;
        }
        /* Divider Styles */
        .divider {
            margin: 1.5rem 0;
            border-top: 1px solid #e5e7eb;
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
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Include Header (Navigation) -->
    @include('layouts.navigation')

    <!-- Booking Form Section -->
    <div class="container mx-auto my-12 px-6">
        <div class="flex flex-col md:flex-row bg-white shadow-xl rounded-xl overflow-hidden">

            <!-- Image Slider Section -->
            <div class="md:w-1/2 relative h-64 md:h-auto">
                <div class="slider">
                    <img src="images/derma.jpg" alt="Dermatology Services" class="active">
                    <img src="images/tele.jpg" alt="Telemedicine Services">
                    <img src="images/newsletter.jpg" alt="Hospital Newsletter">
                </div>
            </div>

            <!-- Booking Form Section -->
            <div class="md:w-1/2 p-8 md:p-10">
                <h2 class="text-3xl font-bold text-center text-[#159ed5] mb-4">Book Your Appointment</h2>
                <p class="text-center text-gray-600 mb-6">Please fill in the form below to schedule an appointment.</p>

                <!-- Display Success Message -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                @endif

                <!-- Display General Error Message -->
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif

                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('booking.submit') }}" method="POST" class="space-y-6" id="booking-form">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]" placeholder="John Doe" required>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]" placeholder="you@example.com" required>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]" placeholder="+254712345678" required>
                    </div>

                    <!-- Date of Appointment -->
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700">Preferred Date</label>
                        <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]" required>
                    </div>

                    <!-- Service Type -->
                    <div>
                        <label for="service" class="block text-sm font-medium text-gray-700">Service Type</label>
                        <input type="text" name="service" id="service" value="{{ old('service') }}" class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]" placeholder="Oncology" required>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]" placeholder="Any additional information...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Challenge Question -->
                    <div>
                        <label for="challenge" class="block text-sm font-medium text-gray-700">What is the sum of 4 and 6?</label>
                        <input type="text" name="challenge" id="challenge" class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#159ed5] focus:border-[#159ed5]" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="w-full bg-[#159ed5] text-white py-3 rounded-md shadow-md hover:bg-blue-600 transition duration-300">Submit Booking</button>
                    </div>
                </form>

                <!-- Divider for Telemedicine Button -->
                <hr class="divider">

                <!-- Book Telemedicine Button -->
                <div class="mt-6 text-center">
                    <a href="https://kijabehospital.org/telemedicine-patient" class="booking-button">Book Telemedicine Appointment</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    @include('layouts.footer')

    <script>
        // Simple Image Slider
        const images = document.querySelectorAll('.slider img');
        let currentImage = 0;

        function showNextImage() {
            images[currentImage].classList.remove('active');
            currentImage = (currentImage + 1) % images.length;
            images[currentImage].classList.add('active');
        }

        setInterval(showNextImage, 3000); // Change image every 3 seconds

        // Challenge Question Validation
        document.getElementById('booking-form').addEventListener('submit', function(event) {
            const challengeResponse = document.getElementById('challenge').value.trim();
            if (challengeResponse !== '10') {
                event.preventDefault();
                alert('Incorrect answer. Please try again.');
            }
        });
    </script>

</body>
</html>