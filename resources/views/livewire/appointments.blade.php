<div>
    <!-- Feedback Message -->
    @if ($feedbackMessage)
        <div
            class="mb-4 p-2 rounded {{ $feedbackType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $feedbackMessage }}
        </div>
    @endif

    <!-- Appointments Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse bg-white shadow-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        ID</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        HMIS Patient Number</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        Prescribe Medicine</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        Patient Name</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        Phone No</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        DOB</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        Patient Uploads</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        Appointment Date</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        Upload</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider border-b border-gray-200">
                        Meeting</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($appointments as $appointment)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            {{ $appointment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            {{ $appointment->hmis_patient_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            <button class="text-blue-500 hover:underline"
                                onclick="openPrescriptionModal({{ $appointment->id }}, '{{ addslashes($appointment->patient_name) }}')">
                                Write Prescription
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            {{ $appointment->patient_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            {{ $appointment->user->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            {{ $appointment->user->dob ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            @if ($appointment->user && $appointment->user->uploads->isNotEmpty())
                                @foreach ($appointment->user->uploads as $upload)
                                    <div>
                                        <a href="{{ asset($upload->filepath) }}" class="text-blue-500 underline">
                                            View
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                No uploads
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            {{ $appointment->appointment_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            <input type="file" wire:model="file" class="mb-2" />
                            <button
                                wire:click="uploadFile({{ $appointment->id }})"
                                class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                                Upload
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                            @if ($appointment->host_start_url)
                                <a href="{{ $appointment->host_start_url }}" target="_blank" class="text-green-500 hover:underline">
                                    Start Meeting
                                </a>
                            @else
                                <span class="text-gray-400">No meeting link</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Prescription Modal -->
    <div id="prescriptionModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <form id="prescriptionForm" method="POST" action="{{ route('save-prescription') }}">
                @csrf
                <input type="hidden" name="appointment_id" id="appointment_id">

                <div class="mb-4">
                    <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis</label>
                    <input type="text" id="diagnosis" name="diagnosis" class="mt-1 p-2 w-full border border-gray-300 rounded" placeholder="Enter the diagnosis...">
                </div>

                <div class="mb-4">
                    <label for="prescription" class="block text-sm font-medium text-gray-700">Prescription</label>
                    <textarea id="prescription" name="prescription" rows="4" class="mt-1 p-2 w-full border border-gray-300 rounded" placeholder="Enter the prescription details here..."></textarea>
                </div>

                <div class="mb-4">
                    <label for="dosage" class="block text-sm font-medium text-gray-700">Dosage</label>
                    <input type="text" id="dosage" name="dosage" class="mt-1 p-2 w-full border border-gray-300 rounded" placeholder="Enter the dosage...">
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Frequency</label>
                    <input type="text" id="quantity" name="quantity" class="mt-1 p-2 w-full border border-gray-300 rounded" placeholder="Enter the quantity...">
                </div>

                <div class="mb-4">
                    <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
                    <input type="text" id="duration" name="duration" class="mt-1 p-2 w-full border border-gray-300 rounded" placeholder="Enter the duration...">
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save Prescription</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Function to open the prescription modal
        function openPrescriptionModal(appointmentId, patientName) {
            console.log("Opening modal for Appointment ID:", appointmentId, "Patient Name:", patientName);
            document.getElementById('appointment_id').value = appointmentId;
            document.getElementById('prescriptionModal').classList.remove('hidden');
        }
        // Function to close the prescription modal
        function closeModal() {
            document.getElementById('prescriptionModal').classList.add('hidden');
        }
    </script>
</div>
