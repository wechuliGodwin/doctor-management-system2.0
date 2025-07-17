@extends('layouts.dashboard')

@section('title', 'Delivery Log')

@section('content')
@include('booking.partials.styles')

<div class="dashboard-container">
    <!-- <div class="header">
        <h1>Delivery Log</h1>
    </div> -->
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
                            <th>Message ID</th>
                            <th>API Status</th>
                            <th>API Status Desc</th>
                            <th>Delivery Status</th>
                            <th>Delivery Desc</th>
                            <th>Delivery Time</th>
                            <th>TAT</th>
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
    const csrfToken = '{{ csrf_token() }}';

    function fetchDeliveryLog() {
        fetch('{{ route("booking.getDeliveryLog") }}', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Delivery log data:', data);
                const deliveryLog = document.getElementById('deliveryLog');
                deliveryLog.innerHTML = '';
                if (data.logs && data.logs.length) {
                    const fragment = document.createDocumentFragment();
                    data.logs.forEach(log => {
                        const row = document.createElement('tr');
                        const statusClass = log.delivery_status === 'Delivered' || log.delivery_status === 'DeliveredToTerminal' || log.delivery_status === 'DeliveryNotificationNotSupported' ? 'status-delivered' :
                            log.delivery_status === 'MessageWaiting' || log.delivery_status === 'Queued' || log.delivery_status === 'MessagePaused' ? 'status-queued' :
                            log.delivery_status === 'DeliveredToNetwork' || log.delivery_status === 'DeliveryUncertain' || log.delivery_status === 'ForwardedToNetwork' ? 'status-noreport' :
                            log.status === 'failed' || ['DeliveryImpossible', 'Insufficient_Balance', 'Invalid_Linkid', 'TeleserviceNotProvisioned', 'UserInBlacklist', 'UserAbnormalState', 'UserIsSuspended', 'NotSFCUser', 'UserNotSubscribed', 'UserNotExist', 'AbsentSubscriber', 'NOT_DELIVERED', 'MessageRejected', 'ReportNotHandled', 'InvalidMobile'].includes(log.delivery_status) ? 'status-failed' : 'status-noreport';
                        row.innerHTML = `
                            <td>${log.appointment_number || '-'}</td>
                            <td>${log.specialization || '-'}</td>
                            <td>${log.appointment_date || '-'}</td>
                            <td>${log.message || '-'}</td>
                            <td>${log.message_id || '-'}</td>
                            <td><span class="${log.status === 'sent' ? 'status-sent' : 'status-failed'}">${log.status === 'sent' ? 'Sent' : 'Failed'}</span></td>
                            <td>${log.status_desc || '-'}</td>
                            <td><span class="${statusClass}">${log.delivery_status || '-'}</span></td>
                            <td>${log.delivery_desc || '-'}</td>
                            <td>${log.delivery_time || '-'}</td>
                            <td>${log.tat || '-'}</td>
                            <td>${log.sent_at || '-'}</td>
                        `;
                        fragment.appendChild(row);
                    });
                    deliveryLog.appendChild(fragment);
                } else {
                    deliveryLog.innerHTML = '<tr><td colspan="12">No logs found</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching delivery log:', error);
                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.style.display = 'block';
                errorMessage.textContent = 'Error fetching delivery log: ' + error.message;
                document.querySelector('.card').appendChild(errorMessage);
            });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        fetchDeliveryLog();
    });
</script>
@endsection