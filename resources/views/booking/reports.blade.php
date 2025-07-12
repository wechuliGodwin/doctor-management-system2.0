@extends('layouts.dashboard')

@section('title', 'Specialization Performance Reports')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8fafc;
        min-height: 100vh;
        color: #1e293b;
    }

    .header {
        text-align: center;
        margin-bottom: 24px;
        padding: 16px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .header h1 {
        color: #159ed5;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .filters-section {
        background: white;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #1e293b;
        font-size: 0.9rem;
    }

    select,
    input[type="date"] {
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
        background: #fff;
        transition: border-color 0.2s ease;
    }

    select:focus,
    input[type="date"]:focus {
        outline: none;
        border-color: #159ed5;
        box-shadow: 0 0 0 2px rgba(21, 158, 213, 0.1);
    }

    .export-buttons {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #159ed5;
        color: white;
    }

    .btn:hover {
        filter: brightness(110%);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .reports-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .full-width {
        grid-template-columns: 1fr;
    }

    .report-card,
    .table-container {
        background: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .report-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
    }

    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
        margin-bottom: 16px;
    }

    .no-data-message {
        text-align: center;
        color: #64748b;
        font-size: 1rem;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        max-height: 400px;
        overflow-y: auto;
        display: block;
    }

    .data-table th,
    .data-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .data-table tfoot {
        background: #f8fafc;
        font-weight: 600;
    }

    .data-table tr:hover {
        background: #f1f5f9;
    }

    .section-header {
        background: white;
        padding: 12px;
        border-radius: 8px;
        margin: 24px 0 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .section-header h2 {
        color: #159ed5;
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .section-description {
        color: #64748b;
        font-size: 0.9rem;
    }

    @media (max-width: 992px) {
        .reports-section {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 12px;
        }

        .header h1 {
            font-size: 1.5rem;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }
    }

    #customDateRange {
        display: none;
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <!-- <div class="header">
        <h1>Specialization Performance Reports</h1>
        <p>Analyze appointment trends by specialization and branch</p>
    </div> -->

    <!-- Filters Section -->
    <div class="filters-section">
        <form id="filtersForm" action="{{ route('booking.reports') }}" method="GET">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="timePeriod">Time Period</label>
                    <select id="timePeriod" name="time_period" onchange="toggleCustomDateRange()">
                        <option value="day" {{ $timePeriod === 'day' ? 'selected' : '' }}>Today</option>
                        <option value="month" {{ $timePeriod === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $timePeriod === 'year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $timePeriod === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div class="filter-group" id="customDateRange">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="filter-group" id="customDateRange">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" name="end_date" value="{{ $endDate }}">
                </div>
                @if ($isSuperadmin)
                <div class="filter-group">
                    <label for="hospitalBranch">Hospital Branch</label>
                    <select id="hospitalBranch" name="branch">
                        <option value="">All Branches</option>
                        @foreach ($hospitalBranches as $branch)
                        <option value="{{ $branch }}" {{ $selectedBranch === $branch ? 'selected' : '' }}>{{ ucfirst($branch) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="filter-group">
                    <label for="specialization">Specialization</label>
                    <select id="specialization" name="specialization">
                        <option value="">All Specializations</option>
                        @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" {{ $selectedSpecialization == $specialization->id ? 'selected' : '' }}>{{ $specialization->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="export-buttons">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>
    </div>

    <!-- Specialization Report Section -->
    <div class="section-header">
        <h2>Specialization Performance Report</h2>
    </div>
    @if ($specializationData->isEmpty())
    <div class="no-data-message">No specialization data available for the selected filters.</div>
    @else
    <div class="reports-section">
        <div class="report-card">
            <h3 class="report-title">Top 10 Specialization Metrics</h3>
            <div class="chart-container">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        <div class="table-container">
            <h3 class="report-title">Performance Details (All Specializations)</h3>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Specialization</th>
                            <th>Total Appointments</th>
                            <th>Confirmed (Pending)</th>
                            <th>Patients Seen</th>
                            <th>Missed</th>
                            <th>Cancelled</th>
                            <th>Rescheduled</th>
                            <th>Pending External Approvals</th>
                            <th>External Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($specializationData as $data)
                        <tr>
                            <td>{{ $data->specialization_name }}</td>
                            <td>{{ $data->total_appointments }}</td>
                            <td>{{ $data->confirmed_pending }}</td>
                            <td>{{ $data->patients_seen }}</td>
                            <td>{{ $data->missed }}</td>
                            <td>{{ $data->cancelled }}</td>
                            <td>{{ $data->rescheduled }}</td>
                            <td>{{ $data->pending_external_approvals }}</td>
                            <td>{{ $data->external_approved }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $specializationData->sum('total_appointments') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('confirmed_pending') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('patients_seen') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('missed') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('cancelled') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('rescheduled') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('pending_external_approvals') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('external_approved') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Daily Booking Rate Section -->
    @if (empty($dailyBookingChartData['labels']))
    <div class="no-data-message">No daily booking data available for the selected filters.</div>
    @else
    <div class="reports-section full-width">
        <div class="report-card">
            <h3 class="report-title">Daily Booking Rate</h3>
            <div class="chart-container">
                <canvas id="dailyBookingChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- Hospital Branch Insights Section (Superadmins Only) -->
    @if ($isSuperadmin)
    <div class="section-header">
        <h2>Hospital Branch Bookings</h2>
        <p class="section-description">Total bookings per hospital branch (including internal and external approved appointments)</p>
    </div>
    @if ($branchData->isEmpty())
    <div class="no-data-message">No branch booking data available for the selected filters.</div>
    @else
    <div class="reports-section">
        <div class="report-card">
            <h3 class="report-title">Branch Booking Distribution</h3>
            <div class="chart-container">
                <canvas id="branchChart"></canvas>
            </div>
        </div>
        <div class="table-container">
            <h3 class="report-title">Branch Booking Details</h3>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Total Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branchData as $data)
                        <tr>
                            <td>{{ ucfirst($data->hospital_branch) }}</td>
                            <td>{{ $data->total_bookings }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $branchData->sum('total_bookings') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif
    @endif

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    // Toggle custom date range inputs
    function toggleCustomDateRange() {
        const timePeriod = document.getElementById('timePeriod').value;
        const customDateRange = document.querySelectorAll('#customDateRange');
        customDateRange.forEach(element => {
            element.style.display = timePeriod === 'custom' ? 'block' : 'none';
        });
    }

    // Initial toggle based on selected time period
    toggleCustomDateRange();

    // Branch Chart (Superadmins Only) - Pie Chart
    @if($isSuperadmin && !$branchData -> isEmpty())
    const branchChartData = @json($branchChartData);
    console.log('Branch Chart Data:', branchChartData); // Debug: Log branch chart data

    const branchCtx = document.getElementById('branchChart');
    if (branchCtx) {
        const branchChart = new Chart(branchCtx.getContext('2d'), {
            type: 'pie',
            data: {
                labels: branchChartData.labels,
                datasets: [{
                    label: branchChartData.datasets[0].label,
                    data: branchChartData.datasets[0].data,
                    backgroundColor: branchChartData.datasets[0].backgroundColor,
                    borderColor: '#ffffff',
                    borderWidth: 1,
                }],
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            boxWidth: 20,
                            padding: 15,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    } else {
        console.error('Canvas element #branchChart not found');
    }
    @elseif($isSuperadmin)
    console.warn('No branch data available for chart rendering');
    @endif

    // Specialization Performance Chart (Top 10)
    @if(!$specializationData -> isEmpty())
    const chartData = @json($chartData);
    console.log('Specialization Chart Data:', chartData); // Debug: Log specialization chart data

    const performanceCtx = document.getElementById('performanceChart');
    if (performanceCtx) {
        const performanceChart = new Chart(performanceCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets.map(dataset => ({
                    ...dataset,
                    maxBarThickness: 60, // Set maximum bar width to 60px
                    barPercentage: 0.9, // Use 90% of the available bar space
                    categoryPercentage: 0.8 // Use 80% of the category space
                })),
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Appointments'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: '{{ $selectedSpecialization ? "Metrics for Selected Specialization" : "Top 10 Specializations" }}'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: false,
                            maxTicksLimit: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    } else {
        console.error('Canvas element #performanceChart not found');
    }
    @else
    console.warn('No specialization data available for chart rendering');
    @endif

    // Daily Booking Rate Chart
    @if(!empty($dailyBookingChartData['labels']))
    const dailyBookingChartData = @json($dailyBookingChartData);
    console.log('Daily Booking Chart Data:', dailyBookingChartData); // Debug: Log daily booking chart data

    const dailyBookingCtx = document.getElementById('dailyBookingChart');
    if (dailyBookingCtx) {
        const dailyBookingChart = new Chart(dailyBookingCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: dailyBookingChartData.labels,
                datasets: dailyBookingChartData.datasets,
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    } else {
        console.error('Canvas element #dailyBookingChart not found');
    }
    @else
    console.warn('No daily booking data available for chart rendering');
    @endif
</script>
@endsection