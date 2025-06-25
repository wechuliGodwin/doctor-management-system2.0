<!DOCTYPE html>
<html>
<head>
    <title>New Patient Feedback</title>
</head>
<body>
    <h1>New Patient Feedback Received</h1>
    <p>Date of Visit: {{ $visit_date }}</p>
    <p>Referral Source: {{ $referral_source }}</p>
    <p>Referral Source (Other): {{ $referral_source_other }}</p>
    <p>Overall Rating: {{ $overall_rating }}</p>
    <p>Experience Reason: {{ $experience_reason }}</p>
    <p>Service Ratings:</p>
    <ul>
        @foreach ($service_rating as $service => $rating)
            <li>{{ $service }}: {{ $rating }}</li>
        @endforeach
    </ul>
    <p>Case Handling Opinion: {{ $case_handling_opinion }}</p>
    <p>Statement Agreements:</p>
    <ul>
        @foreach ($statement_agreement as $statement => $agreement)
            <li>{{ $statement }}: {{ $agreement }}</li>
        @endforeach
    </ul>
    <p>Improvement Suggestions: {{ $improvement_suggestions }}</p>
    <p>Future Contact: {{ $future_contact ? 'Yes' : 'No' }}</p>
    <p>Full Name: {{ $full_name }}</p>
    <p>Mobile Number: {{ $mobile_number }}</p>
</body>
</html>