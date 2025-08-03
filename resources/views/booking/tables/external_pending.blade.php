<!-- External Pending Appointments Table -->
<div class="table-responsive">
    <table class="table table-bordered table-sm table-hover mb-0">
        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th>S.No</th>
                <th>Appointment No</th>
                <th>Pt Name</th>
                <th>Pt No.</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Date</th>
                <th>Specialization</th>
                <th>Appointment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointments as $index => $appointment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $appointment->appointment_number }}</td>
                    <td>{{ $appointment->full_name }}</td>
                    <td>{{ $appointment->patient_number }}</td>
                    <td>{{ $appointment->phone }}</td>
                    <td>{{ $appointment->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('m/d/Y') }}</td>
                    <td>{{ $appointment->specialization }}</td>
                    <td>{{ ucfirst($appointment->appointment_status) }}</td>
                    <td>
                        @if ($appointment->appointment_status === 'pending')
                            <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $appointment->appointment_number }}">
                                <i class="fas fa-check me-1"></i>Approve
                            </button>
                            <button class="btn btn-sm btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $appointment->id }}">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="fas fa-exclamation-circle me-1"></i>No appointments found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<style>
    .table-responsive {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        border: none;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }

    .table-sm th {
        background: #343a40;
        color: #ffffff;
        font-weight: 600;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: #e6eef5;
    }

    .btn-sm {
        padding: 0.2rem 0.5rem;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #159ed5;
        border-color: #159ed5;
    }

    .btn-primary:hover {
        background-color: #127da8;
        border-color: #127da8;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .modal-header {
        background: linear-gradient(90deg, #159ed5 0%, #159ed5 100%);
        border-bottom: none;
    }

    .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .form-control-sm {
        padding: 0.2rem 0.5rem;
        font-size: 0.85rem;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }

    .form-control-sm textarea {
        height: auto;
        min-height: 60px;
    }

    .form-label {
        font-size: 0.85rem;
        color: #333;
        font-weight: 500;
    }

    .form-check-label {
        font-size: 0.85rem;
    }
</style>

@forelse ($appointments as $index => $appointment)
    @if ($appointment->appointment_status === 'pending')
        <div class="modal fade {{ session('modal_target') === 'approveModal' . $appointment->appointment_number ? 'show d-block' : '' }}"
             id="approveModal{{ $appointment->appointment_number }}" tabindex="-1"
             aria-labelledby="approveModalLabel{{ $appointment->appointment_number }}" aria-hidden="true"
             {{ session('modal_target') === 'approveModal' . $appointment->appointment_number ? 'style="background-color: rgba(0,0,0,0.5);"' : '' }}>
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white py-2">
                        <h5 class="modal-title" id="approveModalLabel{{ $appointment->appointment_number }}">
                            Approve: {{ $appointment->appointment_number }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('booking.approveExternal', $appointment->appointment_number) }}"
                          method="POST" id="approve-form-{{ $appointment->appointment_number }}">
                        @csrf
                        <div class="modal-body py-3">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @elseif (session('error') && session('modal_context') === 'approve' && session('appointment_id') == $appointment->appointment_number)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row g-3">
                                <!-- Patient Details -->
                                <div class="col-md-6">
                                    <label for="full_name_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                                           id="full_name_{{ $appointment->appointment_number }}" name="full_name"
                                           value="{{ old('full_name', $appointment->full_name) }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                           id="phone_{{ $appointment->appointment_number }}" name="phone"
                                           value="{{ old('phone', $appointment->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Email</label>
                                    <input type="email" class="form-control form-control-sm {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                           id="email_{{ $appointment->appointment_number }}" name="email"
                                           value="{{ old('email', $appointment->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="patient_number_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Patient Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('patient_number') ? 'is-invalid' : '' }}"
                                           id="patient_number_{{ $appointment->appointment_number }}" name="patient_number"
                                           value="{{ old('patient_number', $appointment->patient_number) }}" required>
                                    @error('patient_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Appointment Details -->
                                <div class="col-md-6">
                                    <label for="doctor_name_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Doctor</label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('doctor_name') ? 'is-invalid' : '' }}"
                                           id="doctor_name_{{ $appointment->appointment_number }}" name="doctor_name"
                                           value="{{ old('doctor_name') }}">
                                    @error('doctor_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_date_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm {{ $errors->has('appointment_date') ? 'is-invalid' : '' }}"
                                           id="appointment_date_{{ $appointment->appointment_number }}" name="appointment_date"
                                           value="{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d')) }}"
                                           required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_time_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Time</label>
                                    <input type="time" class="form-control form-control-sm {{ $errors->has('appointment_time') ? 'is-invalid' : '' }}"
                                           id="appointment_time_{{ $appointment->appointment_number }}" name="appointment_time"
                                           value="{{ old('appointment_time') }}">
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="booking_type_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Booking Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm {{ $errors->has('booking_type') ? 'is-invalid' : '' }}"
                                            id="booking_type_{{ $appointment->appointment_number }}" name="booking_type" required>
                                        <option value="new" {{ old('booking_type') === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="review" {{ old('booking_type') === 'review' ? 'selected' : '' }}>Review</option>
                                        <option value="postop" {{ old('booking_type') === 'postop' ? 'selected' : '' }}>Post-Op</option>
                                        <option value="external" {{ old('booking_type', 'external') === 'external' ? 'selected' : '' }}>External</option>
                                    </select>
                                    @error('booking_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="specialization_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Specialization <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm {{ $errors->has('specialization') ? 'is-invalid' : '' }}"
                                            id="specialization_{{ $appointment->appointment_number }}" name="specialization" required>
                                        <option value="">Select Specialization</option>
                                        @foreach($specializations as $specialization)
                                            <option value="{{ $specialization->name }}"
                                                    {{ old('specialization', $appointment->specialization) === $specialization->name ? 'selected' : '' }}>
                                                {{ $specialization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('specialization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="hospital_branch_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Hospital Branch <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm {{ $errors->has('hospital_branch') ? 'is-invalid' : '' }}"
                                            id="hospital_branch_{{ $appointment->appointment_number }}" name="hospital_branch" required>
                                        <option value="" {{ old('hospital_branch') ? '' : 'selected' }} disabled>Select Branch</option>
                                        <option value="kijabe" {{ old('hospital_branch') == 'kijabe' ? 'selected' : '' }}>Kijabe</option>
                                        <option value="westlands" {{ old('hospital_branch') == 'westlands' ? 'selected' : '' }}>Westlands</option>
                                        <option value="naivasha" {{ old('hospital_branch') == 'naivasha' ? 'selected' : '' }}>Naivasha</option>
                                        <option value="marira" {{ old('hospital_branch') == 'marira' ? 'selected' : '' }}>Marira</option>
                                    </select>
                                    @error('hospital_branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="notes_{{ $appointment->appointment_number }}" class="form-label small fw-bold">Notes <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-sm {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                                              id="notes_{{ $appointment->appointment_number }}" name="notes" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="patient_notified_{{ $appointment->appointment_number }}" name="patient_notified" value="1"
                                               {{ old('patient_notified') ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="patient_notified_{{ $appointment->appointment_number }}">
                                            Patient Notified <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-tertiary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $appointment->id }}" tabindex="-1"
             aria-labelledby="deleteModalLabel{{ $appointment->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white py-2">
                        <h5 class="modal-title" id="deleteModalLabel{{ $appointment->id }}">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        Are you sure you want to delete the appointment for <strong>{{ $appointment->full_name }}</strong> with
                        Appointment No: <strong>{{ $appointment->appointment_number }}</strong>?
                        This action cannot be undone.
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-tertiary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('booking.delete', $appointment->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="status" value="external_pending">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@empty
@endforelse

@push('scripts')
    <!-- Minimal JavaScript for DataTable (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTable initialization (minimal, if required)
            $('.table').DataTable({
                responsive: true,
                ordering: true,
                searching: true,
                pageLength: 50,
                lengthMenu: [[50, 100, 200, -1], ["50", "100", "200", "All"]],
                order: [[6, 'desc']],
                language: {
                    emptyTable: 'No pending appointments found.'
                }
            });
        });
    </script>
@endpush