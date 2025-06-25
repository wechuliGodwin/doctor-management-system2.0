<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Submission</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #159ed5;
            font-size: 24px;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
            padding-left: 15px;
            position: relative;
        }
        li::before {
            content: '\2022';  /* Bullet */
            color: #1386b5;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
        strong {
            color: #1386b5;
        }
        .poster-attachment {
            margin-top: 20px;
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Your Submission</h1>
        <p>Dear {{ $researcherName }},</p>

        <p>Thank you for submitting your registration for the Research Day at Kijabe Hospital on <strong>March 21, 2025</strong>. We appreciate your participation. Here are the details of your submission for your reference:</p>

        <ul>
            <li><strong>Researcher Names:</strong> {{ $registration->names }}</li>
            <li><strong>Phone Numbers:</strong> {{ $registration->phone_numbers }}</li>
            <li><strong>Emails:</strong> {{ $registration->emails }}</li>
            <li><strong>Co-investigators:</strong> {{ $registration->co_investigators }}</li>
            <li><strong>Research Categories:</strong> {{ implode(', ', $registration->categories ?? []) }}</li>
            <li><strong>Resubmission:</strong> {{ $registration->resubmission ? 'Yes' : 'No' }}</li>
        </ul>

        <div class="poster-attachment">
            <p><strong>Attachment:</strong> Your submitted poster has been attached to this email.</p>
        </div>

        <p>We look forward to seeing you at the event. If there are any changes or updates, please let us know.</p>
        
        <div class="footer">
            <p>Best regards,<br>
            Kijabe Hospital Research Team</p>
        </div>
    </div>
</body>
</html>