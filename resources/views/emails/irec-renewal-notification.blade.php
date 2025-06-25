<!-- resources/views/emails/irec-renewal-notification.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IREC Renewal Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #159ed5;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>IREC Renewal Notification</h1>
        </div>
        <div class="content">
            <p>Dear {{ $application->principal_investigator }},</p>
            <p>This is a reminder that your IREC application titled "<strong>{{ $application->proposal_title }}</strong>" is due for renewal on <strong>{{ \Carbon\Carbon::parse($application->date_of_renewal)->toFormattedDateString() }}</strong>.</p>
            <p>Please ensure that all necessary documents are submitted before the renewal date.</p>
            <p>Thank you.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AIC Kijabe Hospital. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
