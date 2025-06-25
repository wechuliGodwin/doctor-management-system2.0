<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | AIC Kijabe</title>
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
            overflow: hidden;
        }
        .login-box {
            background: rgba(21, 158, 213, 0.8); /* Translucent background */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }
        .login-box h1 {
            margin-bottom: 1rem;
            color: #fff;
        }
        .login-box form {
            margin-top: 2rem;
        }
        .login-box form .form-group {
            margin-bottom: 1rem;
        }
        .login-box form label {
            display: inline-block;
            text-align: left;
            width: 100%;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #fff;
        }
        .login-box form input[type="email"] {
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .login-box form button {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            background-color: #159ed5; /* Updated theme color */
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-box form button:hover {
            background-color: #117fb3; /* Slightly darker shade for hover */
        }
        .back-to-login {
            margin-top: 1rem;
        }
        .back-to-login a {
            color: #fff;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <!-- Kijabe Hospital Logo -->
        <img src="https://kijabehospital.org/images/logo.png" alt="Kijabe Hospital Logo" style="max-width: 240px; margin-bottom: 1rem;">
        
        <!-- Title -->
        <h1>{{ __('Reset Password') }}</h1>

        <!-- Information Text -->
        <div class="mb-4 text-sm text-white">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <!-- Password Reset Form -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="back-to-login">
            <a href="{{ route('login') }}">
                {{ __('Back to Login') }}
            </a>
        </div>
    </div>
</body>
</html>
