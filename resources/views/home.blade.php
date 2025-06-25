@extends('layouts.app')

@section('content')
<!-- Slider Section -->
<div class="slider-wrapper relative">
    <div class="relative w-full h-screen overflow-hidden slider">
        <div id="slider" class="relative w-full h-full">
            @foreach([
                ['image' => 'images/bg1.png', 'title_key' => 'welcome_to_aic_kijabe_hospital', 'subtitle_key' => 'providing_compassionate_care', 'link' => route('about'), 'cta_key' => 'learn_more'],
                ['image' => 'images/contact.jpg', 'title_key' => 'telemedicine_services', 'subtitle_key' => 'consult_from_home', 'link' => 'https://kijabehospital.org/telemedicine-patient', 'cta_key' => 'book_now'],
                ['image' => 'images/12.png', 'title_key' => 'state_of_the_art_facilities', 'subtitle_key' => 'equipped_for_complex_procedures', 'link' => '#', 'cta_key' => 'learn_more'],
                ['image' => 'images/bg2.png', 'title_key' => 'dedicated_healthcare_professionals', 'subtitle_key' => 'committed_to_your_health', 'link' => route('contact'), 'cta_key' => 'contact_our_team']
            ] as $index => $slide)
                <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center bg-no-repeat transition-all duration-1000 ease-in-out {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ asset($slide['image']) }}');">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-black/40"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center p-6 md:p-8 max-w-2xl mx-4 relative z-10 hero-text">
                            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-6 text-shadow">{{ __('messages.' . $slide['title_key']) }}</h1>
                            <p class="text-lg md:text-xl text-white mb-8 text-shadow">{{ __('messages.' . $slide['subtitle_key']) }}</p>
                            <a href="{{ $slide['link'] }}" {{ $slide['title_key'] === 'telemedicine_services' ? 'target="_blank"' : '' }} class="inline-block text-white font-semibold text-lg underline decoration-2 decoration-white underline-offset-4 hover:text-gray-300 transition-all duration-300 cursor-pointer z-20">{{ __('messages.' . $slide['cta_key']) }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Disease Search Section -->
<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-[#159ed5] mb-12">{{ __('messages.explore_diseases_conditions') }}</h2>
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-[#159ed5] mb-4">{{ __('messages.search_by_name') }}</h3>
                <form action="{{ route('diseases.index') }}" method="GET">
                    @csrf
                    <div class="relative w-full">
                        <input type="text" name="query" placeholder="{{ __('messages.enter_disease_condition') }}" class="w-full p-4 pr-12 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-[#159ed5] transition duration-300">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#159ed5] text-white p-2 rounded-full hover:bg-[#1386b5] transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
            <div class="flex-2">
                <h3 class="text-xl font-semibold text-[#159ed5] mb-6 text-center">{{ __('messages.find_diseases_by_letter') }}</h3>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach(range('A', 'Z') as $letter)
                        <a href="{{ route('diseases.index', ['letter' => $letter]) }}" class="flex items-center justify-center h-10 w-10 bg-white text-[#159ed5] border border-[#159ed5] rounded-full hover:bg-[#159ed5] hover:text-white transition-all duration-300 font-medium">{{ $letter }}</a>
                    @endforeach
                    <a href="{{ route('diseases.index', ['letter' => '#']) }}" class="flex items-center justify-center h-10 w-10 bg-white text-[#159ed5] border border-[#159ed5] rounded-full hover:bg-[#159ed5] hover:text-white transition-all duration-300 font-medium">#</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Compassionate Care Section -->
<div class="py-16 bg-[#159ed5] text-white relative animate-on-scroll">
    <div class="absolute inset-0 bg-[#159ed5] opacity-70"></div>
    <div class="absolute inset-0">
        <img src="{{ asset('images/NICU-min.jpg') }}" alt="NICU Background" class="w-full h-full object-cover opacity-70">
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="w-full md:w-1/2 mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-12 tracking-tight text-shadow">{{ __('messages.compassionate_care_for_patients') }}</h2>
            <p class="text-lg md:text-xl mb-8 text-shadow">{{ __('messages.comprehensive_care_specialties') }}</p>
            <a href="{{ route('booking.show') }}" class="inline-block px-8 py-4 bg-white text-[#159ed5] font-semibold rounded-full shadow-lg hover:bg-gray-100 transition ease-in-out duration-300 cursor-pointer z-20">{{ __('messages.book_a_visit') }}</a>
        </div>
    </div>
</div>

<!-- Divider -->
<div class="divider"></div>

<!-- 110 Years of Quality Care Section -->
<div class="py-16 bg-gray-50 animate-on-scroll">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row gap-8 items-center">
            <div class="md:w-1/2 text-center md:text-left">
                <img src="{{ asset('images/logo_110.png') }}" alt="110 Years Logo" class="logo-110 mx-auto md:mx-0 mbWood-6 max-w-[200px] w-full">
                <h2 class="text-3xl md:text-4xl font-bold text-[#159ed5] mb-6 tracking-tight">{{ __('messages.110_years_quality_care') }}</h2>
                <p class="text-gray-800 text-lg leading-relaxed">
                    {{ __('messages.110_years_description') }}
                </p>
            </div>
            <div class="md:w-1/2">
                <img src="{{ asset('images/9.png') }}" alt="{{ __('messages.110_years_celebration_alt') }}" class="w-full h-64 md:h-80 object-cover rounded-lg shadow-lg">
            </div>
        </div>
    </div>
</div>

<!-- Divider -->
<div class="divider"></div>

<!-- Featured Care Areas Section -->
<div class="py-16 bg-gray-50 animate-on-scroll">
    <div class="container mx-auto px-6">
        @php
            $column1 = ['Audiology', 'Breast', 'Cardiology', 'Casualty', 'Chemotherapy', 'Comprehensive Care Centre (CCC)', 'Dental', 'Developmental Clinic', 'DM Clinic', 'Echo', 'Eye Clinic', 'Family Clinic', 'Gen Surg OPD', 'General OPD', 'Gynaecology'];
            $column2 = ['Hand Clinic', 'Nutrition', 'Occupational Therapy', 'OHNS', 'Oncology', 'Ortho OPD', 'Paediatrics', 'Palliative', 'Physiotherapy', 'Plastics', 'Psychology', 'Speciality Clinic', 'Speciality IMED', 'Urology', 'Telemedicine'];
        @endphp
        <div class="featured-section">
            <div class="text-container mb-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-[#159ed5] mb-4 tracking-tight">{{ __('messages.featured_care_areas') }}</h2>
                <p class="text-gray-800 text-lg">{{ __('messages.touch_million_lives') }}</p>
            </div>
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="md:w-1/3">
                    <img src="{{ asset('images/10.png') }}" alt="{{ __('messages.featured_care_areas_alt') }}" class="w-full h-64 md:h-80 object-cover rounded-lg shadow-lg">
                </div>
                <div class="md:w-2/3">
                    <div class="care-areas-grid">
                        <div>
                            @foreach($column1 as $index => $care)
                                @php
                                    $route = match(strtolower($care)) {
                                        'dental' => route('dental-clinic'),
                                        'family clinic' => route('family-clinic'),
                                        'general opd' => route('outpatient'),
                                        default => route('services.care', ['care' => Str::slug($care)])
                                    };
                                @endphp
                                <a href="{{ $route }}" class="block mb-2 hover:text-[#159ed5]">{{ $care }}</a>
                                @if($index < count($column1) - 1)
                                    <hr class="my-2">
                                @endif
                            @endforeach
                        </div>
                        <div>
                            @foreach($column2 as $index => $care)
                                @php
                                    $route = match(strtolower($care)) {
                                        'nutrition' => route('nutrition-care'),
                                        'oncology' => route('oncology'),
                                        'paediatrics' => route('paediatrics'),
                                        'palliative' => route('palliative-clinic'),
                                        'physiotherapy' => route('physiotherapy'),
                                        'psychology' => route('mental-health-clinic'),
                                        'telemedicine' => route('telemedicine-patient'),
                                        default => route('services.care', ['care' => Str::slug($care)])
                                    };
                                @endphp
                                <a href="{{ $route }}" class="block mb-2 hover:text-[#159ed5]">{{ $care }}</a>
                                @if($index < count($column2) - 1)
                                    <hr class="my-2">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-container mt-8 text-center">
                <a href="{{ route('booking.show') }}" class="booking-button">{{ __('messages.book_an_appointment') }}</a>
            </div>
        </div>
    </div>
</div>


<!-- Blog Section -->
<div class="py-16 px-6 bg-white animate-on-scroll">
    <div class="container mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-[#159ed5] mb-12 tracking-tight">{{ __('messages.recent_kijabe_news') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($blogs ?? [] as $index => $blog)
                <div class="bg-white rounded-lg shadow-lg blog-card">
                    <img src="{{ $blog->image ?? asset('images/placeholder.jpg') }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover rounded-t-lg" style="image-rendering: -webkit-optimize-contrast;">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-[#159ed5] mb-4">{{ $blog->title }}</h3>
                        <p class="text-gray-600">{{ Str::limit($blog->content ?? __('messages.no_content_available'), 100) }}</p>
                        <a href="{{ route('blog.show', $blog->id ?? '#') }}" class="text-[#159ed5] font-medium hover:underline mt-2 inline-block">{{ __('messages.read_more') }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
/* Glassy Effect for Navigation */
nav {
    background-color: rgba(255, 255, 255, 0.3); /* More transparent, less white */
    backdrop-filter: blur(10px);                 /* Keeps the glassy effect */
    -webkit-backdrop-filter: blur(10px);         /* Safari compatibility */
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);   /* Optional shadow for depth */
}

/* Glassy Overlay (if used elsewhere) */
.glassy-overlay {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    position: relative;
}

/* Slider Adjustments */
.slider-wrapper {
    margin-top: -4rem; /* Aligns slider behind nav; adjust based on nav height */
}

#slider > div {
    opacity: 0;
    transform: scale(1.1);
    transition: opacity 1s ease-in-out, transform 5s ease-in-out;
}

#slider > div.active {
    opacity: 1;
    transform: scale(1);
}

/* Hero Text Adjustments */
.hero-text {
    padding-top: 4rem; /* Ensures text is not obscured by nav */
}

.hero-title,
.hero-subtitle {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-in-out;
}

#slider > div.active .hero-title {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.2s;
}

#slider > div.active .hero-subtitle {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.4s;
}

#slider a {
    position: relative;
    z-index: 20;
    pointer-events: auto;
}

/* Base Styles */
body {
    font-family: 'Poppins', 'Roboto', 'Arial', sans-serif;
    margin: 0;
    background: #f5f5f5;
}

.text-shadow {
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

/* Scroll Animations */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease-in-out;
}

.animate-on-scroll.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Divider */
.divider {
    height: 2px;
    background: linear-gradient(to right, transparent, #159ed5, transparent);
    margin: 2rem 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Featured Care Areas */
.featured-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
}

.text-container {
    text-align: center;
}

.care-areas-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.care-areas-grid a {
    color: #159ed5;
    font-size: 1.125rem;
    line-height: 1.75rem;
    transition: text-decoration 0.3s ease, transform 0.3s ease;
    opacity: 0;
    transform: translateY(10px);
}

.care-areas-grid a.visible {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.3s ease-in-out;
}

.care-areas-grid a:hover {
    text-decoration: underline;
    transform: translateY(-2px);
}

.care-areas-grid hr {
    border: none;
    height: 1px;
    background: linear-gradient(to right, transparent, #159ed5, transparent);
    box-shadow: 0 1px 2px rgba(21, 158, 213, 0.3);
    margin: 0.5rem 0;
}

.booking-button {
    display: inline-block;
    padding: 1rem 2rem;
    background-color: #159ed5;
    color: white;
    border-radius: 0.5rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.booking-button:hover {
    background-color: #0d7ca7;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Search Box */
.search-box {
    position: relative;
    background: none;
    box-shadow: none;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ccc;
    border-radius: 9999px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.search-box input:focus {
    border-color: #159ed5;
    outline: none;
}

.search-box button {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    background-color: #159ed5;
    color: white;
    padding: 0.5rem;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-box button:hover {
    background-color: #1386b5;
}

/* Disease Explorer */
.disease-explorer {
    background: none;
    box-shadow: none;
}

.disease-explorer a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    background-color: white;
    color: #159ed5;
    border: 1px solid #159ed5;
    border-radius: 50%;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    opacity: 0;
    transform: scale(0.9);
    transition: all 0.4s ease-in-out;
    cursor: pointer;
    position: relative;
    z-index: 10;
}

.disease-explorer a.visible {
    opacity: 1;
    transform: scale(1);
}

.disease-explorer a:hover {
    background-color: #159ed5;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Blog Section */
.blog-card {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-0.25rem);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.blog-card img {
    width: 100%;
    height: 12rem;
    object-fit: cover;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

.blog-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #159ed5;
    margin-bottom: 0.5rem;
}

.blog-card p {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 1rem;
}

.blog-card a {
    color: #159ed5;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.3s ease;
    cursor: pointer;
    position: relative;
    z-index: 10;
}

.blog-card a:hover {
    color: #1386b5;
    text-decoration: underline;
}

/* Responsive Adjustments */
@media (max-width: 767px) {
    .h-screen {
        height: 70vh;
    }
    .overlay-text {
        padding: 1rem;
        max-width: 90%;
    }
    .hero-text h1,
    .overlay-text h1 {
        font-size: 1.75rem !important;
    }
    .hero-text p,
    .overlay-text p {
        font-size: 0.9rem !important;
    }
    .hero-text a,
    .overlay-text a {
        font-size: 0.85rem !important;
    }
    .hero-title,
    .hero-subtitle {
        transition-duration: 0.5s;
    }
    #slider > div {
        transform: scale(1.05);
    }
    #slider > div.active {
        transform: scale(1);
    }
    .care-areas-grid {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    .care-areas-grid a {
        font-size: 0.95rem;
        line-height: 1.5rem;
    }
    .care-areas-grid hr {
        margin: 0.3rem 0;
    }
    .booking-button {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .disease-explorer .flex-wrap {
        gap: 0.75rem;
    }
    .disease-explorer a {
        width: 2rem;
        height: 2rem;
        font-size: 0.875rem;
    }
    .search-box h3 {
        font-size: 1rem;
    }
    .search-box input {
        font-size: 0.875rem;
        padding: 0.5rem 2.5rem 0.5rem 0.75rem;
    }
    .search-box button {
        padding: 0.4rem;
        right: 0.5rem;
    }
    .search-box button svg {
        width: 1rem;
        height: 1rem;
    }
    .blog-card img {
        height: 10rem;
    }
    .blog-card h3 {
        font-size: 1.1rem;
    }
    .blog-card p {
        font-size: 0.8rem;
    }
    .blog-card a {
        font-size: 0.875rem;
    }
}

/* Additional Global Styles */
.container {
    max-width: 1280px;
    padding-left: 1rem;
    padding-right: 1rem;
    margin-left: auto;
    margin-right: auto;
}

.flex-1 {
    flex: 1;
}

.flex-2 {
    flex: 2;
}

@media (max-width: 768px) {
    h2 {
        font-size: 1.75rem !important;
    }
    p {
        font-size: 0.9rem !important;
    }
    .animate-on-scroll {
        transition-duration: 0.4s;
    }
    .logo-110 {
        max-width: 150px;
    }
}

/* New: Important Button Class */
.important-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem; /* Space between icon and text */
    padding: 1.25rem 2.5rem; /* Larger padding for bigger button */
    background-color: #159ed5;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 1.125rem; /* Larger text */
    font-weight: 700; /* Bolder text */
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.important-button:hover {
    background-color: #0d7ca7;
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.important-button i {
    font-size: 1.5rem; /* Larger icon */
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Hero Slider
        const slider = document.getElementById('slider');
        const slides = Array.from(slider.children);
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
        }

        function nextSlide() { 
            currentSlide = (currentSlide + 1) % slides.length; 
            showSlide(currentSlide); 
        }

        setInterval(nextSlide, 5000);
        showSlide(currentSlide);

        // Enable touch swipe for slider on mobile
        let touchStartX = 0;
        let touchEndX = 0;
        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        slider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 50) {
                nextSlide();
            } else if (touchEndX - touchStartX > 50) {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }
        });

        // Prevent clicks on slider container from interfering with links
        slider.addEventListener('click', (e) => {
            if (e.target.tagName === 'A') {
                return; // Let anchor tags handle their own clicks
            }
        });

        // Adjust slider position to overlap with nav
        const nav = document.querySelector('nav');
        const sliderWrapper = document.querySelector('.slider-wrapper');
        if (nav && sliderWrapper) {
            const navHeight = nav.offsetHeight;
            sliderWrapper.style.paddingTop = `${navHeight}px`;
            sliderWrapper.querySelector('.slider').style.marginTop = `-${navHeight}px`;
        }

        // Scroll Animation for Sections
        const sections = document.querySelectorAll('.animate-on-scroll');
        const observerOptions = {
            root: null,
            threshold: 0.2,
            rootMargin: '0px'
        };

        const sectionObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            sectionObserver.observe(section);
        });

        // Staggered Animation for Care Areas
        const careAreaLinks = document.querySelectorAll('.care-areas-grid a');
        const careAreaObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    careAreaLinks.forEach((link, index) => {
                        setTimeout(() => {
                            link.classList.add('visible');
                        }, index * 20);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const careAreasSection = document.querySelector('.care-areas-grid');
        if (careAreasSection) {
            careAreaObserver.observe(careAreasSection);
        }

        // Staggered Animation for Disease Explorer Alphabet Grid
        const alphabetLinks = document.querySelectorAll('.disease-explorer a');
        const alphabetObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    alphabetLinks.forEach((link, index) => {
                        setTimeout(() => {
                            link.classList.add('visible');
                        }, index * 30);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const diseaseExplorerSection = document.querySelector('.disease-explorer');
        if (diseaseExplorerSection) {
            alphabetObserver.observe(diseaseExplorerSection);
        }
    });
</script>
@endsection
