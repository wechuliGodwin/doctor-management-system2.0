<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 100px;
            color: #e74c3c;
        }
        p {
            font-size: 20px;
        }
        a {
            text-decoration: none;
            color: #3498db;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Ooops 😊😊😊, the page you are looking for could not be found.</p>
    <a href="{{ url('/') }}">Go back to homepage</a>
</body>
</html>

