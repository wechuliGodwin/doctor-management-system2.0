<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Research Day Registration</title>
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
        .attachment {
            margin-top: 20px;
            font-style: italic;
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
        <h1>New Research Day Registration</h1>
        <p>A new registration for the Research Day at Kijabe Hospital has been submitted. Below are the details:</p>

        <ul>
            <li><strong>Researcher Names:</strong> {{ $registration->names }}</li>
            <li><strong>Phone Numbers:</strong> {{ $registration->phone_numbers }}</li>
            <li><strong>Emails:</strong> {{ $registration->emails }}</li>
            <li><strong>Co-investigators:</strong> {{ $registration->co_investigators }}</li>
            <li><strong>Research Categories:</strong> {{ implode(', ', $registration->categories ?? []) }}</li>
            <li><strong>Resubmission:</strong> {{ $registration->resubmission ? 'Yes' : 'No' }}</li>
        </ul>

        <div class="attachment">
            <p><strong>Attachment:</strong> A poster has been attached to this email for review.</p>
        </div>

        <p>Please review the submitted registration and the attached poster at your earliest convenience.</p>
        
        <div class="footer">
            <p>Best regards,<br>
            Kijabe Hospital Web Systems</p>
        </div>
    </div>
</body>
</html>