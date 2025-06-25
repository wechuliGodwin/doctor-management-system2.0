<div class="print-header" style="display: none;">
    <h2>Patient Search Results - {{ request('search') ?: 'All' }}</h2>
    <hr>
</div>
<table id="search-results-table" class="table table-sm table-hover mb-0">
    <thead>
        <tr>
            <th scope="col">Full Name</th>
            <th scope="col">Patient Number</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Appointment Date</th>
            <th scope="col">Specialization</th>
            <th scope="col">Status</th>
            <th scope="col" class="no-print">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if (request('search') && $appointments->isNotEmpty())
            @foreach ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->full_name ?? 'N/A' }}</td>
                    <td>{{ $appointment->patient_number ?? 'N/A' }}</td>
                    <td>{{ $appointment->email ?? 'N/A' }}</td>
                    <td>{{ $appointment->phone ?? 'N/A' }}</td>
                    <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : 'N/A' }}</td>
  <td>{{ $appointment->specialization ?? 'N/A' }}</td>
                    <td>{{ ucfirst($appointment->appointment_status ?? 'N/A') }}</td>
                    <td class="no-print">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#viewAppointmentModal{{ $appointment->id }}">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <div>
                            <button type="button" class="btn btn-sm btn-info openRescheduleModal"
                                data-bs-toggle="modal" data-bs-target="#rescheduleAppointmentModal"
                                data-action="{{ route('booking.reschedule', [$appointment->id, 'all']) }}"
                                data-id="{{ $appointment->id }}"
                                data-full_name="{{ $appointment->full_name }}"
                                data-patient_number="{{ $appointment->patient_number }}"
                                data-email="{{ $appointment->email }}"
                                data-phone="{{ $appointment->phone }}"
                                data-appointment_date="{{ $appointment->appointment_date }}"
                                data-appointment_time="{{ $appointment->appointment_time }}"
                                data-specialization="{{ $appointment->specialization }}"
                                data-doctor_name="{{ $appointment->doctor_name }}"
                                data-booking_type="{{ $appointment->booking_type }}">
                                <i class="fas fa-calendar-alt"></i> 
                                Reschedule
                            </button>
                        </div>
                    </td>
                   
        
  
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="empty-state">
                    @if (request('search'))
                        No results found for "{{ request('search') }}".
                    @else
                        Enter a search term to find patient records.
                    @endif
                </td>
            </tr>
        @endif
    </tbody>
</table>
