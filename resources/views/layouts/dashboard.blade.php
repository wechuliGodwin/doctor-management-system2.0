<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DMS - @yield('title')</title>
    <!-- Preload Critical Resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow-x: hidden;
            position: relative;
        }

        .navbar {
            background: linear-gradient(90deg, #159ed5 0%, #159ed5 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 0;
            left: 220px;
            width: calc(100% - 220px);
            z-index: 1000;
            transition: left 0.3s ease, width 0.3s ease;
            padding: 0.5rem 1rem;
            min-height: 50px;
        }

        .navbar.hidden-sidebar {
            left: 0;
            width: 100%;
        }

        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            padding: 6px 12px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #e0e0e0 !important;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            right: 100%;
            /* Show on the left instead of left: 100% */
            left: auto;
            margin-top: -1px;
            display: none;
        }

        .dropdown-submenu:hover .dropdown-menu {
            display: block;
        }

        /* Ensure dropdown menu is visible on click */
        .dropdown-submenu .dropdown-menu.show {
            display: block;
        }

        /* Responsive for mobile – keep same */
        @media (max-width: 768px) {
            .dropdown-submenu .dropdown-menu {
                left: 0;
                position: relative;
                margin-left: 20px;
            }
        }


        .sidebar {
            width: 220px;
            height: 100vh;
            background: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            z-index: 900;
            overflow-y: auto;
            padding: 20px 10px;
        }

        .sidebar.hidden {
            transform: translateX(-220px);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #159ed5;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f4f7fa;
        }

        .sidebar .sidebar-header {
            padding: 20px 15px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #159ed5;
            position: sticky;
            top: 0;
            z-index: 1;
            background: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }

        .sidebar ul {
            padding: 10px 0;
            margin: 0;
        }

        .sidebar a {
            color: #333;
            padding: 12px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 0 25px 25px 0;
        }

        .sidebar a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            color: #159ed5;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            background: linear-gradient(to right, #e6eef5, #f0f5fa);
            color: #159ed5;
            margin-right: 5px;
        }

        .sidebar a:hover i {
            color: #159ed5;
            transform: scale(1.1);
        }

        .sidebar a.active {
            background: linear-gradient(to right, rgb(127, 207, 238), rgb(115, 196, 233));
            color: #ffffff;
            margin-right: 5px;
            font-weight: 600;
        }

        .sidebar a.active i {
            color: #ffffff;
        }

        .has-submenu .submenu-toggle {
            cursor: pointer;
            display: flex;
            align-items: center;
            padding-right: 10px;
        }

        .has-submenu .menu-text {
            flex-grow: 1;
        }

        .submenu-indicator {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
            color: #159ed5;
        }

        .has-submenu .submenu-toggle.active .submenu-indicator {
            transform: rotate(180deg);
            color: #ffffff;
        }

        .submenu {
            display: none;
            list-style: none;
            padding-left: 30px;
            margin: 5px 0;
            background: #f9fafb;
            border-left: 2px solid #e0e0e0;
        }

        .submenu.show {
            display: block;
        }

        .submenu li a {
            padding: 8px 15px;
            color: #495057;
            font-size: 0.9rem;
            font-weight: 400;
            transition: all 0.3s ease;
            background: none;
            border-radius: 5px;
        }

        .submenu li a i {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .submenu li a:hover,
        .submenu li a.active {
            color: #159ed5;
            background: #e9ecef;
            padding-left: 20px;
        }

        .submenu li a:hover i,
        .submenu li a.active i {
            color: #159ed5;
            transform: none;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            padding-top: 30px;
            min-height: 100vh;
            background: #f4f7fa;
            transition: margin-left 0.3s ease;
        }

        .main-content.hidden-sidebar {
            margin-left: 0;
        }

        .alert-panel {
            margin-top: 70px;
            margin-left: 250px;
            padding-top: 20px;
            margin-right: 20px;
            margin-bottom: 0px;
            transition: margin-left 0.3s ease;
        }

        .alert-panel.hidden-sidebar {
            margin-left: 0;
        }

        .widget {
            background: #ffffff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .widget h3 {
            margin-bottom: 15px;
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .widget p {
            font-size: 1.6rem;
            font-weight: 500;
            color: #159ed5;
            margin: 0;
        }

        .footer {
            background: #ffffff;
            color: #555;
            text-align: center;
            padding: 20px 0;
            width: calc(100% - 220px);
            margin-left: 220px;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.08);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .footer.hidden-sidebar {
            margin-left: 0;
            width: 100%;
        }

        .sidebar-toggle-btn {
            position: fixed;
            top: 15px;
            left: 235px;
            /* Adjust for larger screens */
            z-index: 1100;
            background: #159ed5;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: left 0.3s ease, background 0.3s ease;
        }

        .sidebar-toggle-btn.hidden {
            left: 15px;
        }

        .sidebar-toggle-btn:hover {
            background: #1278a8;
        }

        /* Loading Indicator Styles */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.2s ease, visibility 0s linear 0.2s;
        }

        .loader-overlay.active {
            visibility: visible;
            opacity: 1;
            transition: opacity 0.2s ease, visibility 0s linear 0s;
        }

        .loader {
            width: 100px;
            height: 25px;
            position: relative;
            overflow: hidden;
        }

        .loader svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 200%;
            height: 100%;
            animation: moveWave 1.5s linear infinite;
        }

        .loader svg path {
            stroke: #159ed5;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .loader .dot {
            position: absolute;
            top: 50%;
            right: 0;
            width: 5px;
            height: 5px;
            background: #159ed5;
            border-radius: 50%;
            transform: translateY(-50%);
            animation: moveDot 1.5s linear infinite;
        }

        @keyframes moveWave {
            0% {
                transform: translateX(-50%);
            }

            100% {
                transform: translateX(0%);
            }
        }

        @keyframes moveDot {
            0% {
                transform: translateX(-100px) translateY(-50%);
            }

            100% {
                transform: translateX(0) translateY(-50%);
            }
        }

        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
                /* Full width for mobile sidebar when open */
                transform: translateX(-220px);
                /* Initially hidden */
                box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
            }

            .sidebar.show {
                transform: translateX(0);
                /* Show sidebar */
            }

            .sidebar .sidebar-header {
                font-size: 1.8rem;
                /* Keep larger font for header */
                padding: 20px 15px;
            }

            .sidebar a span,
            .sidebar .submenu-indicator {
                display: inline;
                /* Ensure text and indicators are visible on mobile sidebar */
            }

            .sidebar a i {
                margin-right: 12px;
                /* Restore margin for icons */
            }

            .submenu {
                display: none;
                /* Keep hidden by default */
                padding-left: 30px;
                /* Indent submenus */
            }

            .submenu.show {
                display: block !important;
                /* Allow submenus to show */
            }

            .main-content {
                margin-left: 0;
                /* No margin on mobile */
                width: 100%;
                /* Full width */
                padding-top: 60px;
                /* Adjust for fixed navbar */
            }

            .main-content.hidden-sidebar,
            .footer.hidden-sidebar {
                margin-left: 0;
                width: 100%;
            }

            .navbar {
                left: 0;
                width: 100%;
            }

            .navbar.hidden-sidebar {
                left: 0;
                width: 100%;
            }

            .sidebar-toggle-btn {
                left: 15px;
                /* Position button at top-left */
                top: 15px;
                z-index: 1001;
                /* Above navbar for easy access */
            }

            .sidebar-toggle-btn.hidden {
                left: 15px;
            }

            .alert-panel {
                margin-left: 20px;
                /* Adjust for full-width content */
                margin-right: 20px;
            }

            .loader {
                width: 80px;
                height: 20px;
            }

            .loader .dot {
                width: 4px;
                height: 4px;
            }

            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 899;
                /* Below sidebar, above main content */
                display: none;
            }

            body.no-scroll {
                overflow: hidden;
            }
        }

        .table-responsive {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .table-responsive.loaded {
            visibility: visible;
            opacity: 1;
        }

        .stylish-alert-btn {
            background-color: #159ed5;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stylish-alert-btn:hover {
            background-color: #117aa2;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(21, 158, 213, 0.5);
        }

        .stylish-alert-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(21, 158, 213, 0.3);
        }
    </style>
    <script>
        // Show loader immediately on page load
        (function() {
            console.log('Loader: Initializing');
            const loaderOverlay = document.getElementById('loaderOverlay');
            if (loaderOverlay) {
                loaderOverlay.classList.add('active');
                sessionStorage.setItem('loaderActive', 'true');
            }
        })();
    </script>
