<!DOCTYPE html>
<html>
<head>
    <title>Your Appointment Meeting Link</title>
</head>
<body>
    <h1>Hello {{ $appointment->patient_name }},</h1>
    <p>Your telemedicine appointment has been scheduled successfully.</p>
    <p><strong>Date:</strong> {{ $appointment->appointment_date }}</p>
    <p><strong>Meeting Link:</strong> <a href="{{ $meetingLink }}">{{ $meetingLink }}</a></p>
    <p>Please join the meeting at the scheduled time. If you have any questions, feel free to contact us.</p>
    <p>Best regards,<br>AIC Kijabe Hospital</p>
</body>
</html>
