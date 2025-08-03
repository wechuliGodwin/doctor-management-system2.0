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

    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        padding-left: 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
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
        text-align: center;
    }

    .chart-container {
        position: relative;
        height: 350px;
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
        table-layout: auto;
    }

    .data-table th,
    .data-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .data-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #1e293b;
        position: sticky;
        top: 0;
        z-index: 1;
        text-align: center;
    }

    .data-table td {
        vertical-align: middle;
        text-align: center;
    }

    .data-table .specialization-column {
        position: sticky;
        left: 0;
        background: white;
        z-index: 2;
        text-align: left;
        min-width: 120px;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .data-table tfoot {
        background: #f8fafc;
        font-weight: 600;
    }

    .data-table tr:hover {
        background: #f1f5f9;
    }

    .table-container {
        overflow-x: auto;
        max-height: 400px;
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
        text-align: center;
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

        .chart-container {
            height: 300px;
        }

        .data-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .data-table th,
        .data-table td {
            min-width: 80px;
            font-size: 0.8rem;
            padding: 8px;
        }

        .data-table .specialization-column {
            min-width: 100px;
            max-width: 120px;
        }
    }

    @media (max-width: 576px) {
        .data-table {
            display: block;
        }

        .data-table thead {
            display: none;
        }

        .data-table tbody tr {
            display: block;
            margin-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }

        .data-table tbody td {
            display: block;
            text-align: left;
            padding: 8px;
            position: relative;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            display: inline-block;
            width: 40%;
            padding-right: 10px;
            color: #1e293b;
        }

        .data-table .specialization-column {
            position: static;
            background: #f8fafc;
            font-weight: 600;
            max-width: none;
        }

        .data-table tfoot {
            display: none;
        }
    }
</style>

<div class="dashboard-container">
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
                    <select id="specialization" name="specialization" class="select2">
                        <option value="">All Specializations</option>
                        @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" {{ $selectedSpecialization == $specialization->id ? 'selected' : '' }}>{{ $isSuperadmin ? $specialization->display_name : $specialization->name }}</option>
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
                            <th class="specialization-column">Specialization</th>
                            <th>Total</th>
                            <th>Confirmed</th>
                            <th>Seen</th>
                            <th>HMIS</th>
                            <th>Missed</th>
                            <th>Cancelled</th>
                            <th>Rescheduled</th>
                            @php
                            $user = Auth::guard('booking')->user();
                            $selectedBranch = session('selected_branch', $user ? $user->hospital_branch : null);
                            @endphp
                            @if ($selectedBranch === 'kijabe')
                            <th>Pending Approval</th>
                            <th>Approved</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($specializationData as $data)
                        <tr>
                            <td class="specialization-column" data-label="Specialization">{{ \Illuminate\Support\Str::limit($data->specialization_name, 10, '...') }}</td>
                            <td data-label="Total">{{ $data->total_appointments }}</td>
                            <td data-label="Confirmed">{{ $data->confirmed_pending }}</td>
                            <td data-label="Seen">{{ $data->patients_seen }}</td>
                            <td data-label="HMIS">{{ $data->hmis_honoured }}</td>
                            <td data-label="Missed">{{ $data->missed }}</td>
                            <td data-label="Cancelled">{{ $data->cancelled }}</td>
                            <td data-label="Rescheduled">{{ $data->rescheduled }}</td>
                            @if ($selectedBranch === 'kijabe')
                            <td data-label="Pending Approval">{{ $data->pending_external_approvals }}</td>
                            <td data-label="Approved">{{ $data->external_approved }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="specialization-column"><strong>Total</strong></td>
                            <td><strong>{{ $specializationData->sum('total_appointments') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('confirmed_pending') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('patients_seen') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('hmis_honoured') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('missed') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('cancelled') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('rescheduled') }}</strong></td>
                            @if ($selectedBranch === 'kijabe')
                            <td><strong>{{ $specializationData->sum('pending_external_approvals') }}</strong></td>
                            <td><strong>{{ $specializationData->sum('external_approved') }}</strong></td>
                            @endif
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

    <!-- Hospital Branch Insights Section (Superadmins and Admins Only) -->
    @if ($isSuperadmin || $isAdmin)
    @if ($branchData->isEmpty())
    <div class="no-data-message">No branch booking data available for the selected filters.</div>
    @else
    <div class="reports-section full-width">
        <div class="table-container">
            <h3 class="report-title">Tracing Status Details</h3>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Total Bookings</th>
                            <th>Contacted</th>
                            <th>Contacted %</th>
                            <th>No Response</th>
                            <th>No Response %</th>
                            <th>Other</th>
                            <th>Other %</th>
                            <th>HMIS Honoured</th>
                            <th>HMIS Honoured %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branchData as $data)
                        <tr>
                            <td data-label="Branch">{{ ucfirst($data->hospital_branch) }}</td>
                            <td data-label="Total Bookings">{{ $data->total_bookings }}</td>
                            <td data-label="Contacted">{{ $data->traced_contacted }}</td>
                            <td data-label="Contacted %">{{ $data->total_bookings > 0 ? number_format(($data->traced_contacted / $data->total_bookings) * 100, 1) : 0 }}%</td>
                            <td data-label="No Response">{{ $data->traced_no_response }}</td>
                            <td data-label="No Response %">{{ $data->total_bookings > 0 ? number_format(($data->traced_no_response / $data->total_bookings) * 100, 1) : 0 }}%</td>
                            <td data-label="Other">{{ $data->traced_other }}</td>
                            <td data-label="Other %">{{ $data->total_bookings > 0 ? number_format(($data->traced_other / $data->total_bookings) * 100, 1) : 0 }}%</td>
                            <td data-label="HMIS Honoured">{{ $data->hmis_honoured }}</td>
                            <td data-label="HMIS Honoured %">{{ $data->total_bookings > 0 ? number_format(($data->hmis_honoured / $data->total_bookings) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $branchData->sum('total_bookings') }}</strong></td>
                            <td><strong>{{ $branchData->sum('traced_contacted') }}</strong></td>
                            <td><strong>{{ $branchData->sum('total_bookings') > 0 ? number_format(($branchData->sum('traced_contacted') / $branchData->sum('total_bookings')) * 100, 1) : 0 }}%</strong></td>
                            <td><strong>{{ $branchData->sum('traced_no_response') }}</strong></td>
                            <td><strong>{{ $branchData->sum('total_bookings') > 0 ? number_format(($branchData->sum('traced_no_response') / $branchData->sum('total_bookings')) * 100, 1) : 0 }}%</strong></td>
                            <td><strong>{{ $branchData->sum('traced_other') }}</strong></td>
                            <td><strong>{{ $branchData->sum('total_bookings') > 0 ? number_format(($branchData->sum('traced_other') / $branchData->sum('total_bookings')) * 100, 1) : 0 }}%</strong></td>
                            <td><strong>{{ $branchData->sum('hmis_honoured') }}</strong></td>
                            <td><strong>{{ $branchData->sum('total_bookings') > 0 ? number_format(($branchData->sum('hmis_honoured') / $branchData->sum('total_bookings')) * 100, 1) : 0 }}%</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Debug: Check if jQuery is loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded. Ensure the jQuery CDN is accessible.');
    } else {
        console.log('jQuery loaded successfully, version:', jQuery.fn.jquery);
    }

    // Initialize Select2 for specialization dropdown
    jQuery(document).ready(function() {
        if (jQuery.fn.select2) {
            jQuery('#specialization').select2({
                placeholder: "Select a specialization",
                allowClear: true,
                width: '100%'
            });
            console.log('Select2 initialized successfully');
        } else {
            console.error('Select2 is not loaded. Ensure the Select2 CDN is accessible.');
        }
    });

    // Debug: Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js library is not loaded. Ensure the CDN is accessible.');
    } else {
        console.log('Chart.js library loaded successfully, version:', Chart.version);
    }

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

    // Specialization Performance Chart (Top 10)
    @if(!$specializationData->isEmpty())
    const chartData = @json($chartData);
    console.log('Specialization Chart Data:', chartData);

    const performanceCtx = document.getElementById('performanceChart');
    if (performanceCtx) {
        try {
            const performanceChart = new Chart(performanceCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets.map((dataset, index) => ({
                        ...dataset,
                        backgroundColor: [
                            'rgba(21, 158, 213, 0.7)',  // Blue
                            'rgba(255, 99, 132, 0.7)',  // Red
                            'rgba(54, 162, 235, 0.7)',  // Light Blue
                            'rgba(255, 206, 86, 0.7)',  // Yellow
                            'rgba(75, 192, 192, 0.7)',  // Teal
                            'rgba(153, 102, 255, 0.7)', // Purple
                            'rgba(255, 159, 64, 0.7)'   // Orange
                        ][index % 7],
                        borderColor: [
                            'rgba(21, 158, 213, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ][index % 7],
                        borderWidth: 1,
                        maxBarThickness: 40,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    })),
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Appointments',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                borderColor: '#e2e8f0',
                                borderWidth: 1
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ $selectedSpecialization ? "Metrics for Selected Specialization" : "Top 10 Specializations" }}',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: false,
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                },
                                boxWidth: 20,
                                padding: 15
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 12
                            },
                            bodyFont: {
                                size: 11
                            },
                            padding: 10
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            console.log('Specialization Performance Chart initialized successfully');
        } catch (error) {
            console.error('Error initializing Specialization Performance Chart:', error);
        }
    } else {
        console.error('Canvas element #performanceChart not found');
    }
    @else
    console.warn('No specialization data available for chart rendering');
    @endif

    // Daily Booking Rate Chart
    @if(!empty($dailyBookingChartData['labels']))
    const dailyBookingChartData = @json($dailyBookingChartData);
    console.log('Daily Booking Chart Data:', dailyBookingChartData);

    const dailyBookingCtx = document.getElementById('dailyBookingChart');
    if (dailyBookingCtx) {
        try {
            const dailyBookingChart = new Chart(dailyBookingCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dailyBookingChartData.labels,
                    datasets: dailyBookingChartData.datasets.map(dataset => ({
                        ...dataset,
                        backgroundColor: 'rgba(21, 158, 213, 0.2)',
                        borderColor: 'rgba(21, 158, 213, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    })),
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Bookings',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                borderColor: '#e2e8f0',
                                borderWidth: 1
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 10,
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                },
                                boxWidth: 20,
                                padding: 15
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 12
                            },
                            bodyFont: {
                                size: 11
                            },
                            padding: 10
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            console.log('Daily Booking Rate Chart initialized successfully');
        } catch (error) {
            console.error('Error initializing Daily Booking Rate Chart:', error);
        }
    } else {
        console.error('Canvas element #dailyBookingChart not found');
    }
    @else
    console.warn('No daily booking data available for chart rendering');
    @endif

    // Branch Chart (Superadmins Only)
    @if($isSuperadmin && !$branchData->isEmpty())
    const branchChartData = @json($branchChartData);
    console.log('Branch Chart Data:', branchChartData);

    const branchCtx = document.getElementById('branchChart');
    if (branchCtx) {
        try {
            const branchChart = new Chart(branchCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: branchChartData.labels,
                    datasets: branchChartData.datasets.map((dataset, index) => ({
                        ...dataset,
                        backgroundColor: [
                            'rgba(21, 158, 213, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ][index % 5],
                        borderColor: [
                            'rgba(21, 158, 213, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ][index % 5],
                        borderWidth: 1
                    })),
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Bookings',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                borderColor: '#e2e8f0',
                                borderWidth: 1
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Hospital Branch',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                },
                                boxWidth: 20,
                                padding: 15
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 12
                            },
                            bodyFont: {
                                size: 11
                            },
                            padding: 10
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            console.log('Branch Chart initialized successfully');
        } catch (error) {
            console.error('Error initializing Branch Chart:', error);
        }
    } else {
        console.error('Canvas element #branchChart not found');
    }
    @elseif($isSuperadmin)
    console.warn('No branch data available for chart rendering');
    @endif
</script>
@endsection