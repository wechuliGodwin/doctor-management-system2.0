@extends('layouts.dashboard')

@section('content')
<!-- Alerts for Success/Error Messages -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show m-0 rounded-0" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show m-0 rounded-0" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Main Container -->
<div class="container-fluid px-0">
    <div class="card shadow-sm mb-0 border-0 rounded-3">
        <!-- Card Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="fas fa-stethoscope me-2"></i>{{ $title }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('booking.specialization.limits', ['date' => $date->copy()->subDay()->toDateString(), 'search' => request('search')]) }}"
                    class="btn btn-sm btn-light rounded-circle" title="Previous Day">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <span class="fw-medium">{{ $date->format('F d, Y') }}</span>
                <a href="{{ route('booking.specialization.limits', ['date' => $date->copy()->addDay()->toDateString(), 'search' => request('search')]) }}"
                    class="btn btn-sm btn-light rounded-circle" title="Next Day">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-0">
            <!-- Filter Form -->
            <div class="p-3 bg-light border-bottom">
                <form method="GET" action="{{ route('booking.specialization.limits') }}"
                    class="d-flex align-items-end gap-3">
                    <div class="flex-grow-1">
                        <label for="search" class="form-label small fw-semibold text-muted">
                            <i class="fas fa-search me-1"></i>Search Specialization
                        </label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm shadow-sm"
                            value="{{ request('search') }}" placeholder="Enter specialization name">
                    </div>
                    <div>
                        <label for="date" class="form-label small fw-semibold text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>Select Date
                        </label>
                        <input type="date" name="date" id="date" class="form-control form-control-sm shadow-sm"
                            value="{{ $date->toDateString() }}" onchange="this.form.submit()">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('booking.specialization.limits') }}"
                        class="btn btn-sm btn-outline-secondary shadow-sm">
                        <i class="fas fa-undo me-1"></i>Reset
                    </a>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th><i class="fas fa-stethoscope me-1"></i>Specialization</th>
                            <th><i class="fas fa-stethoscope me-1"></i>Specialization Group</th>
                            <th><i class="fas fa-tachometer-alt me-1"></i>Daily Limit</th>
                            <th><i class="fas fa-book me-1"></i>Bookings Today</th>
                            <th><i class="fas fa-chair me-1"></i>Remaining Slots</th>
                            <th><i class="fas fa-cog me-1"></i>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specializations as $specialization)
                        <tr>
                            <td>{{ $specialization->name }}</td>
                            <td>{{ $specialization->group_name }}</td>
                            <td>{{ $specialization->limits }}</td>
                            <td>{{ $activeBookings[$specialization->name] ?? 0 }}</td>
                            <td>{{ max(0, $specialization->limits - ($activeBookings[$specialization->name] ?? 0)) }}
                            </td>

                            <td>
                                <button
                                    class="btn btn-sm btn-primary shadow-sm open-limit-modal"
                                    data-bs-toggle="modal"
                                    data-bs-target="#limitModal"
                                    data-id="{{ $specialization->id }}"
                                    data-limit="{{ $specialization->limits }}"
                                    data-day="{{ $specialization->day_of_week }}"
                                    data-group="{{ $specialization->group_id }}">
                                    <i class="fas fa-edit me-1"></i>Change
                                </button>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-exclamation-circle me-1"></i>No specializations found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Changing Limits -->
<!-- Single Modal -->
<div class="modal fade" id="limitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-lg">
            <form method="POST" action="{{ route('booking.specialization.update.limit') }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-tachometer-alt me-2"></i>Change Limit
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="specialization_id" id="modal_specialization_id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-tachometer-alt me-1"></i>New Daily Limit
                        </label>
                        <input type="number" name="daily_limit" id="modal_daily_limit"
                            class="form-control shadow-sm" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-day me-1"></i>Day of the Week
                        </label>
                        <select name="day_of_week" id="modal_day_of_week"
                            class="form-control shadow-sm" required>
                            <option value="daily">Daily</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-users me-1"></i>Group
                        </label>
                        <select name="group_id" id="modal_group_id"
                            class="form-control shadow-sm" required>
                            <option value="">Select Group</option>
                            @foreach($specializations_group as $group)
                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary shadow-sm"
                        data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-save me-1"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Custom Styles -->
