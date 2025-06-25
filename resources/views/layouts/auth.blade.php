<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/128/4727/4727424.png" type="image/x-icon">

    <style>
        .brand-color {
            background-color: #159ed5;
        }

        .brand-hover:hover {
            background-color: #1e4727;
        }

        .brand-footer {
            background-color: #1593d5;
            color: white;
        }

        .icon-color {
            color: #ffffff;
        }

        /* Make the body a flex container */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure full height */
        }

        /* Make the main section grow */
        main {
            flex: 1; /* Take remaining space */
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <header class="brand-color shadow-lg">
        <div class="container mx-auto p-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-19 lg:h-10 lg:w-15">
                <h1 class="text-base lg:text-lg font-bold text-white">Telemed</h1>
            </div>

            <!-- Navigation for desktop -->
            <nav id="menu" class="hidden lg:flex lg:space-x-4">
                <a href="{{ route('dashboard') }}" class="text-white text-sm lg:text-base font-semibold px-3 py-1 rounded-lg brand-hover transition duration-300">
                    <i class="fas fa-home icon-color text-sm"></i> Dashboard
                </a>
                <a href="{{ route('account.index') }}" class="text-white text-sm lg:text-base font-semibold px-3 py-1 rounded-lg brand-hover transition duration-300">
                    <i class="fas fa-user icon-color text-sm"></i> Profile
                </a>
                <a href="{{ route('reports.show') }}" class="text-white text-sm lg:text-base font-semibold px-3 py-1 rounded-lg brand-hover transition duration-300">
                    <i class="fas fa-file-alt icon-color text-sm"></i> Reports
                </a>
                <a href="{{ route('uploads.show') }}" class="text-white text-sm lg:text-base font-semibold px-3 py-1 rounded-lg brand-hover transition duration-300">
                    <i class="fas fa-upload icon-color text-sm"></i> Uploads
                </a>
                <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt mr-1 text-white"></i>
                        <span class="text-white">Logout</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Mobile Navigation Menu -->
        <nav id="mobile-menu" class="lg:hidden hidden flex-col space-y-2 px-4 pt-4 pb-2 bg-white shadow-lg no-scrollbar overflow-auto">
            <a href="{{ route('dashboard') }}" class="text-gray-800 text-lg px-4 py-2 rounded-lg hover:bg-gray-100">Dashboard</a>
            <a href="{{ route('patient.index') }}" class="text-gray-800 text-lg px-4 py-2 rounded-lg hover:bg-gray-100">Profile</a>
            <a href="" class="text-gray-800 text-lg px-4 py-2 rounded-lg hover:bg-gray-100">Uploads</a>
            <a href="" class="text-gray-800 text-lg px-4 py-2 rounded-lg hover:bg-gray-100">Downloads</a>
            <a href="{{ route('logout') }}" class="text-gray-800 text-lg px-4 py-2 rounded-lg hover:bg-gray-100"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                Logout
            </a>
        </nav>

        <!-- Hidden Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </header>

    <main class="container mx-auto py-6 px-4">
        @yield('content')
    </main>

    <footer class="brand-footer mt-6 text-white text-center py-4">
        <p>&copy; {{ date('Y') }} AIC Kijabe Hospital Telemed MIS. All rights reserved.</p>
    </footer>

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>

</html>

