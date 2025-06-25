<!-- Patient Details Modal -->
<div id="patientDetailsModal1" class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-5 w-1/3">
        <h5 class="text-lg font-bold">Patient Details: John Doe</h5>
        <p class="mt-2">Patient details for John Doe...</p>
        <div class="mt-4 flex space-x-2">
            <button class="btn btn-primary" onclick="chat()">💬 Chat</button>
            <button class="btn btn-success" onclick="call()">📞 Call</button>
            <button class="btn btn-secondary" onclick="document()">📄 Document</button>
        </div>
        <button class="mt-4 text-red-500" onclick="closeModal('patientDetailsModal1')">Close</button>
    </div>
</div>

