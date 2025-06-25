<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://cdn.pixabay.com/photo/2019/12/17/17/09/woman-4702060_1280.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            backdrop-filter: blur(5px);
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h1 {
            color: #159ed5;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            margin: 10px 0;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #159ed5;
        }

        button {
            padding: 10px 15px;
            background-color: #159ed5;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0d7ca7;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .reset-link {
            color: #159ed5;
            display: block;
            margin-top: 10px;
            text-decoration: none;
        }

        .reset-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h1>Staff Login</h1>
    @if ($errors->any())
        <div class="error-message">
            {{ $errors->first() }}
        </div>
    @endif
    <form action="{{ route('staff.login.submit') }}" method="POST">

        @csrf
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
            <i class="fa-solid fa-envelope"></i>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
            <i class="fa-solid fa-lock"></i>
        </div>
        <button type="submit">Login</button>
        <a class="reset-link" href="{{ route('staff.password.request') }}">Forgot Password?</a>
    </form>
</div>
</body>
</html>