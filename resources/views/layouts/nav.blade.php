<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kijabe Hospital - Quality Healthcare with Compassion</title>
    <meta name="description" content="Kijabe Hospital: A 363-bed non-profit hospital in Kenya, providing a wide range of medical and surgical services with a focus on compassionate care.">
    <meta name="keywords" content="Kijabe Hospital, mission hospital, healthcare, surgery, Kenya, Africa, medical training, community health, doctors, nurses, lab services, pharmacy, x-ray, ultrasound, maternity, pediatrics, oncology, cardiology, ophthalmology, orthopedics, rehabilitation, emergency medicine, critical care, family medicine, patient care, medical research, global health, AIC Kijabe Hospital, Kijabe Kenya Hospital">
    <link rel="canonical" href="https://www.kijabehospital.org">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
   

    <script src="https://unpkg.com/alpinejs" defer></script>
    <style>
    body {
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
    }
    .bg-gradient-primary {
        background: linear-gradient(90deg, #159ed5 0%, #0d7ca7 100%);
    }
    .text-gray-800, .dark\:text-gray-200 {
        line-height: 1.5;
    }
    .px-4, .py-2 {
        font-size: 13px;
    }
    .space-y-1 > li, .space-y-1 > a {
        font-size: 13px;
        line-height: 1.4;
    }
    .font-bold {
        font-size: 15px;
    }
    @media only screen and (max-width: 767px) {
        .search-icon {
            display: none !important;
        }
        .search-section button {
            display: none !important;
        }
    }
</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>??</text></svg>">
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">

    <!-- Top bar for Social Media, Contact, and Staff Log-in -->
    <div class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex items-center space-x-4">
                <span><i class="bi bi-telephone-fill"></i> <a href="tel:+254709728200" class="hover:text-[#159ed5]">0709728200</a></span>
                <span><i class="bi bi-geo-alt-fill"></i> Kijabe</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="https://www.facebook.com/kijabehospital" target="_blank" class="text-black dark:text-gray-400 hover:text-[#159ed5]"><i class="bi bi-facebook"></i></a>
                <a href="https://x.com/kijabehospital" target="_blank" class="text-black dark:text-gray-400 hover:text-[#159ed5]"><i class="bi bi-twitter"></i></a>
                <a href="https://www.linkedin.com/company/AICKijabeHospital" target="_blank" class="text-black dark:text-gray-400 hover:text-[#159ed5]"><i class="bi bi-linkedin"></i></a>
                <a href="https://api.whatsapp.com/send?phone=254709728200" target="_blank" class="text-black dark:text-gray-400 hover:text-[#159ed5]"><i class="bi bi-whatsapp"></i></a>
                <a href="https://friendsofkijabe.org" target="_blank" class="text-black dark:text-gray-400 hover:text-[#159ed5]"><i class="bi bi-currency-dollar"></i></a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav x-data="{ open: false, aboutMenu: false, servicesMenu: false, educationMenu: false, linksMenu: false }" class="bg-gradient-primary border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="https://kijabehospital.or.ke/images/logo.png" alt="Kijabe Hospital Logo" class="h-14 w-auto">
                    </a>
                </div>


                <!-- Mobile Menu Button -->
                <div class="flex sm:hidden">
                    <button @click="open = !open" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                
            </div>
        </div>

        <!-- Mobile Menu -->
<div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-gradient-primary text-white">
    <div class="pt-2 pb-3 space-y-1 text-center">
        <a href="{{ route('home') }}"
            class="block px-4 py-2 font-medium text-white hover:bg-[#1280b2]">Home</a>

     


    </nav>

    <!-- Search Overlay -->
    <div id="searchOverlay" class="fixed inset-0 bg-white dark:bg-gray-900 bg-opacity-95 flex items-center justify-center z-50 hidden">
        <div class="w-full max-w-md px-4">
            <div class="relative">
                <form action="{{ route('diseases.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="query" placeholder="Search diseases..."
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-full p-4 pl-12 text-lg bg-white dark:bg-gray-800 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500 text-gray-800 dark:text-gray-200">
                    <button type="submit" class="absolute left-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
                <button id="closeSearch" class="absolute top-0 right-0 mt-3 mr-3 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Include JavaScript code before closing body tag -->
    <script>
        // Get the elements
        const searchButton = document.getElementById('searchButton');
        const searchOverlay = document.getElementById('searchOverlay');
        const closeSearch = document.getElementById('closeSearch');

        // Function to open the search overlay
        function openSearchOverlay() {
            searchOverlay.classList.remove('hidden');
        }

        // Function to close the search overlay
        function closeSearchOverlay() {
            searchOverlay.classList.add('hidden');
        }

        // Event listeners
        searchButton.addEventListener('click', openSearchOverlay);
        closeSearch.addEventListener('click', closeSearchOverlay);

        // Close overlay when clicking outside the search form
        searchOverlay.addEventListener('click', function (e) {
            if (e.target === searchOverlay) {
                closeSearchOverlay();
            }
        });

        // Close overlay with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSearchOverlay();
            }
        });

        // Dark mode toggle
        function setDarkMode(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            }
        }

        // Check if dark mode should be applied
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            setDarkMode(true);
        } else {
            setDarkMode(false);
        }
    </script>
</body>
</html>
