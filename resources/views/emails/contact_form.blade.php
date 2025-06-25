<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailData['subject'] ?? 'New Contact Form Submission - Kijabe Hospital' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #159ed5;
            text-align: center;
        }

        p {
            margin: 10px 0;
        }

        .detail {
            margin-bottom: 8px;
        }

        .detail-label {
            font-weight: bold;
            color: #1386b5;
        }

        ul {
            margin-bottom: 10px;
        }

        li {
            margin-bottom: 5px;
        }

        .signature {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>{{ $emailData['subject'] ?? 'New Contact Form Submission' }}</h1>

        @if(isset($emailData['is_admin']) && $emailData['is_admin'])
            <!-- Admin Notification for Appointment -->
            <p>Dear Sir/Madam,</p>
            <p>A new external appointment has been booked. Please find the details below:</p>
            @if(isset($emailData['full_name']))
                <p class="detail"><span class="detail-label">Patient Name:</span> {{ $emailData['full_name'] }}</p>
            @endif
            @if(isset($emailData['email']))
                <p class="detail"><span class="detail-label">Email:</span> {{ $emailData['email'] }}</p>
            @endif
            @if(isset($emailData['phone']))
                <p class="detail"><span class="detail-label">Phone:</span> {{ $emailData['phone'] }}</p>
            @endif
            @if(isset($emailData['appointment_date']))
                <p class="detail"><span class="detail-label">Appointment Date:</span> {{ $emailData['appointment_date'] }}</p>
            @endif
            @if(isset($emailData['specialization']))
                <p class="detail"><span class="detail-label">Specialization:</span> {{ $emailData['specialization'] }}</p>
            @endif
            @if(isset($emailData['appointment_number']))
                <p class="detail"><span class="detail-label">Appointment Number:</span> {{ $emailData['appointment_number'] }}
                </p>
            @endif
            <p>Please review the appointment in the system.</p>
        @else
            <!-- General Contact Form or User Appointment Confirmation -->
            @if(isset($emailData['name']) || isset($emailData['full_name']))
                <p>Dear {{ $emailData['name'] ?? $emailData['full_name'] ?? 'Valued Customer' }},</p>
            @endif

            @if(isset($emailData['message']) && !isset($emailData['appointment_date']))
                <!-- General Contact Form -->
                <div class="details">
                    @if(isset($emailData['name']))
                        <p><span class="detail-label">Name:</span> {{ $emailData['name'] }}</p>
                    @endif
                    @if(isset($emailData['email']))
                        <p><span class="detail-label">Email:</span> {{ $emailData['email'] }}</p>
                    @endif
                    @if(isset($emailData['phone']))
                        <p><span class="detail-label">Phone:</span> {{ $emailData['phone'] }}</p>
                    @endif
                </div>
                <p><span class="detail-label">Message:</span></p>
                <p>{{ nl2br(e($emailData['message'])) }}</p>
                @if(isset($emailData['subscribe']))
                    <p><span class="detail-label">Subscribe to Newsletter:</span> {{ $emailData['subscribe'] }}</p>
                @endif
            @else
                <!-- User Appointment Confirmation -->
                <p>Your appointment has been processed. Details below:</p>
                <ul>
                    @if(isset($emailData['appointment_date']))
                        <li><strong>Date:</strong> {{ $emailData['appointment_date'] }}</li>
                    @endif
                    @if(isset($emailData['doctor_name']))
                        <li><strong>Doctor:</strong> {{ $emailData['doctor_name'] }}</li>
                    @endif
                    @if(isset($emailData['specialization']))
                        <li><strong>Specialization:</strong> {{ $emailData['specialization'] }}</li>
                    @endif
                    @if(isset($emailData['appointment_status']))
                        <li><strong>Status:</strong> {{ ucfirst($emailData['appointment_status']) }}</li>
                    @endif
                    @if(isset($emailData['booking_type']))
                        <li><strong>Type:</strong> {{ ucfirst(str_replace('-', ' ', $emailData['booking_type'])) }}</li>
                    @endif
                    @if(isset($emailData['appointment_number']))
                        <li><strong>Appointment Number:</strong> {{ $emailData['appointment_number'] }}</li>
                    @endif
                    @if(isset($emailData['cancellation_reason']))
                        <li><strong>Cancellation Reason:</strong> {{ $emailData['cancellation_reason'] }}</li>
                    @endif
                    @if(isset($emailData['notes']))
                        <li><strong>Notes:</strong> {{ $emailData['notes'] }}</li>
                    @endif
                </ul>
                <p>Please contact us if you have any questions or need to make changes.</p>
            @endif
        @endif

        <p class="signature">Best regards,<br>Kijabe Hospital CRM Team</p>
    </div>
</body>

</html>