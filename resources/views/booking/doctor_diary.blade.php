@extends('layouts.dashboard')
@section('title', 'Doctor\'s Appointment Diary')

@section('content')
    <div class="container">


        <div class="controls-section">
            <div class="controls-card">
                <div class="controls-grid">
                    <div class="control-group navigation-controls">
                        <label class="control-label">Navigate Month</label>
                        <div class="month-nav">
                            <button id="prevMonthBtn" class="nav-btn">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="current-month" id="currentMonthDisplay">
                                {{ \Carbon\Carbon::parse($currentMonth)->format('F Y') }}
                            </div>
                            <button id="nextMonthBtn" class="nav-btn">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="control-group filter-controls">
                        @if($isSuperadmin && count($hospitalBranches) > 1)
                            <div class="form-group">
                                <label for="branchFilter" class="control-label">
                                    <i class="fas fa-building"></i>
                                    Hospital Branch
                                </label>
                                <select id="branchFilter" class="form-select">
                                    @foreach($hospitalBranches as $branch)
                                        <option value="{{ $branch }}" {{ $selectedBranch === $branch ? 'selected' : '' }}>
                                            {{ ucfirst($branch) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="doctorFilter" class="control-label">
                                <i class="fas fa-user-md"></i>
                                Filter by Doctor
                            </label>
                            <select id="doctorFilter" class="form-select">
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->doctor_name }} ({{ $doctor->department ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="loadingSpinner" class="loading-container" style="display: none;">
            <div class="loading-content">
                <div class="spinner"></div>
                <p class="loading-text">Loading appointments...</p>
            </div>
        </div>

        <div class="calendar-container">
            <div id="monthlyCalendar" class="calendar-grid">
                <div class="day-header">Sunday</div>
                <div class="day-header">Monday</div>
                <div class="day-header">Tuesday</div>
                <div class="day-header">Wednesday</div>
                <div class="day-header">Thursday</div>
                <div class="day-header">Friday</div>
                <div class="day-header">Saturday</div>
                <!-- Calendar days will be populated by JavaScript -->
            </div>
        </div>

        <div id="dailyScheduleContainer" class="daily-schedule-container" style="display: none;">
            <div class="schedule-header">
                <button id="backToMonthlyBtn" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Monthly View
                </button>
                <h2 id="dailyScheduleDate" class="schedule-title">Daily Schedule</h2>
            </div>

            <div id="dailyScheduleContent" class="schedule-content">
                <!-- Daily schedule content will be populated by JavaScript -->
            </div>
        </div>

        <div id="errorMessage" class="error-alert" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="error-text"></span>
        </div>
    </div>

    <style>
        /* Base Styles */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fbff 0%, #ffffff 100%);
            min-height: 100vh;
        }

        /* Header Styles */
        .header {
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #159ed5 0%, #1e88e5 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            box-shadow: 0 8px 32px rgba(21, 158, 213, 0.2);
        }

        .header-content {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* .page-title {
                font-size: 2.5rem;
                font-weight: 700;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .page-title i {
                font-size: 2rem;
            } */

        .header-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Controls Section */
        .controls-section {
            margin-bottom: 2rem;
        }

        .controls-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(21, 158, 213, 0.1);
        }

        .controls-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 2rem;
            align-items: end;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .navigation-controls {
            min-width: 300px;
        }

        .filter-controls {
            display: flex;
            flex-direction: row;
            gap: 1.5rem;
        }

        .control-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .control-label i {
            color: #159ed5;
        }

        /* Month Navigation */
        .month-nav {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 0.5rem;
            border: 2px solid #159ed5;
        }

        .nav-btn {
            background: #159ed5;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1rem;
        }

        .nav-btn:hover {
            background: #1e88e5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(21, 158, 213, 0.3);
        }

        .current-month {
            flex: 1;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: #159ed5;
            padding: 0 1rem;
        }

        /* Form Controls */
        .form-group {
            display: flex;
            flex-direction: column;
            min-width: 250px;
        }

        .form-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            background: white;
            color: #495057;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #159ed5;
            box-shadow: 0 0 0 3px rgba(21, 158, 213, 0.1);
        }

        .form-select:hover {
            border-color: #159ed5;
        }

        /* Loading Styles */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .loading-content {
            text-align: center;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #159ed5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        .loading-text {
            color: #6c757d;
            font-size: 1.1rem;
            margin: 0;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Calendar Container */
        .calendar-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(21, 158, 213, 0.1);
        }

        /* Calendar Grid */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .day-header {
            background: linear-gradient(135deg, #159ed5 0%, #1e88e5 100%);
            color: white;
            font-weight: 600;
            padding: 1rem 0.5rem;
            text-align: center;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .day-cell {
            min-height: 100px;
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
            border-right: 1px solid #e9ecef;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .day-cell:hover {
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
            transform: translateY(-1px);
        }

        .day-cell:nth-child(7n) {
            border-right: none;
        }

        .day-cell.inactive {
            background: #f8f9fa;
            color: #adb5bd;
            cursor: default;
        }

        .day-cell.inactive:hover {
            background: #f8f9fa;
            transform: none;
        }

        .day-cell.today {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffc107;
        }

        .day-cell.selected {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 2px solid #159ed5;
            box-shadow: 0 4px 12px rgba(21, 158, 213, 0.2);
        }

        .date-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            position: absolute;
            top: 0.5rem;
            right: 0.75rem;
            line-height: 1.2;
        }

        .appointment-count {
            position: absolute;
            bottom: 0.5rem;
            left: 0.75rem;
            background: #159ed5;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(21, 158, 213, 0.3);
        }

        .appointment-count.high {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        }

        .appointment-count.very-high {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }

        /* Daily Schedule */
        .daily-schedule-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(21, 158, 213, 0.1);
            overflow: hidden;
        }

        .schedule-header {
            background: linear-gradient(135deg, #159ed5 0%, #1e88e5 100%);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .schedule-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .schedule-content {
            padding: 2rem;
        }

        /* Doctor Cards */
        .doctor-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .doctor-card:hover {
            box-shadow: 0 4px 20px rgba(21, 158, 213, 0.1);
            border-color: #159ed5;
        }

        .doctor-card-summary {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-bottom: 1px solid #e9ecef;
        }

        .doctor-card-summary:hover {
            background: #f8f9fa;
        }

        .doctor-card-summary span:first-child {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .toggle-icon {
            color: #159ed5;
            font-size: 1.2rem;
            transition: transform 0.2s ease;
        }

        .doctor-appointments {
            padding: 1.5rem;
            background: white;
            display: none;
        }

        .doctor-appointments ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .doctor-appointments li {
            padding: 1rem 0;
            border-bottom: 1px solid #f1f3f4;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .doctor-appointments li:last-child {
            border-bottom: none;
        }

        .appointment-time {
            font-weight: 700;
            color: #159ed5;
            min-width: 100px;
            font-size: 0.95rem;
        }

        .appointment-details {
            flex: 1;
            color: #495057;
            font-size: 0.95rem;
        }

        .appointment-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .reminder-status {
            padding: 0.75rem 1.5rem;
            background: #e3f2fd;
            color: #1565c0;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Error Alert */
        .error-alert {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .error-text {
            font-weight: 500;
        }

        /* Info Alert */
        .alert-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #159ed5;
            color: #1565c0;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
        }

        .alert-info h4 {
            color: #159ed5;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .controls-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .filter-controls {
                flex-direction: column;
                gap: 1rem;
            }

            .navigation-controls {
                min-width: auto;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .controls-card {
                padding: 1rem;
            }

            .form-group {
                min-width: auto;
            }

            .day-cell {
                min-height: 80px;
                padding: 0.5rem;
            }

            .date-number {
                font-size: 0.95rem;
            }

            .appointment-count {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .schedule-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .doctor-appointments li {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .appointment-time {
                min-width: auto;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.5rem;
                flex-direction: column;
                text-align: center;
            }

            .day-cell {
                min-height: 60px;
                padding: 0.25rem;
            }

            .date-number {
                font-size: 0.85rem;
                top: 0.25rem;
                right: 0.5rem;
            }

            .appointment-count {
                bottom: 0.25rem;
                left: 0.5rem;
                font-size: 0.6rem;
            }
        }
    </style>

    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Include Moment.js for date manipulation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthlyCalendar = document.getElementById('monthlyCalendar');
            const dailyScheduleContainer = document.getElementById('dailyScheduleContainer');
            const dailyScheduleContent = document.getElementById('dailyScheduleContent');
            const backToMonthlyBtn = document.getElementById('backToMonthlyBtn');
            const doctorFilter = document.getElementById('doctorFilter');
            const branchFilter = document.getElementById('branchFilter');
            const prevMonthBtn = document.getElementById('prevMonthBtn');
            const nextMonthBtn = document.getElementById('nextMonthBtn');
            const currentMonthDisplay = document.getElementById('currentMonthDisplay');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const errorMessage = document.getElementById('errorMessage');

            let currentMonth = '{{ $currentMonth }}';
            let currentDoctorId = '';
            let currentBranch = '{{ $selectedBranch }}';
            let calendarData = {};

            // Initialize calendar
            loadCalendarData();

            // Event listeners
            prevMonthBtn.addEventListener('click', () => {
                currentMonth = moment(currentMonth).subtract(1, 'month').format('YYYY-MM');
                updateMonthDisplay();
                loadCalendarData();
            });

            nextMonthBtn.addEventListener('click', () => {
                currentMonth = moment(currentMonth).add(1, 'month').format('YYYY-MM');
                updateMonthDisplay();
                loadCalendarData();
            });

            doctorFilter.addEventListener('change', (e) => {
                currentDoctorId = e.target.value;
                loadCalendarData();
            });

            if (branchFilter) {
                branchFilter.addEventListener('change', (e) => {
                    currentBranch = e.target.value;
                    loadCalendarData();
                });
            }

            backToMonthlyBtn.addEventListener('click', () => {
                showMonthlyCalendar();
            });

            function updateMonthDisplay() {
                currentMonthDisplay.textContent = moment(currentMonth).format('MMMM YYYY');
            }

            function showLoading() {
                loadingSpinner.style.display = 'block';
                errorMessage.style.display = 'none';
            }

            function hideLoading() {
                loadingSpinner.style.display = 'none';
            }

            function showError(message) {
                const errorText = errorMessage.querySelector('.error-text');
                if (errorText) {
                    errorText.textContent = message;
                } else {
                    errorMessage.innerHTML = `<i class="fas fa-exclamation-triangle"></i><span class="error-text">${message}</span>`;
                }
                errorMessage.style.display = 'flex';
                hideLoading();
            }

            function loadCalendarData() {
                showLoading();

                const params = new URLSearchParams({
                    ajax: '1',
                    month: currentMonth
                });

                if (currentDoctorId) {
                    params.append('doctor_id', currentDoctorId);
                }

                if (currentBranch) {
                    params.append('branch', currentBranch);
                }

                fetch(`{{ route('booking.doctor.diary') }}?${params}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response content-type:', response.headers.get('content-type'));

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                console.error('Non-JSON response:', text);
                                throw new Error('Server returned HTML instead of JSON. Check server logs.');
                            });
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Calendar data received:', data);

                        if (data.success === false) {
                            throw new Error(data.error || 'Server returned error');
                        }

                        calendarData = data.calendar_data || {};
                        renderCalendar();
                        hideLoading();
                    })
                    .catch(error => {
                        console.error('Error loading calendar data:', error);
                        showError('Failed to load calendar data: ' + error.message);
                    });
            }

            function loadDailySchedule(date) {
                showLoading();

                const params = new URLSearchParams({
                    ajax: '1',
                    date: date
                });

                if (currentDoctorId) {
                    params.append('doctor_id', currentDoctorId);
                }

                if (currentBranch) {
                    params.append('branch', currentBranch);
                }

                fetch(`{{ route('booking.doctor.diary') }}?${params}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                    .then(response => {
                        console.log('Daily schedule response status:', response.status);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                console.error('Non-JSON response:', text);
                                throw new Error('Server returned HTML instead of JSON. Check server logs.');
                            });
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Daily schedule data received:', data);

                        if (data.success === false) {
                            throw new Error(data.error || 'Server returned error');
                        }

                        renderDailySchedule(data);
                        showDailySchedule(date);
                        hideLoading();
                    })
                    .catch(error => {
                        console.error('Error loading daily schedule:', error);
                        showError('Failed to load daily schedule: ' + error.message);
                    });
            }

            function renderCalendar() {
                // Clear existing calendar days (keep headers)
                const existingDays = monthlyCalendar.querySelectorAll('.day-cell');
                existingDays.forEach(day => day.remove());

                const startOfMonth = moment(currentMonth).startOf('month');
                const endOfMonth = moment(currentMonth).endOf('month');
                const startOfCalendar = startOfMonth.clone().startOf('week');
                const endOfCalendar = endOfMonth.clone().endOf('week');

                const today = moment().format('YYYY-MM-DD');
                let current = startOfCalendar.clone();

                while (current.isSameOrBefore(endOfCalendar, 'day')) {
                    const dayCell = document.createElement('div');
                    dayCell.className = 'day-cell';

                    const dateString = current.format('YYYY-MM-DD');
                    const dayNumber = current.format('D');
                    const isCurrentMonth = current.isSame(startOfMonth, 'month');
                    const isToday = current.format('YYYY-MM-DD') === today;

                    if (!isCurrentMonth) {
                        dayCell.classList.add('inactive');
                    }

                    if (isToday) {
                        dayCell.classList.add('today');
                    }

                    dayCell.innerHTML = `
                                <span class="date-number">${dayNumber}</span>
                                ${isCurrentMonth && calendarData[dateString] ?
                            `<span class="appointment-count ${getCountClass(calendarData[dateString])}">
                                        ${calendarData[dateString]} appt${calendarData[dateString] !== 1 ? 's' : ''}
                                    </span>` : ''}
                            `;

                    if (isCurrentMonth) {
                        dayCell.addEventListener('click', () => {
                            // Remove previous selection
                            monthlyCalendar.querySelectorAll('.day-cell.selected').forEach(cell => {
                                cell.classList.remove('selected');
                            });

                            // Add selection to clicked cell
                            dayCell.classList.add('selected');

                            loadDailySchedule(dateString);
                        });
                    }

                    monthlyCalendar.appendChild(dayCell);
                    current.add(1, 'day');
                }
            }

            function getCountClass(count) {
                if (count >= 15) return 'very-high';
                if (count >= 10) return 'high';
                return '';
            }

            function renderDailySchedule(data) {
                const appointmentsByDoctor = data.appointments_by_doctor || [];
                const date = data.date;

                document.getElementById('dailyScheduleDate').textContent =
                    `Daily Schedule: ${moment(date).format('dddd, MMMM D, YYYY')}`;

                if (appointmentsByDoctor.length === 0) {
                    dailyScheduleContent.innerHTML = `
                                <div class="alert-info">
                                    <h4><i class="fas fa-info-circle"></i> No appointments scheduled</h4>
                                    <p>There are no appointments scheduled for this date.</p>
                                </div>
                            `;
                    return;
                }

                let html = '';

                appointmentsByDoctor.forEach(doctorData => {
                    html += `
                                <div class="doctor-card">
                                    <div class="doctor-card-summary" data-doctor="${doctorData.doctor_name}">
                                        <span>
                                            <i class="fas fa-user-md"></i>
                                            ${doctorData.doctor_name} (${doctorData.specialization}) - Total Appointments: ${doctorData.total}
                                        </span>
                                        <span class="toggle-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </span>
                                    </div>
                                    <div class="reminder-status">
                                        <i class="fas fa-bell"></i>
                                        Reminder Status: ${doctorData.reminder_status}
                                    </div>
                                    <div class="doctor-appointments">
                                        <ul>
                                            ${doctorData.appointments.map(apt => `
                                                <li>
                                                    <span class="appointment-time">
                                                        <i class="far fa-clock"></i>
                                                        ${formatTime(apt.time)}
                                                    </span>
                                                    <span class="appointment-details">
                                                        <i class="fas fa-user"></i>
                                                        ${apt.patient} (${apt.details}) 
                                                    </span>
                                                    <span class="appointment-status status-${apt.status.toLowerCase()}">
                                                        ${apt.status}
                                                    </span>
                                                </li>
                                            `).join('')}
                                        </ul>
                                    </div>
                                </div>
                            `;
                });

                dailyScheduleContent.innerHTML = html;

                // Add event listeners for expanding/collapsing doctor cards
                dailyScheduleContent.querySelectorAll('.doctor-card-summary').forEach(summary => {
                    summary.addEventListener('click', function () {
                        const appointmentsDiv = this.parentElement.querySelector('.doctor-appointments');
                        const toggleIcon = this.querySelector('.toggle-icon i');

                        if (appointmentsDiv.style.display === 'block') {
                            appointmentsDiv.style.display = 'none';
                            toggleIcon.className = 'fas fa-chevron-down';
                        } else {
                            appointmentsDiv.style.display = 'block';
                            toggleIcon.className = 'fas fa-chevron-up';
                        }
                    });
                });
            }

            function formatTime(time) {
                return moment(time, 'HH:mm:ss').format('h:mm A');
            }

            function showDailySchedule(date) {
                monthlyCalendar.parentElement.style.display = 'none';
                dailyScheduleContainer.style.display = 'block';
            }

            function showMonthlyCalendar() {
                monthlyCalendar.parentElement.style.display = 'block';
                dailyScheduleContainer.style.display = 'none';

                // Clear selections
                monthlyCalendar.querySelectorAll('.day-cell.selected').forEach(cell => {
                    cell.classList.remove('selected');
                });
            }
        });

        // Initialize Select2 for better dropdown styling
        $(document).ready(function () {
            $('#doctorFilter').select2({
                placeholder: "Select a Doctor",
                allowClear: true,
                theme: 'default'
            });

            // Custom Select2 styling to match our theme
            $('.select2-container--default .select2-selection--single').css({
                'height': '42px',
                'border': '2px solid #e9ecef',
                'border-radius': '8px',
                'padding': '0.75rem 1rem'
            });

            $('.select2-container--default .select2-selection--single .select2-selection__rendered').css({
                'line-height': '24px',
                'padding-left': '0'
            });

            $('.select2-container--default .select2-selection--single .select2-selection__arrow').css({
                'height': '40px'
            });

            // Focus styling
            $('#doctorFilter').on('select2:open', function () {
                $('.select2-container--default .select2-selection--single').css({
                    'border-color': '#159ed5',
                    'box-shadow': '0 0 0 3px rgba(21, 158, 213, 0.1)'
                });
            });

            $('#doctorFilter').on('select2:close', function () {
                $('.select2-container--default .select2-selection--single').css({
                    'border-color': '#e9ecef',
                    'box-shadow': 'none'
                });
            });
        });
    </script>
@endsection