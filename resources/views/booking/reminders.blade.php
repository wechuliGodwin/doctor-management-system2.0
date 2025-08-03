@extends('layouts.dashboard')

@section('title', 'SMS Reminders')

@section('content')
@include('booking.partials.styles')

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
                @if (Auth::guard('booking')->user()->role === 'superadmin')
                <button class="btn btn-secondary btn-sm" onclick="manageTemplates()"><i class="fas fa-cog"></i> Manage Templates</button>
                @endif
            </div>
            <textarea id="messageText" class="message-input" placeholder="Type message..." oninput="updatePreview()"></textarea>
            <div class="message-footer">
                <div class="character-count"><span id="charCount">0</span>/3000</div>
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
                    @if (in_array(Auth::guard('booking')->user()->role, ['superadmin', 'admin']))
                    <button class="btn btn-primary" onclick="sendMessages()" id="sendBtn"><i class="fas fa-paper-plane"></i> Send</button>
                    @endif
                </div>
                <div class="progress-bar" id="progressBar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="sending-status" id="sendingStatus"></div>
                <div class="error-message" id="errorMessage"></div>
            </div>
        </div>
    </div>
</div>

@if (Auth::guard('booking')->user()->role === 'superadmin')
<div id="templateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Manage Templates</h2>
            <button class="modal-close" onclick="closeTemplateModal()">×</button>
        </div>
        <div class="modal-body">
            <div id="templateList" class="template-list"></div>
            <div class="template-form">
                <label for="templateName">Template Name</label>
                <input type="text" id="templateName" placeholder="Enter template name">
                <label for="templateType">Template Type</label>
                <select id="templateType">
                    <option value="default">Default</option>
                    <option value="urgent">Urgent</option>
                    <option value="followup">Follow-up</option>
                </select>
                <label for="templateContent">Template Content</label>
                <textarea id="templateContent" placeholder="Enter template content (max 3000 characters)" maxlength="3000"></textarea>
                <div class="modal-actions">
                    <button class="btn btn-primary" onclick="saveTemplate()">Save Template</button>
                    <button class="btn btn-secondary" onclick="closeTemplateModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    let selectedPatients = @json($selectedAppointments ?? []);
    let templates = [];
    const csrfToken = '{{ csrf_token() }}';
    const userRole = '{{ Auth::guard('booking')->user()->role }}';

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
                        <span class="patient-phone">${patient.phone}</span>
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

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.toLocaleString('default', {
            month: 'long'
        });
        const year = date.getFullYear();

        let suffix = 'th';
        if (day === 1 || day === 21 || day === 31) {
            suffix = 'st';
        } else if (day === 2 || day === 22) {
            suffix = 'nd';
        } else if (day === 3 || day === 23) {
            suffix = 'rd';
        }

        return `${day}${suffix} ${month}, ${year}`;
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

        const messageText = document.getElementById('messageText');
        const isReadOnly = ['default', 'urgent', 'followup'].includes(type);

        // Set textarea content and readonly state
        if (type === 'custom') {
            messageText.value = '';
            messageText.removeAttribute('readonly');
            messageText.placeholder = 'Type message...';
        } else {
            const template = templates.find(t => t.type === type);
            messageText.value = template ? template.content : '';
            messageText.setAttribute('readonly', 'readonly');
            messageText.placeholder = 'This template is read-only. Select Custom to enter custom message.';
        }

        updatePreview();
        updateCharCount();
    }

    function manageTemplates() {
        if (userRole !== 'superadmin') {
            alert('Only superadmins can manage templates.');
            return;
        }

        fetchTemplates();
        const modal = document.getElementById('templateModal');
        modal.classList.add('show');
        modal.style.display = 'flex';
        document.getElementById('templateName').focus();
    }

    function renderTemplateList() {
        const container = document.getElementById('templateList');
        container.innerHTML = '<h3>Saved Templates</h3>';
        templates.forEach(template => {
            const templateItem = document.createElement('div');
            templateItem.className = 'template-item';
            templateItem.innerHTML = `
                    <span>${template.name} (${template.type})</span>
                    <div class="template-item-actions">
                        <button class="btn btn-secondary btn-sm" onclick="editTemplate(${template.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteTemplate(${template.id})">Delete</button>
                    </div>
                `;
            container.appendChild(templateItem);
        });
    }

    function editTemplate(id) {
        const template = templates.find(t => t.id === id);
        if (template) {
            document.getElementById('templateName').value = template.name;
            document.getElementById('templateType').value = template.type;
            document.getElementById('templateContent').value = template.content;
            document.getElementById('templateName').dataset.id = id;
        }
    }

    function deleteTemplate(id) {
        if (confirm('Are you sure you want to delete this template?')) {
            fetch('{{ route("booking.deleteTemplate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Template deleted successfully!');
                        fetchTemplates();
                    } else {
                        alert(data.error || 'Error deleting template');
                    }
                })
                .catch(error => {
                    console.error('Error deleting template:', error);
                    alert('Error deleting template');
                });
        }
    }

    function saveTemplate() {
        if (userRole !== 'superadmin') {
            alert('Only superadmins can save templates.');
            return;
        }

        const name = document.getElementById('templateName').value;
        const type = document.getElementById('templateType').value;
        const content = document.getElementById('templateContent').value;
        const id = document.getElementById('templateName').dataset.id || null;

        if (!name || !content) {
            alert('Please enter template name and content.');
            return;
        }

        fetch('{{ route("booking.saveTemplate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id,
                    name,
                    type,
                    content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Template saved successfully!');
                    fetchTemplates();
                    closeTemplateModal();
                } else {
                    alert(data.error || 'Error saving template');
                }
            })
            .catch(error => {
                console.error('Error saving template:', error);
                alert('Error saving template');
            });
    }

    function closeTemplateModal() {
        const modal = document.getElementById('templateModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            document.getElementById('templateName').value = '';
            document.getElementById('templateType').value = 'default';
            document.getElementById('templateContent').value = '';
            document.getElementById('templateName').dataset.id = '';
        }, 300);
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
                const currentType = document.querySelector('.template-option.selected')?.textContent.toLowerCase() || 'default';
                selectTemplate(currentType);
                if (userRole === 'superadmin') {
                    renderTemplateList();
                }
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
            const formattedDate = formatDate(patient.appointment_date);
            previewMessage = previewMessage
                .replace('{name}', patient.full_name)
                .replace('{specialization}', patient.specialization)
                .replace('{date}', formattedDate)
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
        if (textarea.readOnly) {
            alert('Cannot insert variables into a read-only template.');
            return;
        }
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        textarea.value = textarea.value.substring(0, startPos) + variable + textarea.value.substring(endPos);
        updatePreview();
        updateCharCount();
    }

    function sendMessages() {
        if (userRole !== 'superadmin' && userRole !== 'admin') {
            alert('Only superadmins and admins can send reminders.');
            return;
        }

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
        const recipientsWithFormattedDate = selectedPatients.map(patient => {
            const formattedDate = formatDate(patient.appointment_date);
            return {
                ...patient,
                appointment_date: formattedDate // Replace with formatted date
            };
        });

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
                    recipients: recipientsWithFormattedDate,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('sendBtn').disabled = false;
                document.getElementById('progressBar').style.display = 'none';
                document.getElementById('sendingStatus').textContent = `Sent ${data.success_count} messages, ${data.failed_count} failed`;

                if (data.success_count > 0) {
                    selectedPatients = [];
                    renderSelectedPatients();
                }
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
        updatePreview();
        updateCharCount();
    });
