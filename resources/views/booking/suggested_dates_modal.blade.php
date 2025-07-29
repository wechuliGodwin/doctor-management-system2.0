<!-- booking/suggested_dates_modal.blade.php -->
<div class="modal fade {{ session('modal_target') === 'suggestedDatesModal' ? 'show d-block' : '' }}" 
     id="suggestedDatesModal" 
     tabindex="-1" 
     aria-labelledby="suggestedDatesModalLabel" 
     aria-hidden="true" 
     {{ session('modal_target') === 'suggestedDatesModal' ? 'style="background-color: rgba(0,0,0,0.5);"' : '' }}>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="suggestedDatesModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i>Suggested Available Dates
                </h5>
                <button type="button" 
                        class="btn-close btn-close-white" 
                        data-bs-dismiss="modal" 
                        aria-label="Close" 
                        onclick="window.location.href='{{ route('booking.clearSuggestedDates') }}'">
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-danger mb-3">
                    {{ $error ?? session('error', 'The selected date is not available for this specialization.') }}
                </p>
                <form id="suggested-dates-form" action="{{ route('booking.selectSuggestedDate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="context" value="{{ $context ?? session('modal_context', 'booking') }}">
                    <input type="hidden" name="appointment_id" value="{{ $appointment_id ?? session('appointment_id', '') }}">
                    <input type="hidden" name="specialization_id" value="{{ $specialization_id ?? session('specialization_id', '') }}">
                    @foreach ($form_data ?? session('_old_input', []) as $key => $value)
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
            <div class="modal-footer border-0">
                <button type="button" 
                        class="btn btn-outline-secondary shadow-sm" 
                        data-bs-dismiss="modal"
                        onclick="window.location.href='{{ route('booking.clearSuggestedDates') }}'">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
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
    .btn-close-white {
        filter: invert(1);
    }
</style>