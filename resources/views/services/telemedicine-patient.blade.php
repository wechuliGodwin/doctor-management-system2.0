@extends('layouts.app')

@section('content')
<!-- Skip Navigation -->
<a href="#main-content" class="sr-only focus:not-sr-only">Skip to main content</a>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<div class="telemedicine-container">
    <!-- Hero Slider -->
    <section class="hero-slider" aria-label="Telemedicine Service Showcase">
        <div class="slider-wrapper">
            <div class="slider-track">
                <div class="slide">
                    <img src="{{ asset('images/10.png') }}" alt="Doctor consultation" class="slide-image">
                    <div class="bluish-overlay"></div>
                </div>
                <div class="slide">
                    <img src="{{ asset('images/9.png') }}" alt="Medical discussion" class="slide-image">
                    <div class="bluish-overlay"></div>
                </div>
            </div>
            <div class="slider-overlay"></div>
            <div class="slider-content">
                <div class="announcement-bar" role="alert">
                    <strong>Consultation Hours:</strong> Tuesday & Thursday 8:00 AM - 10:00 AM
                </div>
                <h2 class="slider-heading">Welcome to Kijabe Hospital Telemedicine</h2>
                <a href="{{ route('register') }}" class="slider-cta">Start Your Health Journey</a>
            </div>
            <div class="slider-nav">
                <div class="slider-dots"></div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main id="main-content" class="main-content">
        <section class="services-section">
            <div class="services-grid">
                <!-- Left Side - Services List -->
                <div class="services-list">
                    <h2 class="section-heading">Our Telemedicine Services</h2>
                    @foreach([
                        ['name' => 'Tele-General Medicine consultation', 'price' => '500.00', 'department' => 'Outpatient', 'icon' => 'fa-solid fa-stethoscope'],
                        ['name' => 'Tele-Oncology General', 'price' => '1,500.00', 'department' => 'Oncology', 'icon' => 'fa-solid fa-brain'],
                        ['name' => 'Tele-Oncology Specialist', 'price' => '4,000.00', 'department' => 'Oncology', 'icon' => 'fa-solid fa-ribbon'],
                        ['name' => 'Tele-Specialty consultation', 'price' => '1,000.00', 'department' => 'Specialty', 'icon' => 'fa-solid fa-user-md'],
                        ['name' => 'Tele-Private consultation', 'price' => '3,000.00', 'department' => 'Specialty', 'icon' => 'fa-solid fa-stethoscope'],
                        ['name' => 'Tele-Adult Psychiatry', 'price' => '4,500.00', 'department' => 'Psychology', 'icon' => 'fa-solid fa-ribbon'],
                        ['name' => 'Tele-Child Psychiatry', 'price' => '6,000.00', 'department' => 'Psychology', 'icon' => 'fa-solid fa-child'],
                        ['name' => 'Tele-Psychotherapy', 'price' => '1,000.00', 'department' => 'Psychology', 'icon' => 'fa-solid fa-comments'],
                        ['name' => 'Tele-Nutrition consultation', 'price' => '500.00', 'department' => 'Nutrition', 'icon' => 'fa-solid fa-carrot'],
                        ['name' => 'Tele-Child Psychiatry follow up', 'price' => '5,000.00', 'department' => 'Psychology', 'icon' => 'fa-solid fa-child-reaching'],
                        ['name' => 'Tele-Adult Psychiatry follow up', 'price' => '4,000.00', 'department' => 'Psychology', 'icon' => 'fa-solid fa-user-md'],
                        ['name' => 'Tele-Palliative consultation', 'price' => '500.00', 'department' => 'Outpatient', 'icon' => 'fa-solid fa-stethoscope'],
                        ['name' => 'Tele-Palliative follow up', 'price' => '1,000.00', 'department' => 'Outpatient', 'icon' => 'fa-solid fa-user-md'],
                        ['name' => 'Tele-Palliative therapy', 'price' => '3,000.00', 'department' => 'Outpatient', 'icon' => 'fa-solid fa-h-square'],
                        ['name' => 'Tele-Pharmacy - Coming Soon', 'price' => 'Coming Soon', 'department' => 'Pharmacy', 'icon' => 'fa-solid fa-pills'],
                        ['name' => 'Tele-Pathology - Coming Soon', 'price' => 'Coming Soon', 'department' => 'Pathology', 'icon' => 'fa-solid fa-microscope']
                    ] as $service)
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="{{ $service['icon'] }}"></i>
                        </div>
                        <div class="service-details">
                            <h3>{{ $service['name'] }}</h3>
                            <p class="service-price">{{ $service['price'] }}</p>
                            <span class="service-department">{{ $service['department'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Right Side Container -->
                <div class="right-side-container">
                    <!-- Announcement -->
                    <div class="service-alert" role="alert">
                        <strong>Consultation Hours:</strong> Tuesday & Thursday 8:00 AM - 10:00 AM
                    </div>

                    <!-- Registration Panel -->
                    <aside class="registration-panel">
                        <h2 class="panel-heading">Begin Your Virtual Care</h2>
                        <div class="panel-content">
                            <p class="panel-text">Secure, convenient healthcare from anywhere. Register or login to manage your health needs.</p>
                            <div class="action-buttons">
                                <a href="{{ route('register') }}" class="btn-primary">Create Account</a>
                                <a href="{{ route('login') }}" class="btn-secondary">Existing User Login</a>
                            </div>
                            <div class="support-contact">
                                <p class="support-text">Need assistance? Our team is here to help:</p>
                                <ul class="contact-list">
                                    <li>Phone: +254 (0) 709 728 200</li>
                                    <li>Email: <a href="mailto:telemedicine@kijabehospital.org">telemedicine@kijabehospital.org</a></li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </main>
</div>

<style>
    /* Base Styles */
    .telemedicine-container {
        font-family: 'Roboto', sans-serif;
        --primary-blue: #159ed5;
        --secondary-blue: #0d7ca7;
        --text-dark: #2d3748;
        --text-light: #f7fafc;
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Hero Slider */
    .hero-slider {
        position: relative;
        height: 100vh;
        overflow: hidden;
        margin-top: -7rem; /* Adjusted to account for top-bar and nav height */
        padding-top: 7rem; /* Ensure content starts below nav */
    }

    .slider-wrapper {
        position: relative;
        height: 100%;
    }

    .slider-track {
        display: flex;
        height: 100%;
        transition: transform 0.5s ease-in-out;
    }

    .slide {
        flex: 0 0 100vw;
        height: 100%;
        position: relative;
    }

    .slide-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .bluish-overlay {
        position: absolute;
        inset: 0;
        background: rgba(21, 158, 213, 0.4); /* #159ed5 with 40% opacity */
        z-index: 1;
    }

    .slider-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));
        z-index: 2;
    }

    .slider-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 10;
        color: white;
        max-width: 90%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
        animation: fadeInUp 1s ease-out forwards;
    }

    .announcement-bar {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-blue);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 500;
        box-shadow: var(--shadow-md);
        width: fit-content;
        max-width: 90%;
        text-align: center;
    }

    .slider-heading {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem; /* Reduced from 3rem */
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
    }

    .slider-cta {
        display: inline-block;
        padding: 1rem 2rem;
        background: var(--primary-blue);
        color: white;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .slider-cta:hover {
        background: var(--secondary-blue);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Animation Keyframes */
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translate(-50%, -30%);
        }
        100% {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    /* Services Section */
    .services-section {
        background: white;
        padding: 2rem 1rem;
        margin: -3rem auto 0; /* Adjusted from -150px to avoid overlap */
        max-width: 1200px;
        border-radius: 1rem;
        box-shadow: var(--shadow-md);
        position: relative;
        z-index: 10;
    }

    .section-heading {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--primary-blue);
        text-align: center;
        margin-bottom: 2rem;
        background: linear-gradient(to right, var(--primary-blue), var(--secondary-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Right Side Styling */
    .right-side-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .service-alert {
        background: #e3f2fd;
        border-left: 4px solid var(--primary-blue);
        color: var(--text-dark);
        padding: 1.25rem;
        border-radius: 8px;
        box-shadow: var(--shadow-md);
    }

    .registration-panel {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .panel-heading {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .panel-text {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .action-buttons {
        display: grid;
        gap: 1rem;
        margin: 2rem 0;
    }

    .btn-primary {
        background: var(--primary-blue);
        color: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s ease;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: var(--secondary-blue);
    }

    .btn-secondary {
        background: white;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s ease;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: #f0f4f8;
    }

    .support-contact {
        border-top: 1px solid #e2e8f0;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }

    .support-text {
        color: #4a5568;
        margin-bottom: 1rem;
    }

    .contact-list {
        list-style: none;
        padding-left: 0;
    }

    .contact-list li {
        margin-bottom: 0.5rem;
    }

    .contact-list a {
        color: var(--primary-blue);
        text-decoration: none;
    }

    .contact-list a:hover {
        text-decoration: underline;
    }

    /* Services List */
    .services-list {
        display: grid;
        gap: 1rem;
    }

    .service-card {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        transition: transform 0.3s ease;
    }

    .service-card:hover {
        transform: translateX(5px);
    }

    .service-icon {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-right: 1.5rem;
    }

    .service-price {
        color: var(--secondary-blue);
        font-weight: 600;
        margin: 0.5rem 0;
    }

    .service-department {
        font-size: 0.875rem;
        color: #4a5568;
        background: #e2e8f0;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }

    /* Responsive Design */
    .services-grid {
        display: grid;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    @media (min-width: 768px) {
        .services-grid {
            grid-template-columns: 2fr 1fr;
            padding: 0 1rem;
        }

        .right-side-container {
            position: sticky;
            top: 2rem;
            height: fit-content;
        }

        .slider-heading {
            font-size: 3.5rem; /* Reduced from 4rem */
        }
    }

    @media (max-width: 767px) {
        .hero-slider {
            height: 70vh;
            margin-top: -6rem; /* Adjusted for mobile nav height */
            padding-top: 6rem;
        }

        .services-section {
            margin: 1rem auto 0; /* Adjusted for mobile */
        }

        .services-grid {
            grid-template-columns: 1fr;
            padding: 0 1rem;
        }

        .right-side-container {
            order: -1;
            margin-bottom: 2rem;
        }

        .section-heading {
            font-size: 2rem;
        }

        .slider-heading {
            font-size: 2rem; /* Adjusted for mobile */
        }

        .slider-cta {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }

        .announcement-bar {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slider functionality
        const sliderTrack = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.slide');
        const dotsContainer = document.querySelector('.slider-dots');
        let currentIndex = 0;
        let autoSlide = true;
        let slideInterval;

        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.classList.add('slider-dot');
            dot.setAttribute('aria-label', `Slide ${index + 1}`);
            dot.style.cssText = 'width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.5); margin: 0 5px; border: none; cursor: pointer;';
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.slider-dot');

        function updateSlider() {
            sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach((dot, index) => {
                dot.style.background = index === currentIndex ? 'white' : 'rgba(255,255,255,0.5)';
                dot.setAttribute('aria-current', index === currentIndex ? 'true' : 'false');
            });
        }

        function goToSlide(index) {
            currentIndex = index;
            updateSlider();
            resetAutoSlide();
        }

        function resetAutoSlide() {
            if (autoSlide) {
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            }
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            updateSlider();
        }

        // Initialize
        updateSlider();
        if (window.innerWidth > 767) {
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Pause on hover
        sliderTrack.addEventListener('mouseenter', () => clearInterval(slideInterval));
        sliderTrack.addEventListener('mouseleave', () => {
            if (autoSlide && window.innerWidth > 767) {
                slideInterval = setInterval(nextSlide, 5000);
            }
        });
    });
</script>
@endsection