</script>

<style>
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .modal.show {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        background: #fff;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        transform: translateY(-20px);
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }

    .modal.show .modal-content {
        transform: translateY(0);
        opacity: 1;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: #1f2937;
    }

    .modal-body {
        padding: 24px;
    }

    .template-list {
        margin-bottom: 24px;
    }

    .template-list h3 {
        font-size: 1.25rem;
        font-weight: 500;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .template-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .template-item span {
        font-size: 0.95rem;
        color: #374151;
    }

    .template-item-actions {
        display: flex;
        gap: 8px;
    }

    .template-form {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .template-form label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #1f2937;
    }

    .template-form input,
    .template-form select,
    .template-form textarea {
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.95rem;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }

    .template-form textarea[readonly] {
        background-color: #f3f4f6;
        cursor: not-allowed;
        color: #6b7280;
    }

    .template-form input:focus,
    .template-form select:focus,
    .template-form textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .template-form textarea {
        height: 120px;
        resize: vertical;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 16px;
    }

    .modal-actions .btn {
        padding: 10px 20px;
        font-size: 0.95rem;
    }

    @media (max-width: 640px) {
        .modal-content {
            width: 95%;
            max-height: 90vh;
        }

        .modal-header h2 {
            font-size: 1.25rem;
        }

        .template-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .template-item-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>
@endsection