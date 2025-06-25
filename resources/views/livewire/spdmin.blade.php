@extends('layouts.SuperHeader')

@section('content')
<div class="overflow-x-auto">
    <table class="min-w-full border-collapse bg-white shadow-lg">
        <thead class="bg-blue-700 text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">HMIS Patient Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Mpesa Receipt No</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Patient Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Appointment Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Payment Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Phone No</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">DOB</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Actions</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Meeting</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">Available Doctors</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($appointments as $appointment)
            <tr class="hover:bg-blue-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $appointment->hmis_patient_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $appointment->mpesa_code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $appointment->patient_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $appointment->appointment_date }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $appointment->payment_status === 'Completed' ? 'text-green-600' : ($appointment->payment_status === 'Pending' ? 'text-yellow-600' : ($appointment->payment_status === 'Cancelled' ? 'text-red-600' : 'text-gray-600')) }} border-b border-gray-200">{{ $appointment->payment_status }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $appointment->user->phone ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">{{ $appointment->user->dob ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 border-b border-gray-200">
                    <button class="hover:underline" onclick="showEditModal({{ json_encode($appointment) }})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 border-b border-gray-200">
                    @if ($appointment->meeting_id)
                        <span class="hover:underline cursor-pointer" onclick="showMeetingPopup('{{ $appointment->meeting_id }}')">
                            Meeting Link
                        </span>
                    @else
                        <span class="text-gray-500">No meeting link</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                    <form id="allocateDoctorForm-{{ $appointment->hmis_patient_number }}" onsubmit="return allocateDoctor(event, '{{ $appointment->hmis_patient_number }}')">
                        <select name="doctor_id" id="doctor_id-{{ $appointment->hmis_patient_number }}" class="border rounded p-1" onchange="updateTimeSlots('{{ $appointment->hmis_patient_number }}', this.value)">
                            <option value="">Available Doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
                            @endforeach
                        </select>
                        <select name="time_slot" id="time_slot-{{ $appointment->hmis_patient_number }}" class="border rounded p-1 mt-2 lg:mt-0">
                            <option value="">Select Time Slot</option>
                        </select>
                        <button type="submit" class="ml-2 bg-blue-500 text-white rounded px-2 py-1 mt-2 lg:mt-0">Save</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="meetingPopup" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">Meeting Link</h3>
        <p id="meetingLinkText" class="text-gray-700 break-all mb-4"></p>
        <div class="flex justify-end">
            <button onclick="closeMeetingPopup()" class="px-4 py-2 bg-gray-300 text-black rounded">Close</button>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full" style="max-height: 400px; overflow-y: auto;">
        <h2 class="text-lg font-semibold mb-4">Edit Appointment</h2>
        <form id="editForm" method="POST" action="{{ route('appointments.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_appointment_id" name="appointment_id">
            <div class="mb-4">
                <label for="hmis_number" class="block text-gray-700">HMIS Patient Number:</label>
                <input type="text" id="hmis_number" name="hmis_number" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label for="patient_name" class="block text-gray-700">Patient Name:</label>
                <input type="text" id="patient_name" name="patient_name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Meeting Link:</label>
                <p id="meeting_link" class="mt-1 block text-gray-600">Generated Meeting Link will appear here.</p>
            </div>
            <div class="mb-4">
                <label for="appointment_date" class="block text-gray-700">Appointment Date:</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label for="payment_status" class="block text-gray-700">Payment Status:</label>
                <select id="payment_status" name="payment_status" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="notes" class="block text-gray-700">Notes:</label>
                <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></textarea>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded mr-2">Save</button>
                <button type="button" class="px-4 py-2 bg-gray-300 text-black rounded" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showEditModal(appointment) {
        document.getElementById('edit_appointment_id').value = appointment.id;
        document.getElementById('hmis_number').value = appointment.hmis_patient_number;
        document.getElementById('patient_name').value = appointment.patient_name;
        // Convert appointment_date to Y-m-dTH:i format for datetime-local
        document.getElementById('appointment_date').value = appointment.appointment_date.replace(' ', 'T').slice(0, 16);
        document.getElementById('payment_status').value = appointment.payment_status;
        document.getElementById('notes').value = appointment.notes;
        document.getElementById('meeting_link').innerHTML = appointment.meeting_id ? 
        `<a href="${appointment.meeting_id}" target="_blank">${appointment.meeting_id}</a>` : 
        'Generated Meeting Link will appear here.';
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function allocateDoctor(event, hmisNumber) {
        event.preventDefault();
        const form = event.target;
        const doctorId = form.querySelector('[name="doctor_id"]').value;
        const timeSlot = form.querySelector('[name="time_slot"]').value;

        if (!doctorId || !timeSlot) {
            alert('Please select both a doctor and a time slot.');
            return false;
        }

        // Perform AJAX call to allocate doctor (You will need to implement the backend logic)
        alert(`Doctor ${doctorId} allocated for ${hmisNumber} at ${timeSlot}.`);
        return true;
    }

    function showMeetingPopup(link) {
        document.getElementById('meetingLinkText').textContent = link;
        document.getElementById('meetingPopup').classList.remove('hidden');
    }

    function closeMeetingPopup() {
        document.getElementById('meetingPopup').classList.add('hidden');
    }

    function updateTimeSlots(hmisNumber, doctorId) {
        const timeSlotSelect = document.getElementById(`time_slot-${hmisNumber}`);
        timeSlotSelect.innerHTML = ''; // Clear previous options

        // Get available slots for the selected doctor
        const doctors = @json($doctors);
        const selectedDoctor = doctors.find(doc => doc.id == doctorId);
        if (selectedDoctor) {
            selectedDoctor.available_slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                timeSlotSelect.appendChild(option);
            });
        }
    }
</script>
@endsection

