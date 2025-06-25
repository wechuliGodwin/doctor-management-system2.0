<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- FullCalendar Styles -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css' rel='stylesheet' />
    
    <!-- FullCalendar Script -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js'></script>

    <style>
        /* Reduce font sizes */
        body {
            font-size: 0.875rem; /* Reduced base font size */
        }
        .sidebar-text {
            font-size: 0.875rem; /* Sidebar text */
        }
        .sidebar-icon {
            font-size: 1rem; /* Icon size */
        }
    </style>

</head>
<body>

<div class="flex">
    <!-- Sidebar -->
    <aside style="background-color: #159ed5" class="text-white w-48 min-h-screen p-4 flex flex-col shadow-lg">
        <div class="flex flex-col items-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/128/17056/17056399.png" alt="Doctor's Profile Picture" class="w-20 h-20 rounded-full mb-2 mt-2">
            <h2 class="text-sm font-bold text-white sidebar-text" style="font-family: 'Roboto', sans-serif;">@livewire('get-doctor-name')</h2>
            <hr class="w-full border-t border-gray-300 my-2">
        </div>

        <nav class="mt-2 flex flex-col space-y-3 text-sm">
            <a href="{{url('/dash')}}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 sidebar-text">
                <i class="fa-brands fa-windows sidebar-icon mr-2"></i>
                <span>Dashboard</span>
            </a>
            <a href="/appointments" class="flex items-center p-2 rounded-lg hover:bg-gray-700 sidebar-text">
                <i class="fas fa-calendar-check sidebar-icon mr-2"></i>
                <span>Appointments</span>
            </a>
            <a href="{{url('/downloads')}}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 sidebar-text">
                <i class="fas fa-download sidebar-icon mr-2"></i>
                <span>Downloads</span>
            </a>
            
            <a href="{{url('/create-meeting')}}" @click.prevent="openModal = true" class="flex items-center p-2 rounded-lg hover:bg-gray-700 sidebar-text">
                <i class="fas fa-video sidebar-icon mr-2"></i>
                <span>Create Meeting</span>
            </a>
            <a href="{{url('/upload')}}" @click.prevent="openUploadModal = true" class="flex items-center p-2 rounded-lg hover:bg-gray-700 sidebar-text">
                <i class="fas fa-upload sidebar-icon mr-2"></i>
                <span>Upload Files</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="flex items-center  p-2 rounded-lg hover:bg-gray-700 sidebar-text">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt sidebar-icon mr-2"></i>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
    </aside>
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
        <!-- Header/Navbar -->
        <div style="background-color: #159ed5" class="p-2 m-1 shadow flex justify-between items-center">
            <p class="text-white text-sm">Dashboard</p>
            <div class="flex items-center space-x-3">
                <i class="fas fa-envelope text-white text-sm"></i>
                <i class="fas fa-bell text-white text-sm"></i>
                @livewire('search')
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-3 bg-gray-100">
            @yield('content')
            @yield('livewire') 
        </div>
    </div>
</div>

@yield('scripts') <!-- This will include any additional scripts -->

</body>
</html>
