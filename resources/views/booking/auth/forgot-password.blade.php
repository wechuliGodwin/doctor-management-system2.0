<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System - Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
       body {
            background-image: url('/images/12.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .forgot-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .form-control:focus {
            border-color: #159ed5;
            box-shadow: #159ed5;
        }
    </style>
</head>

<body>
    <div class="forgot-card">
        <div class="text-center mb-4">
            <h2>Booking System</h2>
            <p>Reset your password</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('booking.password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required
                    placeholder="Enter your email">
            </div>

            <button type="submit" class="btn w-100 mb-3" style="background-color: #159ed5; color: white;">Send Reset
                Link</button>
        </form>

        <div class="text-center">
            <p>Remember your password? <a href="{{ route('booking.login') }}">Sign In</a></p>
        </div>
    </div>
</body>

</html>