<h1>Appointment Reminder</h1>
<p>Dear {{ $appointment->full_name }},</p>
<p>This is a reminder for your upcoming appointment:</p>
<ul>
    <li>Date: {{ $appointment->appointment_date }}</li>
    <!-- <li>Time: {{ $appointment->appointment_time }}</li> -->
    <li>Doctor: {{ $appointment->doctor_name }}</li>
    <li>Specialization: {{ $appointment->specialization }}</li>
</ul>
<p>Please arrive on time. Contact us if you need to reschedule.</p>
<p>Best regards,<br>KH CRM Team</p>