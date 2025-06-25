<!-- Appointment Request Modal -->
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



