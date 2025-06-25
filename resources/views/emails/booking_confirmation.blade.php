<!-- resources/views/emails/booking_confirmation.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking Confirmation</title>
</head>
<body>
    <h1>New Appointment Booking</h1>
    <p><strong>Name:</strong> {{ $emailData['name'] }}</p>
    <p><strong>Email:</strong> {{ $emailData['email'] }}</p>
    <p><strong>Phone:</strong> {{ $emailData['phone'] }}</p>
    <p><strong>Appointment Date:</strong> {{ $emailData['appointment_date'] }}</p>
    <p><strong>Service:</strong> {{ $emailData['service'] }}</p>
    <p><strong>Additional Notes:</strong> {{ $emailData['notes'] }}</p>
</body>
</html>
