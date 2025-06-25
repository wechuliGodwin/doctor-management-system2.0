<!-- resources/views/emails/expiring_application.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Dear {{ $application->principal_investigator }},</p>
        <p>We would like to remind you that your IREC application titled "{{ $application->proposal_title }}" is due for renewal on {{ $application->date_of_renewal }}.</p>
        <p>Please ensure that all necessary documents are submitted by this date to avoid any disruptions in your research activities.</p>
        <p>Best regards,</p>
        <p>Your Research Team</p>
    </div>
</body>
</html>
