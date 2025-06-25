<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EMR Benchmarking</title>
    
    <style>
        body {
            background: #f3f4f6;
            font-family: 'Open Sans', sans-serif;
            color: #4a5568;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-modal {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            padding: 30px;
            box-sizing: border-box;
            text-align: center;
        }

        .login-modal h1 {
            color: #1a365d;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .login-modal img {
            height: 50px;
            margin-bottom: 20px;
        }

        .login-modal p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            color: #334155;
        }

        .form-group input[type="email"] {
            width: 100%;
            padding: 12px 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            font-size: 16px;
            transition: border-color 0.3s, background 0.3s;
        }

        .form-group input[type="email"]:focus {
            outline: none;
            border-color: #6c5dd3;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(108, 93, 211, 0.1);
        }

        .form-group .error {
            color: #e53e3e;
            font-size: 14px;
            margin-top: 5px;
        }

        .login-modal button {
            background: linear-gradient(to right, #159ed5, #6c5dd3);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px 0;
            width: 100%;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-modal button:hover {
            background: linear-gradient(to right, #0f7abf, #4e3f9a);
        }

        .login-modal .terms {
            font-size: 14px;
            margin-top: 20px;
            color: #64748b;
        }

        .login-modal .terms a {
            color: #159ed5;
            text-decoration: none;
        }

        .login-modal .terms a:hover {
            text-decoration: underline;
        }

        .login-modal .alert {
            background: #f87171;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 480px) {
            .login-modal {
                padding: 20px;
            }

            .login-modal h1 {
                font-size: 24px;
            }

            .login-modal img {
                height: 40px;
            }

            .form-group input[type="email"], .login-modal button {
                font-size: 14px;
            }

            .login-modal p, .login-modal .terms {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="login-modal">
        <h1>EMR Benchmarking</h1>

        @if (session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        <img src="images/micro2.png" alt="Microsoft Logo">

        <p>Enter your work email to access the page.</p>

        <form action="{{ route('emr.benchmarking.validate') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" required>
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Login with Microsoft</button>
        </form>

        <div class="terms">
            <p>By logging in, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>