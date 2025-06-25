<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | AIC Kijabe</title>
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
        .reset-box {
            background: rgba(21, 158, 213, 0.8);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }
        .reset-box h1 {
            color: #fff;
            margin-bottom: 1rem;
        }
        .reset-box form .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }
        .reset-box form label {
            color: #fff;
            font-weight: bold;
            display: block;
            margin-bottom: 0.5rem;
        }
        .reset-box form input[type="email"],
        .reset-box form input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .reset-box form button {
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: none;
            background-color: #159ed5;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .reset-box form button:hover {
            background-color: #117fb3;
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
    <div class="reset-box">
        <img src="https://kijabehospital.org/images/logo.png" alt="Kijabe Hospital Logo" style="max-width: 240px; margin-bottom: 1rem;">
        <h1>{{ __('Reset Password') }}</h1>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">{{ __('New Password') }}</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm New Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
                @error('password_confirmation')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">
                    {{ __('Reset Password') }}
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