<style>
    /* General Styles */
    body {
        background-color: #f5f9fc;
        /* Very light blue from the family */
        font-family: 'Inter', sans-serif;
    }

    .container-fluid {
        padding: 0 1.5rem;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(21, 158, 213, 0.1) !important;
        /* Shadow with a hint of the primary color */
    }

    .card-header {
        background: linear-gradient(90deg, #159ed5, #0d6a9f);
        /* Gradient using primary and darker shade */
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .card-header h5 {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .card-body {
        padding: 0;
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        margin: 1rem 1.5rem 0;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background-color: #e6f4fa;
        /* Very light blue for success */
        color: #0d6a9f;
        /* Darker shade for text */
        border: 1px solid #b3e0f2;
        /* Light blue border */
    }

    .alert-danger {
        background-color: #fff1f1;
        /* Keep a light red for errors to indicate urgency */
        color: #d32f2f;
        border: 1px solid #ffcdd2;
    }

    .btn-close {
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Filter Form */
    .bg-light {
        background-color: #f8fbfe !important;
        /* Very light blue for the filter section */
    }

    .form-label {
        font-size: 0.85rem;
        color: #0d6a9f;
        /* Darker shade for labels */
        margin-bottom: 0.25rem;
    }

    .form-control,
    .form-control-sm {
        border-radius: 6px;
        border: 1px solid #d1e9f5;
        /* Light blue border */
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
        border-color: #159ed5;
        /* Primary color on focus */
        box-shadow: 0 0 0 3px rgba(21, 158, 213, 0.1);
        /* Light blue glow */
    }

    .btn-primary {
        background: linear-gradient(90deg, #159ed5, #0d6a9f);
        /* Gradient using primary and darker shade */
        border: none;
        border-radius: 6px;
        font-weight: 500;
        transition: background 0.3s ease, transform 0.1s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #0d6a9f, #159ed5);
        /* Reverse gradient on hover */
        transform: translateY(-1px);
    }

    .btn-light {
        background-color: #ffffff;
        border: 1px solid #d1e9f5;
        /* Light blue border */
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-light:hover {
        background-color: #e6f4fa;
        /* Very light blue on hover */
        transform: translateY(-1px);
    }

    .rounded-circle {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Table Styles */
    .table-responsive {
        max-height: calc(100vh - 280px);
        overflow-y: auto;
        border-bottom: 1px solid #d1e9f5;
        /* Light blue border */
    }

    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table th {
        background: linear-gradient(90deg, #0d6a9f, #094d7a);
        /* Darker shade gradient for table header */
        color: #ffffff;
        font-weight: 600;
        padding: 0.75rem 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #094d7a;
        /* Even darker shade for border */
    }

    .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        color: #37474f;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8fbfe;
        /* Very light blue for odd rows */
    }

    .table-hover tbody tr:hover {
        background-color: #e6f4fa;
        /* Very light blue on hover */
        transition: background-color 0.2s ease;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #d1e9f5;
        /* Light blue border */
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        background: linear-gradient(90deg, #159ed5, #0d6a9f);
        /* Gradient using primary and darker shade */
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: none;
        padding: 1rem 1.5rem;
    }

    .btn-outline-secondary {
        border-color: #d1e9f5;
        /* Light blue border */
        color: #0d6a9f;
        /* Darker shade for text */
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #e6f4fa;
        /* Very light blue on hover */
        color: #094d7a;
        /* Even darker shade for text */
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0 1rem;
        }

        .card-header {
            flex-direction: column;
            gap: 0.75rem;
            text-align: center;
        }

        .card-header h5 {
            font-size: 1.1rem;
        }

        .form-label {
            font-size: 0.8rem;
        }

        .form-control,
        .form-control-sm {
            font-size: 0.85rem;
        }

        .table th,
        .table td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }

        .table-responsive {
            max-height: calc(100vh - 320px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const limitModal = document.getElementById('limitModal');
        limitModal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract data attributes
            const id = button.getAttribute('data-id');
            const limit = button.getAttribute('data-limit');
            const day = button.getAttribute('data-day');
            const group = button.getAttribute('data-group');

            // Populate the modal fields
            limitModal.querySelector('#modal_specialization_id').value = id;
            limitModal.querySelector('#modal_daily_limit').value = limit;
            limitModal.querySelector('#modal_day_of_week').value = day;
            limitModal.querySelector('#modal_group_id').value = group;

            // (Optional) change the modal title:
            const title = button.closest('tr').querySelector('td:first-child').innerText;
            limitModal.querySelector('.modal-title').innerHTML =
                `<i class="fas fa-tachometer-alt me-2"></i>Change Limit for ${title}`;
        });
    });
</script>
@endsection