</head>

<body>
    <!-- Loading Indicator -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader">
            <svg viewBox="0 0 200 25">
                <path
                    d="M0 12.5 H33.33 L37.5 4.17 L41.67 20.83 L45.83 12.5 H66.67 L70.83 8.33 L75 16.67 L79.17 12.5 H100 L104.17 4.17 L108.33 20.83 L112.5 12.5 H133.33 L137.5 8.33 L141.67 16.67 L145.83 12.5 H166.67 L170.83 4.17 L175 20.83 L179.17 12.5 H200" />
            </svg>
            <div class="dot"></div>
        </div>
    </div>

    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle-btn" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse " id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth('booking')
                    <!-- Display User Name and Branch -->
                    <li class="nav-item me-3">
                        <span class="navbar-text text-white">
                            {{ Auth::guard('booking')->user()->full_name }}
                            ({{ ucfirst(Auth::guard('booking')->user()->hospital_branch) }})
                        </span>
                    </li>
                    <!-- Settings Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item"
                                    href="{{ route('booking.password.request', Auth::guard('booking')->user()->id) }}">Change
                                    Password</a>
                            </li>
                            @if(is_array(Auth::guard('booking')->user()->switchable_branches) && count(Auth::guard('booking')->user()->switchable_branches) > 1)
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">Switch Branch</a>
                                <ul class="dropdown-menu">
                                    @foreach(Auth::guard('booking')->user()->switchable_branches as $branch)
                                    <li>
                                        <form action="{{ route('booking.switch-branch') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="branch" value="{{ $branch }}">
                                            <button type="submit" class="dropdown-item"
                                                {{ session('selected_branch', Auth::guard('booking')->user()->hospital_branch) === $branch ? 'disabled' : '' }}>
                                                {{ ucfirst($branch) }}
                                            </button>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                            <li>
                                <form action="{{ route('booking.logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <!-- Show login link if not authenticated -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('booking.login') }}">Login</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    @if(isset($alertsCount) && is_numeric($alertsCount) && $alertsCount > 0)
    <h5 class="alert-panel alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        You have {{ $alertsCount }} important {{ $alertsCount == 1 ? 'alert' : 'alerts' }},
        click <a href="{{ route('booking.alerts') }}" class="text-primary text-decoration-underline">here</a> to view.
    </h5>
