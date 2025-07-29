@extends('layouts.dashboard')

@section('content')
<!-- Alerts for Success/Error Messages -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show m-0 rounded-0" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error') && !session('suggested_dates'))
<div class="alert alert-danger alert-dismissible fade show m-0 rounded-0" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show m-0 rounded-0" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    @foreach ($errors->all() as $error)
    {{ $error }}<br>
    @endforeach
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
                <!-- Default Limit Form -->
                <form method="POST" action="{{ route('booking.specialization.set.default.limit') }}" class="mt-3">
                    @csrf
                    <div class="d-flex align-items-end gap-3">
                        <div class="flex-grow-1">
                            <label for="specialization_id" class="form-label small fw-semibold text-muted">
                                <i class="fas fa-stethoscope me-1"></i>Select Specialization
                            </label>
                            <select name="specialization_id" id="specialization_id" class="form-control form-control-sm shadow-sm" required>
                                <option value="">Select a specialization</option>
                                @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" data-default-limit="{{ $specialization->limits ?? ($default_limit ?? 10) }}">{{ $specialization->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="default_limit" class="form-label small fw-semibold text-muted">
                                <i class="fas fa-tachometer-alt me-1"></i>Default Limit
                            </label>
                            <input type="number" name="default_limit" id="default_limit" class="form-control form-control-sm shadow-sm"
                                min="0" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-save me-1"></i>Save Default
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th><i class="fas fa-stethoscope me-1"></i>Specialization</th>
                            <th><i class="fas fa-stethoscope me-1"></i>Specialization Group</th>
                            <th><i class="fas fa-calendar-day me-1"></i>Days of Operation</th>
                            <th><i class="fas fa-tachometer-alt me-1"></i>Daily Limit</th>
                            <th><i class="fas fa-book me-1"></i>Bookings Today</th>
                            <th><i class="fas fa-chair me-1"></i>Remaining Slots</th>
                            <th><i class="fas fa-door-closed me-1"></i>Status</th>
                            <th><i class="fas fa-cog me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specializations as $specialization)
                        <tr>
                            <td>{{ $specialization->name }}</td>
                            <td>{{ $specialization->group_name }}</td>
                            <td>
                                {{ is_array($specialization->days_of_week) && !empty($specialization->days_of_week) ? implode(', ', array_map('ucfirst', $specialization->days_of_week)) : 'Daily' }}
                            </td>
                            <td>
                                @php
                                $dateLimit = $dateLimits[$specialization->id] ?? null;
                                $effectiveLimit = $dateLimit ? $dateLimit->daily_limit : ($specialization->limits ?? ($default_limit ?? 10));
                                @endphp
                                {{ $dateLimit && $dateLimit->is_closed ? 'Closed' : $effectiveLimit }}
                            </td>
                            <td>{{ $activeBookings[$specialization->id] ?? 0 }}</td>
                            <td>
                                {{ $dateLimit && $dateLimit->is_closed ? 'N/A' : max(0, $effectiveLimit - ($activeBookings[$specialization->id] ?? 0)) }}
                            </td>
                            <td>
                                {{ $dateLimit && $dateLimit->is_closed ? 'Closed' : 'Open' }}
                            </td>
                            <td>
                                <button
                                    class="btn btn-sm btn-primary shadow-sm open-limit-modal"
                                    data-bs-toggle="modal"
                                    data-bs-target="#limitModal"
                                    data-id="{{ $specialization->id }}"
                                    data-limit="{{ $effectiveLimit }}"
                                    data-days="{{ json_encode($specialization->days_of_week ?? ['daily']) }}"
                                    data-group="{{ $specialization->group_id }}"
                                    data-date="{{ $date->toDateString() }}"
                                    data-is-closed="{{ $dateLimit ? (int)$dateLimit->is_closed : 0 }}"
                                    data-name="{{ $specialization->name }}">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                <form action="{{ route('booking.specialization.toggle.closure') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to {{ $dateLimit && $dateLimit->is_closed ? 'reopen' : 'close' }} this specialization for {{ $date->toDateString() }}?');">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="specialization_id" value="{{ $specialization->id }}">
                                    <input type="hidden" name="date" value="{{ $date->toDateString() }}">
                                    <input type="hidden" name="is_closed" value="{{ $dateLimit && $dateLimit->is_closed ? '0' : '1' }}">
                                    <button type="submit" class="btn btn-sm {{ $dateLimit && $dateLimit->is_closed ? 'btn-success' : 'btn-danger' }} shadow-sm">
                                        <i class="fas fa-door-{{ $dateLimit && $dateLimit->is_closed ? 'open' : 'closed' }} me-1"></i>
                                        {{ $dateLimit && $dateLimit->is_closed ? 'Reopen' : 'Close' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
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

<!-- Modal for Changing Limits -->
<div class="modal fade {{ session('specialization_id') ? 'show d-block' : '' }}" id="limitModal" tabindex="-1" aria-hidden="true" {{ session('specialization_id') ? 'style="background-color: rgba(0,0,0,0.5);"' : '' }}>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-lg">
            <form method="POST" action="{{ route('booking.specialization.update.limit') }}" id="limitForm">
                @csrf
                @method('POST')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-tachometer-alt me-2"></i>Change Limit
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="specialization_id" id="modal_specialization_id">
                    <input type="hidden" name="date" id="modal_date">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-tachometer-alt me-1"></i>Daily Limit for Selected Date
                        </label>
                        <input type="number" name="daily_limit" id="modal_daily_limit"
                            class="form-control shadow-sm" min="0" value="{{ old('daily_limit') }}">
                        <small class="form-text text-muted">Set the limit for the specific date. Leave blank to use the specialization's default limit (or global default: {{ $default_limit ?? 10 }}).</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-day me-1"></i>Days of Operation
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'daily'] as $day)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input day-checkbox" type="checkbox" name="days_of_week[]"
                                    id="day_{{ $day }}" value="{{ $day }}"
                                    {{ $day === 'daily' ? 'data-exclusive' : '' }} {{ is_array(old('days_of_week', [])) && in_array($day, old('days_of_week', [])) ? 'checked' : '' }}>
                                <label class="form-check-label day-label" for="day_{{ $day }}">
                                    {{ ucfirst($day) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-users me-1"></i>Group
                        </label>
                        <select name="group_id" id="modal_group_id"
                            class="form-control shadow-sm" required>
                            <option value="">Select Group</option>
                            @foreach($specializations_group as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_closed" id="modal_is_closed"
                            class="form-check-input" {{ old('is_closed') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="modal_is_closed">
                            <i class="fas fa-door-closed me-1"></i>Close Clinic for This Date
                        </label>
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

<!-- Suggested Dates Modal -->
@if (session('modal_target') === 'suggestedDatesModal')
    @include('booking.suggested_dates_modal')
@endif
<!-- Custom Styles -->
<style>
    body {
        background-color: #f5f9fc;
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
    }

    .card-header {
        background: linear-gradient(90deg, #159ed5, #0d6a9f);
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
        color: #0d6a9f;
        border: 1px solid #b3e0f2;
    }

    .alert-danger {
        background-color: #fff1f1;
        color: #d32f2f;
        border: 1px solid #ffcdd2;
    }

    .btn-close {
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .bg-light {
        background-color: #f8fbfe !important;
    }

    .form-label {
        font-size: 0.85rem;
        color: #0d6a9f;
        margin-bottom: 0.25rem;
    }

    .form-control,
    .form-control-sm {
        border-radius: 6px;
        border: 1px solid #d1e9f5;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
        border-color: #159ed5;
        box-shadow: 0 0 0 3px rgba(21, 158, 213, 0.1);
    }

    .btn-primary {
        background: linear-gradient(90deg, #159ed5, #0d6a9f);
        border: none;
        border-radius: 6px;
        font-weight: 500;
        transition: background 0.3s ease, transform 0.1s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #0d6a9f, #159ed5);
        transform: translateY(-1px);
    }

    .btn-light {
        background-color: #ffffff;
        border: 1px solid #d1e9f5;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-light:hover {
        background-color: #e6f4fa;
        transform: translateY(-1px);
    }

    .rounded-circle {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table-responsive {
        max-height: calc(100vh - 280px);
        overflow-y: auto;
        border-bottom: 1px solid #d1e9f5;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table th {
        background: linear-gradient(90deg, #0d6a9f, #094d7a);
        color: #ffffff;
        font-weight: 600;
        padding: 0.75rem 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #094d7a;
    }

    .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        color: #37474f;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8fbfe;
    }

    .table-hover tbody tr:hover {
        background-color: #e6f4fa;
        transition: background-color 0.2s ease;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #d1e9f5;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        background: linear-gradient(90deg, #159ed5, #0d6a9f);
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
        color: #0d6a9f;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #e6f4fa;
        color: #094d7a;
    }

    .form-check-inline {
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }

    .form-check-input.day-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.1rem;
        border: 2px solid #d1e9f5;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .form-check-input.day-checkbox:checked {
        background-color: #159ed5;
        border-color: #159ed5;
    }

    .form-check-input.day-checkbox:focus {
        box-shadow: 0 0 0 3px rgba(21, 158, 213, 0.1);
    }

    .form-check-label.day-label {
        font-size: 0.9rem;
        color: #37474f;
        margin-left: 0.5rem;
        cursor: pointer;
    }

    .form-check-label.day-label:hover {
        color: #0d6a9f;
    }

    .list-group-item {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #e6f4fa;
    }

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

        .form-check-inline {
            margin-right: 0.75rem;
        }

        .form-check-input.day-checkbox {
            width: 1.1rem;
            height: 1.1rem;
        }

        .form-check-label.day-label {
            font-size: 0.85rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const limitModal = document.getElementById('limitModal');
        limitModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const limit = button.getAttribute('data-limit');
            const days = JSON.parse(button.getAttribute('data-days') || '["daily"]');
            const group = button.getAttribute('data-group');
            const date = button.getAttribute('data-date');
            const isClosed = button.getAttribute('data-is-closed') === '1';
            const name = button.getAttribute('data-name');

            limitModal.querySelector('#modal_specialization_id').value = id;
            limitModal.querySelector('#modal_daily_limit').value = limit;
            limitModal.querySelector('#modal_date').value = date;
            limitModal.querySelector('#modal_group_id').value = group;
            limitModal.querySelector('#modal_is_closed').checked = isClosed;

            const checkboxes = limitModal.querySelectorAll('.day-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = days.includes(checkbox.value);
            });

            limitModal.querySelector('.modal-title').innerHTML =
                `<i class="fas fa-tachometer-alt me-2"></i>Change Limit for ${name}`;
        });

        const dailyCheckbox = document.querySelector('#day_daily');
        const otherCheckboxes = document.querySelectorAll('.day-checkbox:not([data-exclusive])');
        dailyCheckbox.addEventListener('change', () => {
            if (dailyCheckbox.checked) {
                otherCheckboxes.forEach(cb => cb.checked = false);
            }
        });
        otherCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                if (cb.checked) {
                    dailyCheckbox.checked = false;
                }
            });
        });

        const specializationSelect = document.getElementById('specialization_id');
        const defaultLimitInput = document.getElementById('default_limit');
        specializationSelect.addEventListener('change', () => {
            const selectedOption = specializationSelect.options[specializationSelect.selectedIndex];
            const defaultLimit = selectedOption ? selectedOption.getAttribute('data-default-limit') : '';
            defaultLimitInput.value = defaultLimit || '';
        });
    });
</script>
@endsection