<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking System - @yield('title')</title>
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
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 0.75rem 1rem;
            min-height: 60px;
            max-height: 70px;
        }

        .navbar-brand {
            color: #ffffff !important;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-right: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #e0e0e0 !important;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        .dropdown-menu {
            background: #159ed5;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1001;
        }

        .dropdown-item {
            color: #ffffff !important;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: #e0e0e0 !important;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                min-height: 70px;
                max-height: 70px;
            }

            .navbar-brand {
                font-size: 1.2rem;
                max-width: 100px;
                margin-bottom: 0.5rem;
            }

            .sidebar-toggle-btn {
                display: flex;
                position: absolute;
                top: 12px;
                left: 1rem;
                z-index: 1002;
                background: #159ed5;
                color: #ffffff;
                border: none;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            }

            .profile-toggle-btn {
                display: flex;
                position: absolute;
                top: 12px;
                right: 1rem;
                z-index: 1001;
                background: #159ed5;
                color: #ffffff;
                border: none;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            }

            .dropdown-menu {
                position: absolute;
                top: 100%;
                right: 0;
                left: auto;
                width: 200px;
                margin-top: 0.5rem;
            }

            .sidebar .user-info {
                display: flex;
                align-items: center;
                color: #333;
                font-weight: 500;
                font-size: 0.9rem;
                padding: 10px 15px;
                background: #f9fafb;
                margin: 10px 0;
                border-radius: 5px;
            }

            .sidebar .user-info i {
                font-size: 1.2rem;
                margin-right: 0.5rem;
                color: #159ed5;
                background: rgba(21, 158, 213, 0.2);
                padding: 0.3rem;
                border-radius: 50%;
            }
        }

        @media (min-width: 769px) {
            .navbar {
                left: 220px;
                width: calc(100% - 220px);
            }

            .sidebar-toggle-btn,
            .profile-toggle-btn {
                display: none;
            }

            .navbar-nav {
                align-items: center;
            }

            .user-info {
                display: flex;
                align-items: center;
                color: #ffffff;
                font-weight: 500;
                font-size: 0.9rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 500px;
            }

            .user-info i {
                font-size: 1.2rem;
                margin-right: 0.5rem;
                color: #ffffff;
                background: rgba(255, 255, 255, 0.2);
                padding: 0.3rem;
                border-radius: 50%;
            }

            .dropdown-submenu {
                position: relative;
            }

            .dropdown-submenu .dropdown-menu {
                top: 0;
                right: 100%;
                left: auto;
                margin-top: -1px;
            }

            .dropdown-submenu:hover .dropdown-menu {
                display: block;
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
            z-index: 900;
            overflow-y: auto;
            padding: 20px 10px;
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
            padding-top: 80px;
            min-height: 100vh;
            background: #f4f7fa;
        }

        .alert-panel {
            margin-top: 80px;
            margin-left: 250px;
            padding-top: 20px;
            margin-right: 20px;
            margin-bottom: 0px;
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
        }

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

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
                box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar .sidebar-header {
                padding: 1rem;
            }

            .sidebar a span,
            .sidebar .submenu-indicator {
                display: inline;
            }

            .sidebar a i {
                margin-right: 12px;
            }

            .submenu {
                display: none;
                padding-left: 30px;
            }

            .submenu.show {
                display: block !important;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding-top: 80px;
            }

            .alert-panel {
                margin-left: 1rem;
                margin-right: 1rem;
                margin-top: 80px;
            }

            .loader {
                width: 80px;
                height: 20px;
            }

            .loader .dot {
                width: 4px;
                height: 4px;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 899;
                display: none;
            }

            body.no-scroll {
                overflow: hidden;
            }

            .footer {
                margin-left: 0;
                width: 100%;
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

    <!-- Profile Toggle Button (Mobile Only) -->
    @if (Auth::guard('booking')->check())
    <div class="dropdown">
        <button class="profile-toggle-btn" id="profileToggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-ellipsis"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileToggle">
            <li>
                <a class="dropdown-item" href="{{ route('booking.password.request', Auth::guard('booking')->user()->id) }}">
                    Change Password
                </a>
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
    </div>
    @endif

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('booking.dashboard') }}">Booking Management System</a>
            @if (Auth::guard('booking')->check())
            <div class="navbar-nav ms-auto d-none d-md-flex align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::guard('booking')->user()->full_name }}
                        ({{ ucfirst(Auth::guard('booking')->user()->hospital_branch) }})
                        <i class="fas fa-cog ms-2"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('booking.password.request', Auth::guard('booking')->user()->id) }}">
                                Change Password
                            </a>
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
            </div>
            @else
            <div class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('booking.login') }}">Login</a>
                </li>
            </div>
            @endif
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
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="Kijabe logo" class="active">
        </div>
        @if (Auth::guard('booking')->check())
        <div class="user-info d-md-none">
            <i class="fas fa-user-circle"></i>
            {{ Auth::guard('booking')->user()->full_name }}
            ({{ ucfirst(Auth::guard('booking')->user()->hospital_branch) }})
        </div>
        @endif

        <ul class="list-unstyled">
            <li><a href="{{ route('booking.dashboard') }}"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="menu-text">Appointments</span>
                    <i class="fas fa-ellipsis-h"></i>
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
                    $user = Auth::guard('booking')->user();
                    $selectedBranch = session('selected_branch', $user->hospital_branch);
                    @endphp
                    @if ($selectedBranch === 'kijabe')
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
                </ul>
            </li>
            @if(Auth::guard('booking')->check() && Auth::guard('booking')->user()->role === 'superadmin')
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-hospital-alt"></i>
                    <span class="menu-text">Satelites</span>
                    <i class="fas fa-ellipsis-h"></i>
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
                    <i class="fas fa-ellipsis-h"></i>
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
                    @if(Auth::guard('booking')->check() && in_array(Auth::guard('booking')->user()->role, ['superadmin', 'admin']))
                    <li class="has_submenu">
                        <a href="javascript:void(0)" class="submenu-toggle">
                            <i class="fas fa-bell"></i>
                            <span class="menu-text">Reminders</span>
                            <i class="fa fa-sort-desc" aria-hidden="true"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{ route('booking.reminders') }}"><i class="fas fa-sms"></i> Send Reminder</a></li>
                            <li><a href="{{ route('booking.delivery_log') }}"><i class="fas fa-clipboard-list"></i> Delivery Log</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('booking.doctor.diary') }}">
                    <i class="fas fa-calendar-plus me-2"></i> Doctor's Diary
                </a>
            </li>
            <li><a href="{{ route('booking.search') }}"><i class="fas fa-search"></i> <span>Search</span></a></li>
            @if(Auth::guard('booking')->check() && in_array(Auth::guard('booking')->user()->role, ['superadmin', 'admin']))
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Reports</span>
                    <i class="fas fa-ellipsis-h"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('booking.reports') }}"><i class="fas fa-file-alt"></i> <span>Summary Report</span></a></li>
                    <li><a href="{{ route('booking.detailed-report') }}"><i class="fas fa-file-alt"></i> <span>Detailed Report</span></a></li>
                </ul>
            </li>
            @endif
            <li><a href="{{ route('booking.specialization.limits') }}"><i class="fas fa-chart-line"></i> <span>View Limits</span></a></li>
            @if (Auth::guard('booking')->check() && in_array(Auth::guard('booking')->user()->role, ['admin', 'superadmin']))
            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">Account</span>
                    <i class="fas fa-ellipsis-h"></i>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Loader: DOMContentLoaded');

            // Submenu toggle functionality
            const toggles = document.querySelectorAll('.submenu-toggle');
            toggles.forEach(toggle => {
                const submenu = toggle.nextElementSibling;
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
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
                    body.classList.toggle('no-scroll');
                });
            }

            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.style.display = 'none';
                body.classList.remove('no-scroll');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                        const dropdownInstance = bootstrap.Dropdown.getInstance(menu.previousElementSibling);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    });
                }
            });

            // Dropdown submenu toggle for mobile
            document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const submenu = toggle.nextElementSibling;
                    submenu.classList.toggle('show');
                });
            });

            // Hide loader after all assets are loaded
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

            // Restore active state on page load
            const currentPath = window.location.pathname + window.location.search;
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            sidebarLinks.forEach(link => {
                const linkHref = link.getAttribute('href').replace(/\/+$/, '');
                const currentPathClean = currentPath.replace(/\/+$/, '');
                if (linkHref === currentPathClean) {
                    link.classList.add('active');
                    let parentSubmenu = link.closest('.submenu');
                    while (parentSubmenu) {
                        parentSubmenu.classList.add('show');
                        let parentToggle = parentSubmenu.previousElementSibling;
                        if (parentToggle && parentToggle.classList.contains('submenu-toggle')) {
                            parentToggle.classList.add('active');
                            const submenuId = parentToggle.getAttribute('data-submenu-id') || 'default';
                            localStorage.setItem(`submenuOpen_${submenuId}`, 'true');
                        }
                        parentSubmenu = parentSubmenu.parentNode.closest('.submenu');
                    }
                }
            });

            // Loading indicator functionality
            const loaderOverlay = document.getElementById('loaderOverlay');
            const tableResponsive = document.querySelector('.table-responsive');
            console.log('Loader: Hiding');
            if (loaderOverlay) {
                loaderOverlay.classList.remove('active');
                sessionStorage.removeItem('loaderActive');
            }
            if (tableResponsive) {
                tableResponsive.classList.add('loaded');
            }

            const exportForm = document.getElementById('export-form');
            if (exportForm) {
                exportForm.addEventListener('submit', function(e) {
                    // Prevent the default form submission for a moment
                    e.preventDefault();

                    // Show the loader immediately
                    if (loaderOverlay) {
                        loaderOverlay.classList.add('active');
                    }

                    // Manually submit the form to initiate the download
                    this.submit();

                    setTimeout(function() {
                        if (loaderOverlay) {
                            loaderOverlay.classList.remove('active');
                            sessionStorage.removeItem('loaderActive');
                        }
                    }, 2000); // 2-second delay
                });
            }

            window.addEventListener('beforeunload', function() {
                const loaderOverlay = document.getElementById('loaderOverlay');
                if (loaderOverlay && sessionStorage.getItem('loaderActive') === 'true') {
                    console.log('Loader: Persisting on beforeunload');
                    loaderOverlay.classList.add('active');
                }
            });
        });
    </script>
    @yield('scripts')
</body>

</html>