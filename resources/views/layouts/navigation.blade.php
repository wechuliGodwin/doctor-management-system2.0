<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kijabe Hospital - @yield('title', 'Quality Healthcare with Compassion')</title>
    <meta name="description" content="Kijabe Hospital: A 363-bed non-profit hospital in Kenya, providing a wide range of medical and surgical services with a focus on compassionate care;">
    <meta name="keywords" content="Kijabe Hospital, mission hospital, healthcare, surgery, Kenya, Africa, medical training, community health, doctors, nurses, lab services, pharmacy, x-ray, ultrasound, maternity, pediatrics, oncology, cardiology, ophthalmology, orthopedics, rehabilitation, emergency medicine, critical care, family medicine, patient care, medical research, global health, AIC Kijabe Hospital, Kijabe Kenya Hospital">
    <link rel="canonical" href="https://www.kijabehospital.org">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/fav.png') }}">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 15px;
            font-weight: 300;
        }
        .bg-gradient-primary {
            @if (!Route::is('home'))
                background: rgba(255, 255, 255, 0.1);
            @else
                background-size: cover;
            @endif
            backdrop-filter: blur(10px);
        }
        .dropdown-menu, .dropdown-menu-scrollable {
            background: #ffffff;
        }
        .dark .dropdown-menu, .dark .dropdown-menu-scrollable {
            background: #1f2937;
        }
        .text-gray-800, .dark\:text-gray-200 {
            line-height: 1.5;
        }
        .nav-item {
            @if (app()->getLocale() === 'en')
                font-size: 17px;
            @else
                font-size: 15px;
            @endif
            font-weight: 500;
            color: #000000;
            transition: color 0.3s ease, background-color 0.3s ease;
        }
        .nav-item:hover {
            color: #666666;
        }
        .scrolled .nav-item {
            color: #159ed5;
        }
        .scrolled .nav-item:hover {
            color: #0d7ca7;
        }
        .dropdown-menu {
            font-size: 15px;
        }
        .dropdown-menu .font-bold {
            font-size: 17px;
        }
        .dropdown-menu-scrollable {
            max-height: 300px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        .top-bar {
            background: linear-gradient(to right, #1E3A5F, #2A4E7A);
            color: #FFFFFF;
        }
        .top-bar-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: #FFFFFF;
            font-weight: 600;
        }
        .top-bar-item:hover {
            background: #159ed5;
            color: #FFFFFF;
            text-decoration: underline;
        }
        .top-bar-item:hover a {
            color: #FFFFFF;
            text-decoration: underline;
        }
        .top-bar-btn {
            background: linear-gradient(45deg, #159ed5, #0d7ca7, #159ed5);
            background-size: 200% 200%;
            animation: gradientShift 5s ease infinite;
            color: #FFFFFF;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            transition: all 0.3s ease;
        }
        .top-bar-btn:hover {
            background: linear-gradient(45deg, #1386b5, #0b5f87, #1386b5);
            box-shadow: 0 6px 20px rgba(21, 158, 213, 0.8);
            text-decoration: underline;
        }
        .social-icons a {
            font-size: 1.25rem;
            padding: 0.5rem;
            color: #FFFFFF;
            transition: color 0.3s ease;
        }
        .social-icons a:hover {
            color: #D1E8FF;
        }
        .language-btn {
            color: #FFFFFF;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .language-btn:hover {
            background: #159ed5;
        }
        .language-dropdown {
            min-width: 120px;
            z-index: 60;
        }
        /* Stylish Call Icon Styles */
        .call-icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(21, 158, 213, 0.1);
            transition: all 0.3s ease;
        }
        .call-icon-wrapper:hover {
            background: rgba(21, 158, 213, 0.3);
            transform: scale(1.1);
        }
        .call-icon-wrapper i {
            font-size: 1.2rem;
            color: #FFFFFF;
            animation: phonePulse 2s infinite;
        }
        .call-icon-wrapper:hover i {
            color: #D1E8FF;
        }
        @keyframes phonePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @media only screen and (max-width: 767px) {
            .nav-item {
                color: #159ed5;
                @if (app()->getLocale() === 'en')
                    font-size: 16px;
                @else
                    font-size: 14px;
                @endif
            }
            .nav-item:hover, .nav-item:active {
                background-color: #D1E8FF;
                color: #0d7ca7;
            }
            .top-bar-item, .top-bar-btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            .social-icons, .location-span {
                display: none !important;
            }
            .dropdown-menu {
                font-size: 14px;
                background: #ffffff;
            }
            .dark .dropdown-menu {
                background: #1f2937;
            }
            .dropdown-menu .font-bold {
                font-size: 16px;
            }
            .dropdown-menu a {
                color: #159ed5;
            }
            .dropdown-menu a:hover, .dropdown-menu a:active {
                background-color: #D1E8FF;
                color: #0d7ca7;
            }
            .top-bar-link i {
                display: none;
            }
            .dropdown-menu-scrollable {
                max-height: 300px;
                background: #ffffff;
            }
            .dark .dropdown-menu-scrollable {
                background: #1f2937;
            }
            .language-btn {
                padding: 0.5rem;
            }
            .language-dropdown {
                min-width: 80px;
            }
            .call-icon-wrapper {
                width: 28px;
                height: 28px;
            }
            .call-icon-wrapper i {
                font-size: 1rem;
            }
        }

@media only screen and (max-width: 767px) {
    .top-bar-item .fa-phone {
        display: none;
    }
}

@media only screen and (max-width: 767px) {
    .top-bar-btn {
        font-size: 0.75rem; /* Reduced from 0.875rem */
    }
}

@media only screen and (max-width: 767px) {
    .top-bar-btn .fa {
        display: none;
    }
}
    </style>
</head>
<body x-data="{ updatesOpen: false }" @keydown.escape.window="updatesOpen = false" class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
    <div class="h-0.5 bg-[#159ed5]"></div>

    <!-- Top Bar -->
    <div class="top-bar text-sm relative">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex items-center space-x-2">
                <span class="top-bar-item">
                    <i class="fa fa-phone mr-2 hidden sm:inline"></i>
                    <a href="tel:+254709728200" class="hidden sm:inline">{{ __('messages.phone_number') }}</a>
                    <a href="tel:+254709728200" class="sm:hidden">
                        <span class="call-icon-wrapper">
                            <i class="bi bi-telephone-fill"></i>
                        </span>
                    </a>
                </span>
                <span class="top-bar-item location-span">
                    <i class="bi bi-geo-alt-fill mr-2 hidden sm:inline"></i>{{ __('messages.location') }}
                </span>
                <div class="relative hidden sm:block" x-data="{ langOpen: false }" @click.away="langOpen = false">
                    <button @click="langOpen = !langOpen" class="language-btn flex items-center">
                        <span>Select Language: {{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="langOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="language-dropdown absolute left-0 mt-2 bg-white dark:bg-gray-800 shadow-lg rounded-md py-2">
                        <a href="?lang=en" class="block px-4 py-2 text-gray-600 hover:bg-[#159ed5] hover:text-white dark:text-gray-400 dark:hover:text-white">{{ __('messages.english') }}</a>
                        <a href="?lang=sw" class="block px-4 py-2 text-gray-600 hover:bg-[#159ed5] hover:text-white dark:text-gray-400 dark:hover:text-white">{{ __('messages.swahili') }}</a>
                        <a href="?lang=fr" class="block px-4 py-2 text-gray-600 hover:bg-[#159ed5] hover:text-white dark:text-gray-400 dark:hover:text-white">{{ __('messages.french') }}</a>
                    </div>
                </div>
                <div class="relative sm:hidden" x-data="{ langOpen: false }" @click.away="langOpen = false">
                    <button @click="langOpen = !langOpen" class="language-btn flex items-center">
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="langOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="language-dropdown absolute right-0 mt-2 bg-white dark:bg-gray-800 shadow-lg rounded-md py-2">
                        <a href="?lang=en" class="block px-4 py-2 text-gray-600 hover:bg-[#159ed5] hover:text-white dark:text-gray-400 dark:hover:text-white">EN</a>
                        <a href="?lang=sw" class="block px-4 py-2 text-gray-600 hover:bg-[#159ed5] hover:text-white dark:text-gray-400 dark:hover:text-white">SW</a>
                        <a href="?lang=fr" class="block px-4 py-2 text-gray-600 hover:bg-[#159ed5] hover:text-white dark:text-gray-400 dark:hover:text-white">FR</a>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('updates') }}" class="top-bar-btn" id="updatesBtn">
                    <i class="fas fa-bell mr-2 hidden sm:inline"></i>{{ __('messages.updates') }}
                </a>
                <a href="{{ route('telemedicine-patient') }}" class="top-bar-btn" id="telemedicineBtn">
                    <i class="fas fa-video mr-2 hidden sm:inline"></i>{{ __('messages.telemedicine') }}
                </a>
            </div>
            <div class="flex items-center space-x-2 social-icons">
                <a href="https://www.facebook.com/kijabehospital" target="_blank" class="hover:text-[#D1E8FF]"><i class="bi bi-facebook"></i></a>
                <a href="https://x.com/kijabehospital" target="_blank" class="hover:text-[#D1E8FF]"><i class="bi bi-twitter"></i></a>
                <a href="https://www.linkedin.com/company/AICKijabeHospital" target="_blank" class="hover:text-[#D1E8FF]"><i class="bi bi-linkedin"></i></a>
                <a href="https://api.whatsapp.com/send?phone=254709728200" target="_blank" class="hover:text-[#D1E8FF]"><i class="bi bi-whatsapp"></i></a>
                <a href="https://friendsofkijabe.org" target="_blank" class="hover:text-[#D1E8FF]"><i class="bi bi-currency-dollar"></i></a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav x-data="{ open: false }" class="bg-gradient-primary sticky top-0 z-50" id="mainNav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center pt-2">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo_main.png') }}" alt="Kijabe Hospital Logo" class="h-14 w-auto">
                    </a>
                </div>
                <div class="hidden sm:flex items-center space-x-8 mx-auto">
                    <a href="{{ route('home') }}" class="nav-item">{{ __('messages.home') }}</a>
                    <div class="relative" x-data="{ open: false, timeout: null }" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => { open = false }, 200)">
                        <button class="nav-item flex items-center">
                            {{ __('messages.about_us') }}
                            <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="dropdown-menu dropdown-menu-scrollable absolute left-1/2 transform -translate-x-1/2 mt-2 w-[600px] bg-white dark:bg-gray-800 shadow-lg rounded-md py-4 z-20">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4">
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.about_kijabe') }}</li>
                                    <li><a href="{{ route('leaders') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.our_leaders') }}</a></li>
                                    <li><a href="{{ route('history') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.our_history') }}</a></li>
                                    <li><a href="{{ route('mission') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.our_mission') }}</a></li>
                                    <li><a href="{{ route('strategy') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.our_strategy') }}</a></li>
                                    <li><a href="{{ asset('uploads/FS2023.pdf') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.previous_audit_report') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.getting_here') }}</li>
                                    <li><a href="{{ route('main-branch') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.main_branch') }}</a></li>
                                    <li><a href="{{ route('westlands-branch') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.westlands_branch') }}</a></li>
                                    <li><a href="{{ route('marira-branch') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.marira_branch') }}</a></li>
                                    <li><a href="{{ route('naivasha-branch') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.naivasha_branch') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.contact_and_more') }}</li>
                                    <li><a href="{{ route('contact') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.contact_page') }}</a></li>
                                    <li><a href="{{ route('faq') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.faqs') }}</a></li>
                                    <li><a href="{{ route('feedback.create') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.give_feedback') }}</a></li>
                                    <li><a href="{{ route('gallery') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.gallery') }}</a></li>
                                    <li><a href="{{ route('blog') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.blog') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false, timeout: null }" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => { open = false }, 200)">
                        <button class="nav-item flex items-center">
                            {{ __('messages.our_services') }}
                            <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="dropdown-menu dropdown-menu-scrollable absolute left-1/2 transform -translate-x-1/2 mt-2 w-[800px] bg-white dark:bg-gray-800 shadow-lg rounded-md py-4 z-20">
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 p-4">
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.outpatient_services') }}</li>
                                    <li><a href="{{ route('outpatient') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.general_outpatient') }}</a></li>
                                    <li><a href="{{ route('family-clinic') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.family_clinic') }}</a></li>
                                    <li><a href="{{ route('dental-clinic') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.dental_clinics') }}</a></li>
                                    <li><a href="{{ route('mental-health-clinic') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.mental_health_clinic') }}</a></li>
                                    <li><a href="{{ route('palliative-clinic') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.palliative_clinic') }}</a></li>
                                    <li><a href="{{ route('nutrition-care') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.nutrition_and_dietetics') }}</a></li>
                                    <li><a href="{{ route('oncology') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.oncology') }}</a></li>
                                    <li><a href="{{ route('physiotherapy') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.physiotherapy') }}</a></li>
                                    <li><a href="{{ route('chronic-care-clinic') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.chronic_care_clinic') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.inpatient_services') }}</li>
                                    <li><a href="{{ route('icu') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.intensive_care_unit_icu') }}</a></li>
                                    <li><a href="{{ route('hdu') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.high_dependency_unit_hdu') }}</a></li>
                                    <li><a href="{{ route('medical-ward') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.medical_ward') }}</a></li>
                                    <li><a href="{{ route('surgical-care') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.surgical_care') }}</a></li>
                                    <li><a href="{{ route('paediatrics') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.paediatrics') }}</a></li>
                                    <li><a href="{{ route('picu') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.picu_and_nicu') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.allied_services') }}</li>
                                    <li><a href="{{ route('radiology') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.radiology') }}</a></li>
                                    <li><a href="{{ route('laboratory') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.laboratory') }}</a></li>
                                    <li><a href="{{ route('pathology') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.pathology') }}</a></li>
                                    <li><a href="{{ route('pharmacy') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.pharmacy') }}</a></li>
                                    <li><a href="{{ route('chaplaincy') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.chaplaincy') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.telemedicine') }}</li>
                                    <li>
                                        <div class="p-4 bg-white dark:bg-gray-800 shadow rounded-lg text-center">
                                            <i class="fas fa-video fa-3x text-[#159ed5]"></i>
                                            <h3 class="mt-2 font-bold text-gray-700 dark:text-gray-300">{{ __('messages.telemedicine') }}</h3>
                                            <a href="{{ route('telemedicine-patient') }}" class="mt-4 inline-block bg-[#159ed5] text-white py-2 px-4 rounded-lg hover:bg-[#0d7ca7]">{{ __('messages.telemedicine_register') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false, timeout: null }" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => { open = false }, 200)">
                        <button class="nav-item flex items-center">
                            {{ __('messages.education') }}
                            <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="dropdown-menu dropdown-menu-scrollable absolute left-1/2 transform -translate-x-1/2 mt-2 w-[800px] bg-white dark:bg-gray-800 shadow-lg rounded-md py-4 z-20">
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 p-4">
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.kchs') }}</li>
                                    <li><a href="https://kchs.ac.ke" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.visit_kchs') }}</a></li>
                                    <li><a href="https://kchs.ac.ke/onlineapplication.php" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.new_students') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.short_courses') }}</li>
                                    <li><a href="{{ route('short-courses') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.short_courses_listing') }}</a></li>
                                    <li><a href="{{ route('short-courses-application') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.short_courses_application') }}</a></li>
                                    <li><a href="{{ route('brochure-download') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.download_brochure') }}</a></li>
                                    <li><a href="https://amhlearn.com/kijabe/" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.online_learning') }}</a></li>
                                    <li><a href="http://amhlearn.com/kijabe-registration" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.online_learning_registration') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.research_and_visiting_learners') }}</li>
                                    <li><a href="{{ route('research') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.research_department') }}</a></li>
                                    <li><a href="{{ route('research-day') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.annual_research_day') }}</a></li>
                                    <li><a href="{{ route('visiting-learners-hub') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.visiting_learners_hub') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.medical_simulation') }}</li>
                                    <li>
                                        <div class="p-4 bg-white dark:bg-gray-800 shadow rounded-lg text-center">
                                            <i class="fas fa-vr-cardboard fa-3x text-[#159ed5]"></i>
                                            <h3 class="mt-2 font-bold text-gray-700 dark:text-gray-300">{{ __('messages.simulation_lab') }}</h3>
                                            <a href="{{ route('simulation') }}" class="mt-4 inline-block bg-[#159ed5] text-white py-2 px-4 rounded-lg hover:bg-[#0d7ca7]">{{ __('messages.explore_simulation_lab') }}</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false, timeout: null }" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => { open = false }, 200)">
                        <button class="nav-item flex items-center">
                            {{ __('messages.important_links') }}
                            <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="dropdown-menu dropdown-menu-scrollable absolute left-1/2 transform -translate-x-1/2 mt-2 w-[600px] bg-white dark:bg-gray-800 shadow-lg rounded-md py-4 z-20">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4">
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.news_and_information') }}</li>
                                    <li><a href="{{ route('blog') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.blog') }}</a></li>
                                    <li><a href="{{ route('newsletters') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.newsletters') }}</a></li>
                                    <li><a href="{{ route('notices') }}" class="text-gray-600 hover:text-[#159ed5] dark:blue-gray-400 dark:hover:text-white">{{ __('messages.notices_and_others') }}</a></li>
                                    <li><a href="{{ route('guidelines') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.guidelines') }}</a></li>
                                    <li><a href="{{ route('diseases.index') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.disease_search') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.careers_and_internships') }}</li>
                                    <li><a href="https://recruit.kijabehospital.org/" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.careers_portal') }}</a></li>
                                    <li><a href="https://recruit.kijabehospital.org/storage/manual/ERecruitmentManual.pdf" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.how_to_apply') }}</a></li>
                                    <li><a href="https://staff.kijabehospital.org" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.staff_portal') }}</a></li>
                                    <li><a href="{{ route('internships-application') }}" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.internship_application') }}</a></li>
                                </ul>
                                <ul class="space-y-1">
                                    <li class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.others') }}</li>
                                    <li><a href="https://friendsofkijabe.org/" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.friends_of_kijabe') }}</a></li>
                                    <li><a href="https://africanmissionhealthcare.org/" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.amh') }}</a></li>
                                    <li><a href="https://kijabeanesthesia.vercel.app/" target="_blank" class="text-gray-600 hover:text-[#159ed5] dark:text-gray-400 dark:hover:text-white">{{ __('messages.anesthesia_guidelines') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="https://recruit.kijabehospital.org/" target="_blank" class="nav-item">{{ __('messages.careers_portal') }}</a>
                    <a href="{{ route('booking.show') }}" class="nav-item">{{ __('messages.bookings') }}</a>
                    <a href="https://friendsofkijabe.org" target="_blank" class="nav-item">{{ __('messages.donate') }}</a>
                </div>
                <div class="flex sm:hidden">
                    <button @click="open = !open" class="text-[#159ed5] hover:text-[#0d7ca7] focus:outline-none">
                        <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden text-[#159ed5]">
                <div class="pt-2 pb-3 space-y-1 text-left">
                    <a href="{{ route('home') }}" class="nav-item block px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7]">{{ __('messages.home') }}</a>
                    <div x-data="{ aboutMenuMobile: false }">
                        <button @click="aboutMenuMobile = !aboutMenuMobile" class="nav-item w-full text-left px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7] flex items-center justify-between">
                            {{ __('messages.about_us') }}
                            <svg :class="{ 'transform rotate-180': aboutMenuMobile }" class="w-4 h-4 inline-block transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="aboutMenuMobile" class="dropdown-menu dropdown-menu-scrollable pl-4 space-y-1">
                            <a href="{{ route('leaders') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.our_leaders') }}</a>
                            <a href="{{ route('history') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.our_history') }}</a>
                            <a href="{{ route('mission') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.our_mission') }}</a>
                            <a href="{{ route('strategy') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.our_strategy') }}</a>
                            <a href="{{ asset('uploads/FS2023.pdf') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.previous_audit_report') }}</a>
                            <a href="{{ route('main-branch') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.main_branch') }}</a>
                            <a href="{{ route('westlands-branch') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.westlands_branch') }}</a>
                            <a href="{{ route('marira-branch') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.marira_branch') }}</a>
                            <a href="{{ route('naivasha-branch') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.naivasha_branch') }}</a>
                            <a href="{{ route('contact') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.contact_page') }}</a>
                            <a href="{{ route('faq') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.faqs') }}</a>
                            <a href="{{ route('feedback.create') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.give_feedback') }}</a>
                            <a href="{{ route('gallery') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.gallery') }}</a>
                            <a href="{{ route('blog') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.blog') }}</a>
                        </div>
                    </div>
                    <div x-data="{ servicesMenuMobile: false }">
                        <button @click="servicesMenuMobile = !servicesMenuMobile" class="nav-item w-full text-left px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7] flex items-center justify-between">
                            {{ __('messages.our_services') }}
                            <svg :class="{ 'transform rotate-180': servicesMenuMobile }" class="w-4 h-4 inline-block transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="servicesMenuMobile" class="dropdown-menu dropdown-menu-scrollable pl-4 space-y-1">
                            <span class="block px-4 py-2 font-bold">{{ __('messages.outpatient_services') }}</span>
                            <a href="{{ route('outpatient') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.general_outpatient') }}</a>
                            <a href="{{ route('family-clinic') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.family_clinic') }}</a>
                            <a href="{{ route('dental-clinic') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.dental_clinics') }}</a>
                            <a href="{{ route('mental-health-clinic') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.mental_health_clinic') }}</a>
                            <a href="{{ route('palliative-clinic') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.palliative_clinic') }}</a>
                            <a href="{{ route('nutrition-care') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.nutrition_and_dietetics') }}</a>
                            <a href="{{ route('oncology') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.oncology') }}</a>
                            <a href="{{ route('physiotherapy') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.physiotherapy') }}</a>
                            <a href="{{ route('chronic-care-clinic') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.chronic_care_clinic') }}</a>
                            <span class="block px-4 py-2 font-bold">{{ __('messages.inpatient_services') }}</span>
                            <a href="{{ route('icu') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.intensive_care_unit_icu') }}</a>
                            <a href="{{ route('hdu') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.high_dependency_unit_hdu') }}</a>
                            <a href="{{ route('medical-ward') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.medical_ward') }}</a>
                            <a href="{{ route('surgical-care') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.surgical_care') }}</a>
                            <a href="{{ route('paediatrics') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.paediatrics') }}</a>
                            <a href="{{ route('picu') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.picu_and_nicu') }}</a>
                            <span class="block px-4 py-2 font-bold">{{ __('messages.allied_services') }}</span>
                            <a href="{{ route('radiology') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.radiology') }}</a>
                            <a href="{{ route('laboratory') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.laboratory') }}</a>
                            <a href="{{ route('pathology') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.pathology') }}</a>
                            <a href="{{ route('pharmacy') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.pharmacy') }}</a>
                            <a href="{{ route('chaplaincy') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.chaplaincy') }}</a>
                            <span class="block px-4 py-2 font-bold">{{ __('messages.telemedicine') }}</span>
                            <a href="{{ route('telemedicine-patient') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.telemedicine') }}</a>
                        </div>
                    </div>
                    <div x-data="{ educationMenuMobile: false }">
                        <button @click="educationMenuMobile = !educationMenuMobile" class="nav-item w-full text-left px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7] flex items-center justify-between">
                            {{ __('messages.education') }}
                            <svg :class="{ 'transform rotate-180': educationMenuMobile }" class="w-4 h-4 inline-block transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="educationMenuMobile" class="dropdown-menu dropdown-menu-scrollable pl-4 space-y-1">
                            <a href="https://kchs.ac.ke" target="_blank" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.visit_kchs') }}</a>
                            <a href="https://kchs.ac.ke/onlineapplication.php" target="_blank" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.new_students') }}</a>
                            <a href="https://amhlearn.com/kijabe/" target="_blank" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.amhlearn') }}</a>
                            <a href="{{ route('short-courses') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.short_courses_listing') }}</a>
                            <a href="{{ route('short-courses-application') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.short_courses_application') }}</a>
                            <a href="{{ route('brochure-download') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.download_brochure') }}</a>
                            <a href="{{ route('research') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.research_department') }}</a>
                            <a href="{{ route('research-day') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.annual_research_day') }}</a>
                            <a href="{{ route('visiting-learners-hub') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.visiting_learners_hub') }}</a>
                            <a href="https://kijabehospital.org/visiting-learners/create" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.new_learner_application') }}</a>
                            <a href="{{ route('simulation') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.simulation') }}</a>
                        </div>
                    </div>
                    <div x-data="{ linksMenuMobile: false }">
                        <button @click="linksMenuMobile = !linksMenuMobile" class="nav-item w-full text-left px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7] flex items-center justify-between">
                            {{ __('messages.important_links') }}
                            <svg :class="{ 'transform rotate-180': linksMenuMobile }" class="w-4 h-4 inline-block transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="linksMenuMobile" class="dropdown-menu dropdown-menu-scrollable pl-4 space-y-1">
                            <a href="{{ route('blog') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.blog') }}</a>
                            <a href="{{ route('news') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.news') }}</a>
                            <a href="{{ route('newsletters') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.newsletters') }}</a>
                            <a href="{{ route('notices') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.notices') }}</a>
                            <a href="{{ route('gallery') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.gallery') }}</a>
                            <a href="{{ route('guidelines') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.guidelines') }}</a>
                            <a href="https://recruit.kijabehospital.org/" target="_blank" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.careers_portal') }}</a>
                            <a href="https://friendsofkijabe.org/" target="_blank" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.friends_of_kijabe') }}</a>
                            <a href="{{ route('donate') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.donate') }}</a>
                            <a href="{{ route('contact') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.contact') }}</a>
                            <a href="{{ route('faq') }}" class="block px-4 py-2 hover:bg-[#D1E8FF]">{{ __('messages.faq') }}</a>
                        </div>
                    </div>
                    <a href="{{ route('booking.show') }}" class="nav-item block px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7]">{{ __('messages.bookings') }}</a>
                    <a href="https://recruit.kijabehospital.org/" class="nav-item block px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7]">{{ __('messages.careers') }}</a>
                    <a href="{{ route('donate') }}" class="nav-item block px-4 py-2 font-medium hover:bg-[#D1E8FF] hover:text-[#0d7ca7]">{{ __('messages.donate') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Overlay -->
    <div id="searchOverlay" class="fixed inset-0 bg-white dark:bg-gray-900 bg-opacity-95 flex items-center justify-center z-50 hidden">
        <div class="w-full max-w-md px-4">
            <div class="relative">
                <form action="{{ route('diseases.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="query" placeholder="Search diseases..." class="w-full border border-gray-300 dark:border-gray-700 rounded-full p-4 pl-12 text-lg bg-white dark:bg-gray-800 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500 text-gray-800 dark:text-gray-200">
                    <button type="submit" class="absolute left-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
                <button id="closeSearch" class="absolute top-0 right-0 mt-3 mr-3 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Updates Modal -->
    <div x-show="updatesOpen" @click.away="updatesOpen = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-lg w-full shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Latest Updates</h2>
                <button @click="updatesOpen = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-2xl">�</button>
            </div>
            <div class="text-gray-700 dark:text-gray-300">
                <p class="mb-4">Stay informed with the latest news from Kijabe Hospital:</p>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fas fa-video text-blue-500 mr-2 mt-1"></i>
                        <span><strong>Telemedicine Available:</strong> Access our Telemedicine services for remote consultations. <a href="{{ route('telemedicine-patient') }}" class="text-blue-500 hover:underline">Learn More</a></span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mr-2 mt-1"></i>
                        <span><strong>No MRI Services:</strong> We currently don't have MRI services, kindly note.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchOverlay = document.getElementById('searchOverlay');
            const closeSearch = document.getElementById('closeSearch');
            const nav = document.getElementById('mainNav');
            const updatesBtn = document.getElementById('updatesBtn');
            const telemedicineBtn = document.getElementById('telemedicineBtn');

            if (searchOverlay && closeSearch) {
                window.toggleSearch = function() { searchOverlay.classList.toggle('hidden'); };
                closeSearch.addEventListener('click', () => searchOverlay.classList.add('hidden'));
                searchOverlay.addEventListener('click', (e) => { if (e.target === searchOverlay) searchOverlay.classList.add('hidden'); });
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !searchOverlay.classList.contains('hidden')) searchOverlay.classList.add('hidden'); });
            }

            if (nav) {
                window.addEventListener('scroll', () => {
                    nav.classList.toggle('scrolled', window.scrollY > 50);
                });
            }

            if (updatesBtn && telemedicineBtn) {
                function highlightElements() {
                    updatesBtn.classList.toggle('highlighted');
                    telemedicineBtn.classList.toggle('highlighted');
                }
                setInterval(highlightElements, 5000);
                updatesBtn.classList.add('highlighted');
            }
        });
    </script>
</body>
</html>
