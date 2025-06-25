<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/17422/17422247.png" type="image/png">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- FullCalendar Styles -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css' rel='stylesheet' />
    <!-- FullCalendar Script -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js'></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sidebar-text, .sidebar a, .btn {
            transition: all 0.3s ease;
        }
        .sidebar-text:hover, .sidebar a:hover {
            background-color: #117fb3; /* Slightly darker shade for hover */
        }
        .theme-color {
            background-color: #159ed5;
            color: white;
        }
        .theme-color:hover {
            background-color: #117fb3;
        }
    </style>
</head>
<body>

<div class="flex">
    <!-- Sidebar -->
    <aside class="theme-color w-54 min-h-screen p-5 flex flex-col shadow-lg">
        <div class="flex flex-col items-center mb-8">
            <img src="https://cdn-icons-png.flaticon.com/128/17034/17034419.png" alt="Admin" class="w-24 h-24 rounded-full mb-2 mt-3">
            <h2 class="text-lg font-bold" style="font-family: 'Roboto', sans-serif; font-size: 1.25rem;">@livewire('get-doctor-name')</h2>
            <hr class="w-full border-t border-blue-300 my-2">
        </div>

        <nav class="mt-5 flex flex-col space-y-4">
            <a href="{{ url('/Board') }}" class="flex items-center p-2 rounded-lg sidebar-text">
                <i class="fa-brands fa-windows mr-2"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center p-2 rounded-lg sidebar-text">
                <i class="fas fa-money-check-alt mr-2"></i>
                <span>Payment</span>
            </a>
	
            <a href="{{ url('/create-meeting') }}" @click.prevent="openModal = true" class="flex items-center p-2 rounded-lg sidebar-text">
                <i class="fas fa-video mr-2"></i>
                <span>Create Meeting</span>
            </a>
            <a href="{{ route('superadmin.user_management') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 ease-in-out">
    		<i class="fas fa-users-cog mr-2"></i>
    		<span>User Management</span>
	    </a>

            <form action="{{ route('logout') }}" method="POST" class="flex items-center p-2 rounded-lg sidebar-text">
                @csrf
                <button type="submit" class="w-full text-left">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
        <!-- Header/Navbar -->
        <div class="theme-color p-3 m-1 shadow flex justify-between items-center">
            <p class="text-white">Dashboard</p>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <div class="relative">
                    <i class="fas fa-bell text-white"></i>
                </div>
                <div class="relative">
                    @livewire('search')
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-5 bg-gray-100">
            @yield('content')
            @yield('livewire')
        </div>
    </div>
</div>

@yield('scripts') <!-- This will include any additional scripts -->

</body>
</html>
