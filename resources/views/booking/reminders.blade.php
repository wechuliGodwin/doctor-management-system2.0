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
                console.log('Send SMS response:', data);
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
@endsection