@endif
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">DMS</div>
        <ul class="list-unstyled">
            <li><a href="{{ route('booking.dashboard') }}"><i
                        class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="menu-text">Appointments</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('booking.add') }}" class="submenu-item">
                            <i class="fas fa-calendar-plus me-2"></i>
                            <span class="menu-text">Add Appointment</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.dashboard', 'new') }}" class="submenu-item">
                            <i class="fas fa-stethoscope"></i>
                            <span class="menu-text">New</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.dashboard', 'review') }}" class="submenu-item">
                            <i class="fas fa-sync-alt me-2"></i>
                            <span class="menu-text">Review</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.dashboard', 'postop') }}" class="submenu-item">
                            <i class="fas fa-procedures me-2"></i>
                            <span class="menu-text">Post-Ops</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.dashboard', 'rescheduled') }}" class="submenu-item">
                            <i class="fas fa-clock me-2"></i>
                            <span class="menu-text">Rescheduled</span>
                        </a>
                    </li>
                    @php
                    $userBranch = Auth::guard('booking')->user()->hospital_branch;
                    @endphp
                    @if ($userBranch === 'kijabe')
                    <li>
                        <a href="{{ route('booking.dashboard', 'external_pending') }}" class="submenu-item">
                            <i class="fas fa-hourglass-half me-2"></i>
                            <span class="menu-text">External Pending Approval</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.dashboard', 'external_approved') }}" class="submenu-item">
                            <i class="fas fa-check-circle me-2"></i>
                            <span class="menu-text">External Approved</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('booking.dashboard', 'cancelled') }}" class="submenu-item">
                            <i class="fas fa-times-circle me-2"></i>
                            <span class="menu-text">Cancelled</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.dashboard', 'all') }}" class="submenu-item">
                            <i class="fas fa-list me-2"></i>
                            <span class="menu-text">All</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="#" class="submenu-item">
                            <i class="fa-solid fa-user-check me-2"></i>
                            <span class="menu-text">Smart Schedule</span>
                        </a>
                    </li> -->
                </ul>
            </li>
            @if(Auth::guard('booking')->check() && Auth::guard('booking')->user()->role === 'superadmin')
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-hospital-alt"></i>
                    <span class="menu-text">Satelites</span>
                    <i class="fas fa-chevron-down submenu-indicator"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('booking.branch', 'kijabe') }}" class="submenu-item">
                            <i class="fas fa-hospital"></i>
                            <span class="menu-text">Kijabe Main</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.branch', 'westlands') }}" class="submenu-item">
                            <i class="fas fa-city"></i>
                            <span class="menu-text">Westlands Clinic</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.branch', 'naivasha') }}" class="submenu-item">
                            <i class="fas fa-building"></i>
                            <span class="menu-text">Naivasha Clinic</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('booking.branch', 'marira') }}" class="submenu-item">
                            <i class="fas fa-clinic-medical"></i>
                            <span class="menu-text">Marira Clinic</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-bell"></i>
                    <span class="menu-text">Alerts</span>
                    <i class="fas fa-chevron-down submenu-indicator"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a class="{{ Request::is('booking/alerts*') ? 'active' : '' }}"
                            href="{{ route('booking.alerts') }}">
                            <i class="fas fa-bell me-2"></i> Active
                        </a>
                    </li>
                    <li>
                        <a class="{{ Request::is('booking/resolved-alerts*') ? 'active' : '' }}"
                            href="{{ route('booking.resolved_alerts') }}">
                            <i class="fas fa-check-circle"></i> Resolved
                        </a>
                    </li>
                    <li class="has_submenu">
                        <a href="javascript:void(0)" class="submenu-toggle">
                            <i class="fas fa-bell"></i>
                            <span class="menu-text">Reminders</span>
                            <i class="fas fa-chevron-down submenu-indicator"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('booking.reminders') }}"><i class="fas fa-sms"></i> Send Reminder</a></li>
                            <li><a href="{{ route('booking.delivery_log') }}"><i class="fas fa-clipboard-list"></i> Delivery Log</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('booking.doctor.diary') }}">
                    <i class="fas fa-calendar-plus me-2"></i> Doctor's Diary
                </a>
            </li>
            <!-- <li><a href="{{ route('booking.calendar') }}"><i
                        class="fas fa-calendar-plus me-2"></i><span>Calendar</span></a></li> -->
            <!-- 
            <li><a class="{{ Request::is('booking/reminders*') ? 'active' : '' }}"
                    href="{{ route('booking.reminders') }}">
                    <i class="fas fa-bell me-2"></i> Reminders
                    @if(isset($reminderCount) && $reminderCount > 0)
                        <span class="badge rounded-pill ms-2" id="reminderAlert"
                            style="background-color: #e53935; color: white;">
                            {{ $reminderCount }}
                        </span>
                    @endif
                </a></li> -->
            <li><a href="{{ route('booking.search') }}"><i class="fas fa-search"></i> <span>Search</span></a></li>
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Reports</span>
                    <i class="fas fa-chevron-down submenu-indicator"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('booking.reports') }}"><i class="fas fa-file-alt"></i> <span>Summary Report</span></a></li>
                    <li><a href="{{ route('booking.detailed-report') }}"><i class="fas fa-file-alt"></i> <span>Detailed Report</span></a></li>
                    <!-- <li><a href="{{ route('booking.detailed-report') }}"><i class="fas fa-file-alt"></i> <span>HMIS Report</span></a></li> -->
                </ul>
            </li>

            <li><a href="{{ route('booking.specialization.limits') }}"><i class="fas fa-chart-line"></i> <span>View Limits</span></a></li>

            @if (Auth::guard('booking')->check() && in_array(Auth::guard('booking')->user()->role, ['admin', 'superadmin']))
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">Account</span>
                    <i class="fas fa-chevron-down submenu-indicator"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('booking.auth.users.index') }}" class="submenu-item">
                            <i class="fas fa-user"></i>
                            <span class="menu-text">User Management</span> </a>
                    </li>

                    <li class="nav-item">
                        <a class="submenu-item {{ request()->routeIs('booking.auth.doctors.*') ? 'active' : '' }}"
                            href="{{ route('booking.auth.doctors.index') }}">
                            <i class="fas fa-user-md me-2"></i>
                            Doctor Management
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    <div class="col-12 text-center footer mt-3">
        <p style="font-family: 'Roboto', sans-serif; color: #555; font-size: 0.9rem;">
            <strong>KH DMS v2.1 © Kijabe Devops</strong>
        </p>
    </div>

    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Loader: DOMContentLoaded');

            // Submenu toggle functionality
            const toggles = document.querySelectorAll('.submenu-toggle');
            toggles.forEach(toggle => {
                const submenu = toggle.nextElementSibling;
                const submenuItems = submenu.querySelectorAll('.submenu-item');
                const submenuId = toggle.getAttribute('data-submenu-id') || 'default';
                const isSubmenuOpen = localStorage.getItem(`submenuOpen_${submenuId}`) === 'true';
                if (isSubmenuOpen) {
                    submenu.classList.add('show');
                    toggle.classList.add('active');
                }
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isOpen = submenu.classList.contains('show');
                    submenu.classList.toggle('show');
                    toggle.classList.toggle('active');
                    localStorage.setItem(`submenuOpen_${submenuId}`, !isOpen);
                });
            });

            // Mobile Sidebar Toggle
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggle');
            const mainContent = document.querySelector('.main-content');
            const navbar = document.querySelector('.navbar');
            const footer = document.querySelector('.footer');
            const alertPanel = document.querySelector('.alert-panel');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            sidebarToggleBtn.addEventListener('click', function() {
                const isHidden = sidebar.classList.contains('hidden');
                const isMobile = window.innerWidth <= 768;

                if (isMobile) {
                    sidebar.classList.toggle('show'); // Use 'show' for mobile
                    sidebarOverlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
                    body.classList.toggle('no-scroll');
                } else {
                    sidebar.classList.toggle('hidden');
                    mainContent.classList.toggle('hidden-sidebar');
                    navbar.classList.toggle('hidden-sidebar');
                    footer.classList.toggle('hidden-sidebar');
                    if (alertPanel) {
                        alertPanel.classList.toggle('hidden-sidebar');
                    }
                    // Save sidebar state to localStorage
                    localStorage.setItem('sidebarHidden', sidebar.classList.contains('hidden'));
                }
            });

            // Close sidebar when clicking outside on mobile
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.style.display = 'none';
                body.classList.remove('no-scroll');
            });

            // On initial load, apply saved sidebar state for desktop
            if (window.innerWidth > 768) {
                const savedSidebarState = localStorage.getItem('sidebarHidden');
                if (savedSidebarState === 'true') {
                    sidebar.classList.add('hidden');
                    mainContent.classList.add('hidden-sidebar');
                    navbar.classList.add('hidden-sidebar');
                    footer.classList.add('hidden-sidebar');
                    if (alertPanel) {
                        alertPanel.classList.add('hidden-sidebar');
                    }
                }
            }

            // Hide loader after all assets are loaded and parsed
            window.addEventListener('load', function() {
                console.log('Loader: Window loaded');
                const loaderOverlay = document.getElementById('loaderOverlay');
                const tableResponsive = document.querySelector('.table-responsive');
                if (loaderOverlay) {
                    loaderOverlay.classList.remove('active');
                    sessionStorage.removeItem('loaderActive');
                }
                if (tableResponsive) {
                    tableResponsive.classList.add('loaded');
                }
            });

            // Restore active state on page load (only if the user has interacted)
            if (!isInitialLoad) {
                const activeItem = localStorage.getItem('activeSidebarItem');
                if (activeItem) {
                    const activeLink = document.querySelector(`.sidebar a[href="${activeItem}"]`);
                    if (activeLink) {
                        sidebarLinks.forEach(l => l.classList.remove('active'));
                        activeLink.classList.add('active');
                    }
                }
            }

            // Loading indicator functionality
            const loaderOverlay = document.getElementById('loaderOverlay');
            const tableResponsive = document.querySelector('.table-responsive');

            // Hide loader when DOM is ready
            console.log('Loader: Hiding');
            loaderOverlay.classList.remove('active');
            sessionStorage.removeItem('loaderActive');
            if (tableResponsive) {
                tableResponsive.classList.add('loaded');
            }

            // Show loader for form submissions
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    console.log('Loader: Showing for form submission');
                    loaderOverlay.classList.add('active');
                    sessionStorage.setItem('loaderActive', 'true');
                    if (tableResponsive) {
                        tableResponsive.classList.remove('loaded');
                    }
                });
            });
        });

        // Ensure loader stays active during page unload
        window.addEventListener('beforeunload', function() {
            const loaderOverlay = document.getElementById('loaderOverlay');
            if (sessionStorage.getItem('loaderActive') === 'true') {
                console.log('Loader: Persisting on beforeunload');
                loaderOverlay.classList.add('active');
            }
        });
    </script>
    @yield('scripts')
</body>

</html>