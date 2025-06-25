<!DOCTYPE html>
<html>
<head>
    <title>New User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            margin: 20px;
        }
        h1 {
            color: #159ed5;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 5px;
        }
        strong {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>New User Registered</h1>
        <p>A new user has registered with the following details:</p>

        <ul>
            <li><strong>Name:</strong> {{ $user->name }}</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Phone:</strong> {{ $user->phone }}</li>
            <li><strong>Date of Birth:</strong> {{ $user->dob }}</li>
            <li><strong>Next of Kin:</strong> {{ $user->next_of_kin }}</li>
            <li><strong>Next of Kin Number:</strong> {{ $user->next_of_kin_number }}</li>
            <li><strong>Region:</strong> {{ $user->region }}</li>
        </ul>

        <p>Please review the user's information and take any necessary actions.</p>
    </div>
</body>
</html>
