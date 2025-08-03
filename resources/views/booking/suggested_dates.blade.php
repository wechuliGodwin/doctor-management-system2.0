@extends('layouts.dashboard')

@section('title', 'Suggested Appointments')

@section('content')
<style>
    .widget {
        background: #ffffff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }
    .modal-content {
        font-family: Arial, sans-serif;
        font-size: 14px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-primary {
        background-color: #159ed5;
        border-color: #159ed5;
    }
    .btn-primary:hover {
        background-color: #127fb0;
        border-color: #127fb0;
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="widget">
                <h2 class="text-center mb-4" style="font-family: 'Roboto', sans-serif; font-weight: 500; color: #333; font-size: 1.8rem;">
                    <i class="fas fa-calendar-alt me-2"></i>Suggested Available Dates
                </h2>
                <div class="p-4">
                    <p class="text-danger mb-3">
                        {{ $error ?? 'The selected date is not available for this specialization.' }}
                    </p>
                    <form id="suggested-dates-form" action="{{ route('booking.selectSuggestedDate') }}" method="POST">
                        @csrf
                        {{-- Hidden inputs to pass data back to the controller --}}
                        <input type="hidden" name="context" value="{{ $context ?? 'booking' }}">
                        <input type="hidden" name="appointment_id" value="{{ $appointment_id ?? '' }}">
                        <input type="hidden" name="specialization_id" value="{{ $specialization_id ?? '' }}">
                        @foreach ($form_data ?? [] as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $subKey => $subValue)
                                    <input type="hidden" name="form_data[{{ $key }}][{{ $subKey }}]" value="{{ $subValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="form_data[{{ $key }}]" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Limit</th>
                                        <th>Bookings</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($suggested_dates) && !empty($suggested_dates))
                                        @foreach ($suggested_dates as $suggestion)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($suggestion['date'])->format('m/d/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($suggestion['date'])->format('l') }}</td>
                                                <td>{{ $suggestion['limit'] }}</td>
                                                <td>{{ $suggestion['bookings'] }}</td>
                                                <td>
                                                    <button type="submit" 
                                                            name="selected_date" 
                                                            value="{{ $suggestion['date'] }}"
                                                            class="btn btn-sm btn-primary">Select</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No alternative dates available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="text-center mt-4">
                    {{-- A simple button to go back to the booking form --}}
                    <a href="{{ route('booking.add') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Form
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection