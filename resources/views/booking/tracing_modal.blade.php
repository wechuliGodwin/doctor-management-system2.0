<!-- Hidden popup template -->
<div id="tracingTemplate" style="display: none;">
    <div class="modal-overlay" style="
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    ">
        <div class="popup-container" style="
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            min-width: 600px;
            max-width: 90%;
            font-family: Arial, sans-serif;
        ">
            <h2 id="tracingTitle" style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">
                Update Tracing Info
            </h2>
            <div id="patientInfo" style="margin-bottom: 16px; font-size: 0.9rem; color: #333;">
                <strong>Patient Name:</strong> <span id="patientName">-</span> <br>
                <strong>Patient Number:</strong> <span id="patientNumber">-</span>
            </div>

            <form id="tracingForm" style="display: flex; flex-direction: column; gap: 14px;">
                <input type="hidden" id="appointment_id" name="appointment_id">

                <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                    <div style="flex: 1 1 200px;">
                        <label for="traceDate" style="display: block; font-size: 0.85rem; font-weight: 600;">Tracing Date:</label>
                        <input type="datetime-local" id="traceDate" name="traceDate" required readonly
                            style="width: 100%; padding: 6px; font-size: 0.85rem; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <div style="flex: 1 1 200px;">
                        <label for="status" style="display: block; font-size: 0.85rem; font-weight: 600;">Status:</label>
                        <select id="status" name="status" required
                            style="width: 100%; padding: 6px; font-size: 0.85rem; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="" disabled selected>Select</option>
                            <option value="contacted">Contacted</option>
                            <option value="no response">
                                No Response</option>
                            <!-- <option value="other">Other</option> -->
                        </select>
                    </div>
                </div>

                <div>
                    <label for="message" style="display: block; font-size: 0.85rem; font-weight: 600;">Message (Optional):</label>
                    <textarea id="message" name="message" rows="3"
                        style="width: 100%; padding: 6px; font-size: 0.85rem; border: 1px solid #ccc; border-radius: 4px; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="submit"
                        style="padding: 6px 16px; background-color: #159ed5; color: white; border: none; border-radius: 4px; font-size: 0.85rem; cursor: pointer;">Submit</button>
                    <button type="button" id="closeTracing"
                        style="padding: 6px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; font-size: 0.85rem; cursor: pointer;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const template = document.getElementById('tracingTemplate');
        let currentModal = null;

        window.openTracingModal = function(appointmentId, event) {
            event.preventDefault();
            event.stopPropagation();

            if (currentModal) {
                currentModal.remove();
                currentModal = null;
            }

            const clone = template.querySelector('.modal-overlay').cloneNode(true);
            document.body.appendChild(clone);
            currentModal = clone;

            const form = clone.querySelector('#tracingForm');
            form.querySelector('#appointment_id').value = appointmentId;

            const now = new Date();
            const offset = 3 * 60;
            const localDatetime = new Date(now.getTime() + offset * 60000).toISOString().slice(0, 16);
            form.querySelector('#traceDate').value = localDatetime;

            // Set patient name & number
            const table = $('.table').DataTable();
            const rowData = table.row(event.target.closest('tr')).data();

            const patientName = rowData.full_name || 'Unknown';
            const patientNumber = rowData.patient_number || 'N/A';

            clone.querySelector('#patientName').textContent = patientName;
            clone.querySelector('#patientNumber').textContent = patientNumber;

            // Submit
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const data = {
                    appointment_id: form.appointment_id.value,
                    tracing_date: form.traceDate.value,
                    status: form.status.value,
                    message: form.message.value.trim(),
                    _token: '{{ csrf_token() }}'
                };

                fetch('{{ route("booking.save-tracing") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(response.statusText);
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message || 'Tracing info saved!');
                        if (currentModal) currentModal.remove();
                        currentModal = null;
                        $('.table').DataTable().ajax.reload(null, false); // Reload table without resetting pagination
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to save tracing info.');
                    });
            });

            // Cancel
            clone.querySelector('#closeTracing').addEventListener('click', function() {
                if (currentModal) currentModal.remove();
                currentModal = null;
                form.reset();
            });

            // Click outside
            clone.addEventListener('click', function(e) {
                if (e.target === clone) {
                    if (currentModal) currentModal.remove();
                    currentModal = null;
                    form.reset();
                }
            });
        };
    });
</script>