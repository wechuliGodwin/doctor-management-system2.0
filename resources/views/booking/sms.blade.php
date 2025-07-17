@extends('layouts.dashboard')

@section('title', 'SMS Reminders')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8fafc;
        min-height: 100vh;
        color: #1e293b;
    }

    .dashboard-container {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .header {
        text-align: center;
        margin-bottom: 24px;
        padding: 16px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .header h1 {
        color: #159ed5;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .cards-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .full-width {
        grid-template-columns: 1fr;
    }

    .card {
        background: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-container {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .search-input {
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
        background: #fff;
        transition: border-color 0.2s ease;
        flex: 1;
    }

    .search-input:focus {
        outline: none;
        border-color: #159ed5;
        box-shadow: 0 0 0 2px rgba(21, 158, 213, 0.1);
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background: #159ed5;
        color: white;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        padding: 6px 10px;
        font-size: 0.8rem;
    }

    .btn:hover {
        filter: brightness(110%);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .search-results {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        max-height: 200px;
        overflow-y: auto;
        margin-bottom: 16px;
        display: none;
    }

    .search-result-item {
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
    }

    .search-result-item:hover {
        background: #f1f5f9;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .selected-patients {
        max-height: 400px;
        overflow-y: auto;
    }

    .patient-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .patient-item:hover {
        background: #f1f5f9;
    }

    .patient-item:last-child {
        border-bottom: none;
    }

    .patient-info {
        display: flex;
        gap: 10px;
        align-items: center;
        flex: 1;
    }

    .patient-name,
    .patient-specialization,
    .patient-date {
        font-size: 0.9rem;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .patient-name {
        font-weight: 600;
        color: #1e293b;
    }

    .patient-specialization {
        color: #64748b;
    }

    .patient-date {
        color: #159ed5;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 8px;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-sent {
        background: #d1fae5;
        color: #065f46;
    }

    .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .template-options {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .template-option {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        background: #fff;
        transition: all 0.2s ease;
    }

    .template-option:hover {
        border-color: #159ed5;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .template-option.selected {
        border-color: #159ed5;
        background: #e6f4fa;
        color: #159ed5;
    }

    .message-input {
        width: 100%;
        min-height: 100px;
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
        resize: vertical;
        transition: border-color 0.2s ease;
    }

    .message-input:focus {
        outline: none;
        border-color: #159ed5;
        box-shadow: 0 0 0 2px rgba(21, 158, 213, 0.1);
    }

    .message-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        font-size: 0.9rem;
        color: #64748b;
    }

    .variable-chips {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .variable-chip {
        background: #159ed5;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .variable-chip:hover {
        filter: brightness(110%);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .preview-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 16px;
    }

    .preview-message {
        background: white;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        max-width: 300px;
        font-size: 0.9rem;
    }

    .delivery-log {
        max-height: 400px;
        overflow-y: auto;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
    }

    .delivery-log-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .delivery-log-table th,
    .delivery-log-table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .delivery-log-table th {
        background: #f1f5f9;
        font-weight: 600;
        color: #1e293b;
    }

    .delivery-log-table tr:hover {
        background: #f1f5f9;
    }

    .delivery-log-table .status-sent {
        color: #065f46;
        background: #d1fae5;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .delivery-log-table .status-failed {
        color: #991b1b;
        background: #fee2e2;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .send-section {
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .send-actions {
        display: flex;
        gap: 8px;
    }

    .progress-bar {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin: 8px 0;
        display: none;
    }

    .progress-fill {
        height: 100%;
        background: #159ed5;
        width: 0%;
        transition: width 0.3s ease;
    }

    .sending-status {
        font-size: 0.9rem;
        color: #64748b;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.9rem;
        margin-top: 8px;
        display: none;
    }
</style>

<div class="dashboard-container">
    <div class="header">
        <h1>SMS Reminders</h1>
    </div>
    <div class="cards-section">
        <div class="card">
            <h2 class="card-title"><i class="fas fa-users"></i> Recipients (<span id="totalPatients">0</span>)</h2>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search by name, patient number, or appointment number..." id="patientSearch" oninput="debounceSearch()">
                <button class="btn btn-secondary" onclick="searchPatients()"><i class="fas fa-search"></i> Search</button>
            </div>
            <div class="search-results" id="searchResults"></div>
            <div class="selected-patients" id="selectedPatients"></div>
        </div>
        <div class="card">
            <h2 class="card-title"><i class="fas fa-envelope"></i> Message</h2>
            <div class="template-options">
                <div class="template-option selected" onclick="selectTemplate('default')">Default</div>
                <div class="template-option" onclick="selectTemplate('urgent')">Urgent</div>
                <div class="template-option" onclick="selectTemplate('followup')">Follow-up</div>
                <div class="template-option" onclick="selectTemplate('custom')">Custom</div>
                <button class="btn btn-secondary btn-sm" onclick="saveTemplate()"><i class="fas fa-save"></i> Save Template</button>
            </div>
            <textarea id="messageText" class="message-input" placeholder="Type message..." oninput="updatePreview()">Hi {name}, reminder: appt with {specialization} on {date} at {time}. Arrive early.</textarea>
            <div class="message-footer">
                <div class="character-count"><span id="charCount">0</span>/160</div>
                <div class="variable-chips">
                    <span class="variable-chip" onclick="insertVariable('{name}')">{name}</span>
                    <span class="variable-chip" onclick="insertVariable('{specialization}')">{specialization}</span>
                    <span class="variable-chip" onclick="insertVariable('{date}')">{date}</span>
                    <span class="variable-chip" onclick="insertVariable('{time}')">{time}</span>
                </div>
            </div>
            <div class="preview-section">
                <div class="preview-message" id="previewMessage"></div>
                <small class="section-description">Preview for first recipient</small>
            </div>
            <div class="send-section">
                <div class="send-actions">
                    <button class="btn btn-primary" onclick="sendMessages()" id="sendBtn"><i class="fas fa-paper-plane"></i> Send</button>
                </div>
                <div class="progress-bar" id="progressBar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="sending-status" id="sendingStatus"></div>
                <div class="error-message" id="errorMessage"></div>
            </div>
        </div>
    </div>
    <div class="cards-section full-width">
        <div class="card">
            <h2 class="card-title"><i class="fas fa-clipboard-list"></i> Delivery Log</h2>
            <div class="delivery-log">
                <table class="delivery-log-table">
                    <thead>
                        <tr>
                            <th>Appointment Number</th>
                            <th>Specialization</th>
                            <th>Appointment Date</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Status Description</th>
                            <th>Sent At</th>
                        </tr>
                    </thead>
                    <tbody id="deliveryLog"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedPatients = @json($selectedAppointments ?? []);
    let templates = [];
    const csrfToken = '{{ csrf_token() }}';

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const debounceSearch = debounce(searchPatients, 300);

    function updateRecipientCount() {
        document.getElementById('totalPatients').textContent = selectedPatients.length;
    }

    function renderSelectedPatients() {
        const container = document.getElementById('selectedPatients');
        container.innerHTML = '';
        selectedPatients.forEach(patient => {
            const patientItem = document.createElement('div');
            patientItem.className = 'patient-item';
            patientItem.innerHTML = `
                <div class="patient-info">
                    <span class="patient-name">${patient.full_name}</span>
                    <span class="patient-specialization">${patient.specialization}</span>
                    <span class="patient-date">${patient.appointment_date}</span>
                </div>
                <button class="btn btn-danger" onclick="removePatient(${patient.id})"><i class="fas fa-trash"></i></button>
            `;
            container.appendChild(patientItem);
        });
        updateRecipientCount();
        updatePreview();
    }

    function removePatient(id) {
        selectedPatients = selectedPatients.filter(p => p.id !== id);
        renderSelectedPatients();
    }

    function searchPatients() {
        const query = document.getElementById('patientSearch').value;
        if (query.length < 3) {
            document.getElementById('searchResults').style.display = 'none';
            return;
        }

        fetch(`{{ route('booking.searchPatients') }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const resultsContainer = document.getElementById('searchResults');
            resultsContainer.innerHTML = '';
            if (data.data && data.data.length) {
                data.data.forEach(appointment => {
                    if (!selectedPatients.find(p => p.id === appointment.id)) {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'search-result-item';
                        resultItem.innerHTML = `
                            ${appointment.full_name} (Patient: ${appointment.patient_number}, Appt: ${appointment.appointment_number}, ${appointment.specialization}) - ${appointment.appointment_date}
                        `;
                        resultItem.onclick = () => {
                            selectedPatients.push({
                                id: appointment.id,
                                full_name: appointment.full_name,
                                phone: appointment.phone,
                                specialization: appointment.specialization,
                                appointment_date: appointment.appointment_date,
                                appointment_time: appointment.appointment_time,
                                appointment_number: appointment.appointment_number,
                                patient_number: appointment.patient_number
                            });
                            renderSelectedPatients();
                            resultsContainer.style.display = 'none';
                            document.getElementById('patientSearch').value = '';
                        };
                        resultsContainer.appendChild(resultItem);
                    }
                });
                resultsContainer.style.display = 'block';
            } else {
                resultsContainer.innerHTML = '<div class="search-result-item">No results found</div>';
                resultsContainer.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error searching patients:', error);
            document.getElementById('errorMessage').textContent = 'Error searching patients';
            document.getElementById('errorMessage').style.display = 'block';
        });
    }

    function selectTemplate(type) {
        document.querySelectorAll('.template-option').forEach(opt => opt.classList.remove('selected'));
        document.querySelector(`.template-option[onclick="selectTemplate('${type}')"]`).classList.add('selected');

        const template = templates.find(t => t.type === type);
        const messageText = document.getElementById('messageText');
        if (template) {
            messageText.value = template.content;
        } else {
            messageText.value = type === 'default' ? 'Hi {name}, reminder: appt with {specialization} on {date} at {time}. Arrive early.' :
                type === 'urgent' ? 'Urgent: {name}, your appt with {specialization} is on {date} at {time}. Please confirm.' :
                type === 'followup' ? 'Hi {name}, follow-up appt with {specialization} on {date} at {time}.' :
                '';
        }
        updatePreview();
        updateCharCount();
    }

    function saveTemplate() {
        const name = prompt('Enter template name:');
        if (!name) return;

        const message = document.getElementById('messageText').value;
        const type = document.querySelector('.template-option.selected').textContent.toLowerCase();

        fetch('{{ route("booking.saveTemplate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                name: name,
                type: type,
                content: message,
                is_default: type === 'default'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Template saved successfully!');
                fetchTemplates();
            } else {
                console.error('Template save failed:', data.error);
                document.getElementById('errorMessage').textContent = data.error || 'Error saving template';
                document.getElementById('errorMessage').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error saving template:', error);
            document.getElementById('errorMessage').textContent = 'Error saving template';
            document.getElementById('errorMessage').style.display = 'block';
        });
    }

    function fetchTemplates() {
        fetch('{{ route("booking.getTemplates") }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            templates = data.templates;
            const currentType = document.querySelector('.template-option.selected').textContent.toLowerCase();
            selectTemplate(currentType);
        })
        .catch(error => {
            console.error('Error fetching templates:', error);
            document.getElementById('errorMessage').textContent = 'Error fetching templates';
            document.getElementById('errorMessage').style.display = 'block';
        });
    }

    function updatePreview() {
        const message = document.getElementById('messageText').value;
        const preview = document.getElementById('previewMessage');
        if (selectedPatients.length > 0) {
            let previewMessage = message;
            const patient = selectedPatients[0];
            previewMessage = previewMessage
                .replace('{name}', patient.full_name)
                .replace('{specialization}', patient.specialization)
                .replace('{date}', patient.appointment_date)
                .replace('{time}', patient.appointment_time);
            preview.innerHTML = previewMessage;
        } else {
            preview.innerHTML = 'No recipients selected';
        }
        updateCharCount();
    }

    function updateCharCount() {
        const message = document.getElementById('messageText').value;
        document.getElementById('charCount').textContent = message.length;
    }

    function insertVariable(variable) {
        const textarea = document.getElementById('messageText');
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        textarea.value = textarea.value.substring(0, startPos) + variable + textarea.value.substring(endPos);
        updatePreview();
        updateCharCount();
    }

    function fetchDeliveryLog() {
        fetch('{{ route("booking.getDeliveryLog") }}', {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Delivery log data:', data); // Debug response
            const deliveryLog = document.getElementById('deliveryLog');
            deliveryLog.innerHTML = '';
            if (data.logs && data.logs.length) {
                data.logs.forEach(log => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${log.appointment_number}</td>
                        <td>${log.specialization}</td>
                        <td>${log.appointment_date}</td>
                        <td>${log.message}</td>
                        <td><span class="status-${log.status}">${log.status === 'sent' ? 'Sent' : 'Failed'}</span></td>
                        <td>${log['status description'] || '-'}</td>
                        <td>${log.sent_at}</td>
                    `;
                    deliveryLog.appendChild(row);
                });
            } else {
                deliveryLog.innerHTML = '<tr><td colspan="7">No logs found</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching delivery log:', error);
            document.getElementById('errorMessage').textContent = 'Error fetching delivery log';
            document.getElementById('errorMessage').style.display = 'block';
        });
    }

    function sendMessages() {
        if (selectedPatients.length === 0) {
            document.getElementById('errorMessage').textContent = 'Please select at least one recipient';
            document.getElementById('errorMessage').style.display = 'block';
            return;
        }

        const message = document.getElementById('messageText').value;
        if (!message) {
            document.getElementById('errorMessage').textContent = 'Please enter a message';
            document.getElementById('errorMessage').style.display = 'block';
            return;
        }

        document.getElementById('sendBtn').disabled = true;
        document.getElementById('progressBar').style.display = 'block';
        document.getElementById('sendingStatus').textContent = 'Sending...';
        document.getElementById('errorMessage').style.display = 'none';

        fetch('{{ route("booking.sendBulkSMS") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                recipients: selectedPatients,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Send SMS response:', data); // Debug response
            document.getElementById('sendBtn').disabled = false;
            document.getElementById('progressBar').style.display = 'none';
            document.getElementById('sendingStatus').textContent = `Sent ${data.success_count} messages, ${data.failed_count} failed`;

            const deliveryLog = document.getElementById('deliveryLog');
            if (data.results && data.results.length) {
                data.results.forEach(result => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${result.appointment_number}</td>
                        <td>${result.specialization}</td>
                        <td>${result.appointment_date}</td>
                        <td>${result.message}</td>
                        <td><span class="status-${result.status_code === '1000' ? 'sent' : 'failed'}">${result.status_code === '1000' ? 'Sent' : 'Failed'}</span></td>
                        <td>${result.status_desc || '-'}</td>
                        <td>${result.sent_at}</td>
                    `;
                    deliveryLog.insertBefore(row, deliveryLog.firstChild);
                });
            } else {
                console.warn('No results in response:', data);
            }

            if (data.success_count > 0) {
                selectedPatients = [];
                renderSelectedPatients();
            }
            fetchDeliveryLog(); // Refresh delivery log
        })
        .catch(error => {
            console.error('Error sending messages:', error);
            document.getElementById('sendBtn').disabled = false;
            document.getElementById('progressBar').style.display = 'none';
            document.getElementById('sendingStatus').textContent = '';
            document.getElementById('errorMessage').textContent = 'Error sending messages';
            document.getElementById('errorMessage').style.display = 'block';
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        renderSelectedPatients();
        fetchTemplates();
        fetchDeliveryLog(); // Load delivery log on page load
        updatePreview();
        updateCharCount();
    });
</script>
@endsection