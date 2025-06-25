<div class="container mx-auto mt-5">
    <div class="flex justify-between">
        <!-- Today's Appointments Column -->
        <div class="bg-white shadow-md rounded-lg p-4 w-5/12 mb-5 flex-grow mx-2">
            <h5 class="text-xl font-semibold text-blue-600 mb-4">Requested Appointments</h5>
            <div class="space-y-4">
                <!-- Appointment 1 (Ongoing) -->
                <div class="appointment-item mb-3">
                    <a href="#" class="text-gray-800 hover:text-blue-500" onclick="openModal('patientDetailsModal1')">
                        <strong>{{ 'Alice Johnson' }}</strong> - <span class="text-green-500">Ongoing</span>
                        <br>
                        <small>{{ date('Y-m-d') }} 10:30</small>
                        <br>
                        <small>Purpose: Check-up</small>
                    </a>
                </div>

                <!-- Add more appointments as needed -->
            </div>
        </div>

        <div id="requestModal1" class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg p-5 w-1/3">
                <h5 class="text-lg font-bold">Appointment Request: Alice Johnson</h5>
                <p class="mt-2">Request details for Alice Johnson...</p>
                <div class="mt-4 flex space-x-2">
                    <button class="btn btn-primary" onclick="approveRequest()">✅ Approve</button>
                    <button class="btn btn-red-500" onclick="rejectRequest()">❌ Reject</button>
                </div>
                <button class="mt-4 text-red-500" onclick="closeRequestModal('requestModal1')">Close</button>
            </div>
        </div>
        
        
    </div>
</div>

