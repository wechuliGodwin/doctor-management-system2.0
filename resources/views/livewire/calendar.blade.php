<div class="calendar-container bg-white shadow-md rounded-lg p-4" style="box-shadow: 10px 10px 12px #159ed5;">
    <div id="calendar" class="calendar-grid">
        <!-- Calendar Header -->
        <div class="calendar-header grid space-x-1 grid-cols-7">
            <div class="calendar-day text-center font-bold">Sun</div>
            <div class="calendar-day text-center font-bold">Mon</div>
            <div class="calendar-day text-center font-bold">Tue</div>
            <div class="calendar-day text-center font-bold">Wed</div>
            <div class="calendar-day text-center font-bold">Thu</div>
            <div class="calendar-day text-center font-bold">Fri</div>
            <div class="calendar-day text-center font-bold">Sat</div>
        </div>

        <!-- Days Placeholder -->
        <div class="calendar-days grid grid-cols-7">
            @for ($i = 1; $i <= 30; $i++) <!-- Assuming 30 days in a month -->
                <div class="calendar-day-cell border border-gray-300 p-1 h-16 relative">
                    <span class="day-number block text-gray-700 text-center text-xs">{{ $i }}</span>
                    <!-- Optional: Add events here -->
                    @if ($i == 10)
                        <div class="event text-xs">Meeting</div>
                    @endif
                    @if ($i == 12)
                        <div class="event text-xs">Doctor Appointment</div>
                    @endif
                    @if ($i == 15)
                        <div class="event text-xs">Team Lunch</div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <style>
        .calendar-container {
            width: 100%; /* Ensures the container takes full width */
        }

        .calendar-grid {
            display: grid;
            gap: 0.5rem; /* Reduced spacing */
        }

        .calendar-header {
            font-weight: bold;
            text-align: center;
            margin-bottom: 0.5rem; /* Reduced margin */
        }

        .calendar-day {
            padding: 5px; /* Reduced padding */
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .calendar-day-cell {
            padding: 5px; /* Reduced padding */
            border-radius: 5px;
            text-align: center;
            height: 65px; /* Reduced cell height */
            position: relative;
            overflow: hidden;
        }

        .day-number {
            display: block;
            font-size: 0.9rem; /* Smaller day number font */
            margin-bottom: 3px;
        }

        /* Styling for events */
        .event {
            background-color: #159ed5;
            color: white;
            padding: 1px 3px; /* Reduced padding for events */
            border-radius: 3px;
            position: absolute;
            bottom: 3px;
            left: 3px;
            right: 3px;
            font-size: 0.7rem; /* Smaller font for events */
        }
    </style>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                // Example events
                {
                    title: 'Meeting',
                    start: '2024-10-10',
                },
                {
                    title: 'Doctor Appointment',
                    start: '2024-10-12',
                },
                {
                    title: 'Team Lunch',
                    start: '2024-10-15',
                },
            ],
        });
        calendar.render();
    });
</script>
