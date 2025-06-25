<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Telemedicine MIS</title>
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
            overflow: scroll;
        }
        .register-box {
            background: rgba(21, 158, 213, 0.8); 
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }
        .register-box h1 {
            margin-bottom: 0.4rem;
            color: #fff;
        }
        .register-box form {
            margin-top: 1.5rem;
        }
        .register-box form .form-group {
            margin-bottom: 0.6rem;
        }
        .register-box form label {
            display: inline-block;
            text-align: left;
            width: 100%;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #fff;
        }
        .register-box form input[type="text"],
        .register-box form input[type="email"],
        .register-box form input[type="number"],
        .register-box form input[type="password"],
        .register-box form input[type="date"] { 
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .register-box form button {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            background-color: #159ed5;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .register-box form button:hover {
            background-color: #117fb3; 
        }
        .login-link {
            margin-top: 1rem;
        }
        .login-link a {
            color: #fff;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <img src="https://kijabehospital.or.ke/images/logo.png" alt="Telemedicine MIS Logo" style="max-width: 150px; margin-bottom: 0.2rem;">
        <h1>Telemedicine MIS Register</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required autocomplete="tel"> 
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob') }}" required>
            </div>

            <div class="form-group">
                <label for="next_of_kin">Next of Kin:</label>
                <input type="text" name="next_of_kin" id="next_of_kin" class="form-control" value="{{ old('next_of_kin') }}" required>
            </div>

            <div class="form-group">
                <label for="next_of_kin_number">Next of Kin Number:</label>
                <input type="text" name="next_of_kin_number" id="next_of_kin_number" class="form-control" value="{{ old('next_of_kin_number') }}" required>
            </div>

            <div class="form-group">
                <label for="region">Region:</label>
                <input type="text" name="region" id="region" class="form-control" value="{{ old('region') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>

            <div class="login-link">
                <a href="{{ route('login') }}">
                    {{ __('Already registered? Login here') }}
                </a>
            </div>
        </form>
    </div>
</body>
</html>