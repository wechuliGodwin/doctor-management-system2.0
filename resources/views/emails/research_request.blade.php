<!DOCTYPE html>
<html>
<head>
    <title>New ISERC Research Request Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #159ed5;
            font-size: 24px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .section-title {
            font-weight: bold;
            color: #159ed5;
            margin-top: 20px;
            font-size: 18px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>New ISERC Research Request</h1>
    <p>A new research request has been submitted by <strong>{{ $researchRequest->pi_name }}</strong>.</p>

    <div class="details">
        <p class="section-title">Principal Investigator Details:</p>
        <p><strong>Name:</strong> {{ $researchRequest->pi_name }}</p>
        <p><strong>Email:</strong> {{ $researchRequest->pi_email }}</p>
        <p><strong>Address:</strong> {{ $researchRequest->pi_address }}</p>
        <p><strong>Mobile:</strong> {{ $researchRequest->pi_mobile }}</p>
        <p><strong>Human Subjects Training Completed:</strong> {{ ucfirst($researchRequest->human_subjects_training) }}</p>
    </div>

    <div class="details">
        <p class="section-title">Co-Investigators:</p>
        <p><strong>Co-Investigator 1:</strong> {{ $researchRequest->co_investigator_1 ?? 'N/A' }}</p>
        <p><strong>Co-Investigator 2:</strong> {{ $researchRequest->co_investigator_2 ?? 'N/A' }}</p>
        <p><strong>Co-Investigator 3:</strong> {{ $researchRequest->co_investigator_3 ?? 'N/A' }}</p>
    </div>

    <div class="details">
        <p class="section-title">Payment Information:</p>
        <p><strong>Payment Reference (MPESA):</strong> {{ $researchRequest->payment_reference }}</p>
    </div>

    <p>Please find the attached documents for further review.</p>

    <div class="footer">
        <p>This email was generated automatically by the Research Submission System.</p>
    </div>
</body>
</html>
