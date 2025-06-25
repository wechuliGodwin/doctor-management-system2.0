<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brochure Download | Kijabe Hospital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #159ed5;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 1.1rem;
        }

        .info-message {
            background-color: #e9f5fb;
            border: 1px solid #159ed5;
            color: #159ed5;
            padding: 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Include the navigation blade component -->
@include('layouts.navigation')

<div class="container">
    <h1>Downloads</h1>
    <p>Thank you for your interest in our information. Our various leaflets will be available for download soon. Please check back later for more information.</p>
    <div class="info-message">
        Downloads will be ready soon. Stay tuned!
    </div>
</div>

<!-- Include the footer blade component -->
@include('layouts.footer')

</body>
</html>
