@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container mx-auto p-0 mt-12">
    <!-- Font Imports -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <!-- Custom Styling -->
    <style>
        .themed-title {
            background: linear-gradient(to right, #159ed5, #0d7ca7);
            -webkit-background-clip: text;
            color: transparent;
            font-size: 2.5rem; /* Increased for better visibility */
        }
        .slider-container {
            position: relative;
            height: calc(100vh - 50px); 
            top: -50px; 
            overflow: hidden;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease;
        }
        .slider img {
            width: calc(100vw + 100px); 
            height: 100%;
            object-fit: cover;
            margin-left: -50px; 
        }
        .slider-text {
            position: absolute;
            left: 5%;
            top: 50%;
            transform: translateY(-50%);
            max-width: 400px;
            color: white;
            text-align: left;
            z-index: 10;
            animation: fadeInUp 1s ease-out;
            text-shadow: 2px 2px 4px rgba(128, 128, 128, 0.8);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(-50%);
            }
        }
        .slider-button {
            display: inline-block;
            padding: 12px 24px; /* Increased padding for better touch targets */
            background-color: #159ed5;
            color: white;
            text-decoration: none;
            border-radius: 8px; /* More rounded corners */
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Added transform for hover effect */
            text-shadow: none;
        }
        .slider-button:hover {
            background-color: #0d7ca7;
            transform: scale(1.05); /* Slight scale up on hover */
        }
        .service-item {
            background: #f8f9fa;
            border-radius: 12px; /* More rounded for modern look */
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .service-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .service-item .icon {
            color: #159ed5;
            font-size: 2.5em; /* Larger icons for better visibility */
        }
        .slider-dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
        }
        .dot {
            display: inline-block;
            width: 12px; /* Slightly larger dots */
            height: 12px;
            margin: 0 8px; /* More space between dots */
            background: #bbb;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.6s ease;
        }
        .dot.active {
            background: #159ed5;
        }

        /* Mobile adjustments */
        @media (max-width: 767px) {
            .slider-container {
                height: 100vh;
                top: -100px;
            }
            .slider {
                display: none;
            }
            .mobile-bg {
                display: block;
                position: absolute;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-image: url('{{ asset('images/pharmacy.png') }}');
                background-size: cover;
                background-position: center;
            }
            .slider-text {
                position: absolute;
                left: 50%;
                top: 40%;
                transform: translate(-50%, -50%);
                text-align: center;
                max-width: 100%;
                z-index: 11;
                text-shadow: 2px 2px 4px rgba(128, 128, 128, 0.8);
            }
            .slider-button {
                margin-top: 10px;
                display: block;
                margin: 0 auto;
            }
            .slider-dots {
                display: none;
            }
            .main-content {
                margin-top: -150px;
                position: relative;
                z-index: 12;
            }
        }
    </style>

    <!-- Slider Container -->
    <div class="slider-container relative">
        <div class="slider" id="slider">
            <img src="{{ asset('images/pharmacy1.jpg') }}" alt="Tele-Pharmacy Image 1">
            <img src="{{ asset('images/pharmacy2.jpg') }}" alt="Tele-Pharmacy Image 2">
        </div>
        <div class="slider-text">
            <h2 class="text-4xl mb-4">Welcome to Tele-Pharmacy</h2> <!-- Increased font size -->
            <p class="text-xl mb-4">Medication at your fingertips</p> <!-- Increased font size -->
            <a href="{{ route('contact') }}" class="slider-button">Learn More</a>
        </div>
        <div class="slider-dots" id="sliderDots"></div>
        <div class="mobile-bg"></div>
    </div>

    <!-- Main Content -->
    <div class="main-content bg-white p-6 rounded-lg shadow-lg mx-4 lg:mx-auto">
        <h1 class="text-center themed-title text-5xl lg:text-7xl mb-6"> <!-- Larger title -->
            Kijabe Hospital Tele-Pharmacy
        </h1>

        <p class="text-lg lg:text-xl text-gray-700 text-center mb-6 leading-relaxed">
            Get your medications prescribed and delivered safely from the comfort of your home. Our tele-pharmacy service combines convenience with expert care.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach([
                ['name' => 'Online Prescription Refill', 'description' => 'Refill your prescriptions without leaving your home', 'icon' => 'fa-solid fa-prescription'],
                ['name' => 'Medication Consultation', 'description' => 'Consult with our pharmacists for medication advice', 'icon' => 'fa-solid fa-comments'],
                ['name' => 'Home Delivery', 'description' => 'Have your medications delivered directly to your door', 'icon' => 'fa-solid fa-truck'],
                ['name' => 'Drug Interaction Checks', 'description' => 'Ensure your medications are safe to take together', 'icon' => 'fa-solid fa-shield'],
                ['name' => 'Pharmacist On Call', 'description' => '24/7 pharmacist support for urgent questions', 'icon' => 'fa-solid fa-phone'],
                ['name' => 'Medication Management', 'description' => 'Ongoing support for managing complex medication regimes', 'icon' => 'fa-solid fa-calendar-check']
            ] as $service)
                <div class="service-item p-4">
                    <i class="{{ $service['icon'] }} icon mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">{{ $service['name'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $service['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-gray-100 p-6 rounded-lg shadow-md mb-6">
            <h2 class="font-bold themed-title text-3xl lg:text-4xl mb-4 text-center">
                How to Use Tele-Pharmacy
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <i class="fa-solid fa-user-plus text-5xl text-[#159ed5] mb-4"></i> <!-- Larger icons -->
                    <h3 class="text-lg font-semibold">Register</h3>
                    <p class="text-sm">Create an account with your basic information.</p>
                </div>
                <div class="text-center">
                    <i class="fa-solid fa-video text-5xl text-[#159ed5] mb-4"></i>
                    <h3 class="text-lg font-semibold">Consult</h3>
                    <p class="text-sm">Book and attend a consultation with our pharmacists.</p>
                </div>
                <div class="text-center">
                    <i class="fa-solid fa-shopping-cart text-5xl text-[#159ed5] mb-4"></i>
                    <h3 class="text-lg font-semibold">Receive</h3>
                    <p class="text-sm">Get your prescription filled and delivered to your doorstep.</p>
                </div>
            </div>
        </div>

        <div class="text-center mb-6">
            <a href="{{ route('pharmacy.register') }}" class="px-6 py-3 bg-[#159ed5] text-white rounded-md shadow-md hover:bg-[#0d7ca7] hover:shadow-lg transition-all">
                Get Started with Tele-Pharmacy
            </a>
        </div>

        <div class="bg-gray-100 p-6 rounded-lg shadow-md text-center">
            <h2 class="font-bold themed-title text-3xl lg:text-4xl mb-4">
                Contact Our Pharmacy Team
            </h2>
            <p class="text-sm lg:text-base mb-2 text-gray-600">
                Need assistance? Reach out to our support team:
            </p>
            <p class="text-lg text-gray-800 font-semibold">
                Phone: +254 (0) 709 72 82 00 / +254 (0) 791 333 000<br>
                Email: <a href="mailto:telepharmacy@kijabehospital.org" class="text-blue-600 hover:underline">telepharmacy@kijabehospital.org</a>
            </p>
        </div>
    </div>
</div>

<script>
    let slideIndex = 0;
    const slides = document.getElementById('slider');
    const slideWidth = window.innerWidth;
    const dotsContainer = document.getElementById('sliderDots');

    function moveSlide() {
        slideIndex = (slideIndex + 1) % slides.children.length;
        updateSlidePosition();
        updateDots();
    }

    function updateSlidePosition() {
        slides.style.transform = `translateX(-${slideIndex * slideWidth}px)`;
    }

    function updateDots() {
        const dots = dotsContainer.children;
        for (let i = 0; i < dots.length; i++) {
            dots[i].classList.toggle('active', i === slideIndex);
        }
    }

    // Create navigation dots
    for (let i = 0; i < slides.children.length; i++) {
        let dot = document.createElement('span');
        dot.className = 'dot';
        dot.onclick = () => {
            slideIndex = i;
            updateSlidePosition();
            updateDots();
        };
        dotsContainer.appendChild(dot);
    }

    // Initial setup
    slides.style.width = `${slides.children.length * slideWidth}px`;
    updateSlidePosition();
    updateDots();

    // Auto slide for desktop only
    if (window.innerWidth > 767) {
        setInterval(moveSlide, 5000);
    }
</script>
@endsection
