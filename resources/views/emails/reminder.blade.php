<!-- resources/views/emails/reminder.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Reminder: IREC Application Expiration</title>
</head>
<body>
    <h1>Reminder: Your IREC Application is Expiring Soon</h1>
    <p>Dear {{ $application->principal_investigator }},</p>

    <p>This is a reminder that your IREC application titled "<strong>{{ $application->proposal_title }}</strong>" is set to expire on <strong>{{ $application->date_of_renewal->format('F j, Y') }}</strong>.</p>

    <p>Please take the necessary actions to renew your application before the expiration date.</p>

    <p>Thank you,<br/>The IREC Team</p>
</body>
</html>
