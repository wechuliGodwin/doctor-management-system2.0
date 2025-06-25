<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Bulk Email</title>
</head>
<body>
    <h1>Send Bulk Email</h1>
    <form action="{{ route('emails.send_bulk') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="subject">Subject:</label>
        <input type="text" name="subject" required><br><br>

        <label for="message">Message:</label>
        <textarea name="message" rows="4" required></textarea><br><br>

        <label for="attachment">Attachment (optional):</label>
        <input type="file" name="attachment"><br><br>

        <button type="submit">Send Emails</button>
    </form>
</body>
</html>
