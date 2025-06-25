<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Kijabe Hospital | Medical Services in Kenya</title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="Get in touch with Kijabe Hospital for medical services, healthcare information, hospital contact, patient inquiries, and emergency services in Kenya. Fill out our contact form or call us directly for immediate assistance.">
    <meta name="keywords" content="Kijabe Hospital, contact us, medical services, healthcare, hospital contact, patient inquiries, emergency services, Kenya healthcare">
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .input-focus:focus {
            outline: none;
            border-color: #159ed5;
            box-shadow: 0 0 0 3px rgba(21, 158, 213, 0.5);
        }
        /* Custom font styles for smaller and thinner */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
        }
        h1 {
            font-size: 2.5rem;
        }
        h2 {
            font-size: 1.5rem;
        }
        h3 {
            font-size: 1.25rem;
        }
        p, label, input, textarea, button {
            font-size: 0.9rem;
        }
        /* Custom shadow for images */
        .theme-shadow {
            box-shadow: 0 4px 6px -1px rgba(21, 158, 213, 0.1), 0 2px 4px -1px rgba(21, 158, 213, 0.06);
        }
        /* Telemedicine Button Styles */
        .telemedicine-btn {
            background: linear-gradient(90deg, #159ed5, #0d6ea0);
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            box-shadow: 0 4px 14px rgba(21, 158, 213, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .telemedicine-btn:hover {
            background: linear-gradient(90deg, #0d6ea0, #159ed5);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(21, 158, 213, 0.5);
        }
        .telemedicine-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: 0.5s;
        }
        .telemedicine-btn:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Include Navigation -->
    @include('layouts.navigation')

    <div class="container mx-auto my-10 px-6">
        <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Get in Touch with Kijabe Hospital</h1>

        <div class="bg-white p-8 rounded-lg shadow-xl mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">How Can We Assist You?</h2>
            <p class="text-gray-700 mb-6">
                Whether you need information about our <strong>medical services</strong>, have <strong>patient inquiries</strong>, or require <strong>emergency services</strong>, Kijabe Hospital is here for you. Reach out to us through the details below or fill out our contact form for personalized assistance.
            </p>

            <!-- Telemedicine Booking Button -->
            <div class="text-center mb-8">
                <a href="https://kijabehospital.org/telemedicine-patient" target="_blank" class="telemedicine-btn inline-block">
                    Book a Telemedicine Appointment
                </a>
            </div>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Form -->
                <div class="bg-white p-8 rounded-lg shadow-xl">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Send Us Your Inquiry</h2>
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6" id="contact-form">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 p-3 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-0 input-focus" placeholder="Your Name" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 p-3 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-0 input-focus" placeholder="you@example.com" required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 p-3 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-0 input-focus" placeholder="+254 712 345 678" required>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Your Message</label>
                            <textarea name="message" id="message" rows="4" class="mt-1 p-3 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-0 input-focus" placeholder="Your Message" required>{{ old('message') }}</textarea>
                        </div>

                        <div>
                            <label for="challenge" class="block text-sm font-medium text-gray-700">What is the sum of 5 and 3?</label>
                            <input type="text" name="challenge" id="challenge" class="mt-1 p-3 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-0 input-focus" required>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="subscribe" name="subscribe" class="mr-2">
                            <label for="subscribe" class="text-sm text-gray-700">Subscribe to receive Kijabe Hospital newsletter</label>
                        </div>

                        <div class="text-center relative">
                            <button type="submit" class="w-full bg-[#159ed5] text-white py-3 rounded-md shadow-md hover:bg-blue-600 transition duration-300" id="submit-btn">Send Message</button>
                            <div id="loading-overlay" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 hidden">
                                <img src="{{ asset('images/Blocks.gif') }}" alt="Submitting..." class="w-16 h-16">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Image Placement -->
                <div class="flex flex-col items-center bg-white p-8 rounded-lg shadow-xl theme-shadow space-y-4">
                    <div class="flex flex-col items-center space-y-2">
                        <img src="{{ asset('images/10.png') }}" alt="Get Quick Response" class="w-full h-auto object-cover rounded-lg">
                        <p class="text-center text-sm text-gray-700">Get Quick Response</p>
                    </div>
                    <div class="flex flex-col items-center space-y-2">
                        <img src="{{ asset('images/9.png') }}" alt="Get Comprehensive Quality Care" class="w-full h-auto object-cover rounded-lg">
                        <p class="text-center text-sm text-gray-700">Get Comprehensive Quality Care</p>
                    </div>
                </div>
            </div>

            <!-- Address and Map -->
            <div class="bg-white p-8 rounded-lg shadow-xl mt-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Our Address</h3>
                        <p class="text-gray-700">
                            Kijabe Hospital,<br>
                            P.O. Box 20-00220,<br>
                            Kijabe, Kenya.
                        </p>
                        <h3 class="text-xl font-bold text-gray-800 mt-4 mb-2">Phone Numbers</h3>
                        <p class="text-gray-700">
                            +254 709 72 82 00<br>
                            +254 791 333 000
                        </p>
                        <h3 class="text-xl font-bold text-gray-800 mt-4 mb-2">Email</h3>
                        <p class="text-gray-700">
                            cad@kijabehospital.org
                        </p>
                    </div>
                    <div class="w-full h-64 bg-gray-200 rounded-lg overflow-hidden">
                        <!-- Ensure the map loads by adding 'loading="lazy"' and 'title' attributes -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127880.2920237744!2d36.47958727408802!3d-0.9534888457680094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f19b2671f8e85%3A0x7467b885ebba2c73!2sKijabe%20Hospital!5e0!3m2!1sen!2ske!4v1693126598451!5m2!1sen!2ske" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" title="Kijabe Hospital Location"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    @include('layouts.footer')

    <!-- JavaScript for Form Enhancements, Loader, and Overlay -->
    <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            // Validate the math challenge
            const challengeResponse = document.getElementById('challenge').value.trim();
            if (challengeResponse !== '8') { 
                event.preventDefault(); 
                alert('Incorrect answer. Please try again.');
                return;
            }

            // Show the loading GIF and disable the submit button
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('submit-btn').classList.add('opacity-50', 'cursor-not-allowed');
        });
        
        // Enhance form field visibility on focus
        const inputs = document.querySelectorAll('.input-focus');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#159ed5';
            });
            input.addEventListener('blur', function() {
                this.style.borderColor = '#d1d5db';
            });
        });
    </script>
</body>
</html>