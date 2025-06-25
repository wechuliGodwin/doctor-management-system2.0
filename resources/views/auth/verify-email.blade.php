<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification | Research MIS</title>
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1579154204845-5d7f8d4dc785?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .verification-box {
            background: rgba(21, 158, 213, 0.8); /* Translucent background with theme color */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }
        .verification-box img {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }
        .verification-box p {
            color: #fff;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        .btn-primary {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            background-color: #159ed5; /* Theme color */
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 1rem;
            display: inline-block;
        }
        .btn-primary:hover {
            background-color: #117fb3; /* Slightly darker shade for hover */
        }
        .logout-button {
            background: none;
            border: none;
            color: #fff;
            text-decoration: underline;
            cursor: pointer;
            margin-top: 1rem;
            display: inline-block;
        }
        .logout-button:hover {
            color: #f0f4f8; /* Light background color on hover */
        }
    </style>
</head>
<body>
    <div class="verification-box">
        <img src="https://kijabehospital.or.ke/images/logo.png" alt="Kijabe Hospital Logo"> <!-- Kijabe logo at the top -->
        
        <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>

        @if (session('status') == 'verification-link-sent')
            <p class="text-green-400">A new verification link has been sent to the email address you provided during registration.</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">Log Out</button>
        </form>
    </div>
</body>
</html>
