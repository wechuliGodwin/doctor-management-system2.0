<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Telemedicine MIS</title>
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
            background: rgba(21, 158, 213, 0.8); /* Translucent login modal with new theme color */
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
        .login-box form input[type="email"],
        .login-box form input[type="password"] {
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
        .forgot-password-link,
        .register-link {
            margin-top: 1rem;
        }
        .forgot-password-link a,
        .register-link a {
            color: #fff;
        }
        .forgot-password-link a:hover,
        .register-link a:hover {
            text-decoration: underline;
        }
        .disclaimer {
            font-style: italic;
            font-size: 0.8em;
            color: #ccc;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="{{ asset('images/logo.png') }}" alt="Kijabe Hospital Logo" style="max-width: 240px; margin-bottom: 1rem;">
        <h1>Telemedicine MIS</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                 <label for="password">Password: <span class="disclaimer">Ensure password is kept safe</span></label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <div class="forgot-password-link">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
            <div class="register-link">
                <a href="{{ route('register') }}">
                    {{ __('Don\'t have an account? Register here') }}
                </a>
            </div>
        </form>
    </div>
</body>
</html>
