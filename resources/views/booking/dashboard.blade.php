@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('content')
   <style>
        .small-widget {
            padding: 12px;
            margin-bottom: 15px;
        }
        .small-widget h3 {
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .small-widget p {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .small-widget .time-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        .small-widget .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
        .widget { padding: 20px; }
        h4 { font-size: 1.5rem; font-weight: 600; color: #333; }
        h5 { font-size: 1.25rem; font-weight: 600; color: #333; margin-bottom: 15px; }
        .form-label { font-size: 0.9rem; color: #333; }
        .form-control, .form-select { border-radius: 4px; border: 1px solid #ccc; }
        .btn-outline-primary { border-radius: 4px; padding: 6px 12px; font-weight: 500; border-color: #159ed5; color: #159ed5; }
        .btn-outline-primary:hover { background-color: #159ed5; color: #fff; }
        .btn-outline-success { border-radius: 4px; padding: 6px 12px; font-weight: 500; border-color: #28a745; color: #28a745; }
        .btn-outline-success:hover { background-color: #28a745; color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 0.875rem; }
        table { border-collapse: collapse; width: 100%; }
        thead th { background-color: #159ed5; color: #fff; font-weight: 600; font-size: 0.875rem; padding: 0.5rem 0.75rem; border: 1px solid #dee2e6; text-align: left; }
        tbody tr:nth-child(odd) { background-color: #f9f9f9; }
        tbody tr:nth-child(even) { background-color: #fff; }
        tbody td { padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #212529; border: 1px solid #dee2e6; }
        td { white-space: nowrap; }
        .date-range-inputs, .month-input, .year-input { display: none; }
        .filter-form { max-width: 800px; margin: 0 auto; }
        .table-container { max-width: 100%; margin: 20px 0; }
    </style>
    <div class="row">
        @php
            // Get the user's hospital branch
            $userBranch = Auth::guard('booking')->user()->hospital_branch;
        @endphp

        @foreach ([
            'new' => ['New Appointments', 'warning'],
            'review' => ['Review Appointments', 'info'],
            'postop' => ['Post-Ops Appointments', 'secondary'],
            'external_pending' => ['External Pending Approval', 'light'],
            'external_approved' => ['External Approved', 'dark'],
            'cancelled' => ['Cancelled Appointments', 'danger'],
            'all' => ['All Appointments', 'primary'],
            'rescheduled' => ['Rescheduled Appointments', 'success']
        ] as $status => [$label, $color])
            @if ($status === 'external_pending' || $status === 'external_approved')
                {{-- Only show external widgets for 'kijabe' branch --}}
                @if ($userBranch === 'kijabe')
                    <div class="col-md-3 col-6 mb-3">
                        <div class="widget small-widget">
                            <h3>{{ $label }}</h3>
                            <p>
                                @if($status === 'external_pending')
                                    {{ $totalExternalPendingAppointments ?? 0 }}
                                @elseif($status === 'external_approved')
                                    {{ $totalExternalApprovedAppointments ?? 0 }}
                                @endif
                            </p>
                            <div class="time-label">Current Month</div>
                            <a href="{{ route('booking.dashboard.status', $status) }}" class="btn btn-{{ $color }} btn-sm">View Details</a>
                        </div>
                    </div>
                @endif
            @else
                {{-- Show other widgets for all branches --}}
                <div class="col-md-3 col-6 mb-3">
                    <div class="widget small-widget">
                        <h3>{{ $label }}</h3>
                        <p>
                            @if($status === 'new')
                                {{ $totalNewAppointments ?? 0 }}
                            @elseif($status === 'review')
                                {{ $totalReviewAppointments ?? 0 }}
                            @elseif($status === 'postop')
                                {{ $totalPostopAppointments ?? 0 }}
                            @elseif($status === 'cancelled')
                                {{ $totalCancelledAppointments ?? 0 }}
                            @elseif($status === 'all')
                                {{ $totalAppointments ?? 0 }}
                            @elseif($status === 'rescheduled')
                                {{ $totalRescheduledAppointments ?? 0 }}
                            @endif
                        </p>
                        <div class="time-label">Current Month</div>
                        <a href="{{ route('booking.dashboard.status', $status) }}" class="btn btn-{{ $color }} btn-sm">View Details</a>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="widget" style="padding: 15px;">
                <canvas id="specializationChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Chart.js initialization
            const ctx = document.getElementById('specializationChart').getContext('2d');
            const labels = @json($labels);
            ctx.canvas.width = Math.max(800, labels.length * 50);
            new Chart(ctx, {
                type: 'bar',
                data: { labels, datasets: @json($chartData) },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        title: { 
                            display: true, 
                            text: 'Appointments by Specialization', 
                            font: { size: 12 } 
                        }, 
                        legend: { display: false } 
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true,
                            ticks: { font: { size: 10 } }
                        }, 
                        x: { 
                            ticks: { 
                                autoSkip: false, 
                                maxRotation: 45, 
                                minRotation: 45,
                                font: { size: 10 }
                            } 
                        } 
                    },
                    elements: { 
                        bar: { 
                            barPercentage: 0.4, 
                            categoryPercentage: 0.8 
                        } 
                    },
                }
            });

            // Filter form toggle logic
            const dateFilter = document.getElementById('date_filter');
            const dateRangeInputs = document.querySelectorAll('.date-range-inputs');
            const monthInput = document.querySelector('.month-input');
            const yearInput = document.querySelector('.year-input');

            function toggleFilterInputs() {
                dateRangeInputs.forEach(input => input.style.display = 'none');
                monthInput.style.display = 'none';
                yearInput.style.display = 'none';

                if (dateFilter.value === 'range') {
                    dateRangeInputs.forEach(input => input.style.display = 'block');
                } else if (dateFilter.value === 'month') {
                    monthInput.style.display = 'block';
                } else if (dateFilter.value === 'year') {
                    yearInput.style.display = 'block';
                }
            }

            // Initial toggle based on current selection
            toggleFilterInputs();

            // Update on change
            dateFilter.addEventListener('change', toggleFilterInputs);
        });
    </script>