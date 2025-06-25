<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Application | Kijabe Hospital</title>
    <style>
        body {
            background-color: #e9f5fb; /* Light background color */
            color: #333; /* Default text color */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .application-container {
            display: flex;
            flex: 1;
            width: 100%;
            height: 100vh;
            box-sizing: border-box;
        }

        .image-side {
            flex: 1;
            background: url('https://cdn.pixabay.com/photo/2015/02/26/15/40/doctor-650534_1280.jpg') no-repeat center center;
            background-size: cover;
        }

        .info-side {
            flex: 1;
            background-color: #ffffff; /* White background for the container */
            padding: 60px 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-side h1 {
            color: #159ed5; /* Theme color */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .info-side p {
            margin-bottom: 15px;
            line-height: 1.6;
            color: #555; /* Slightly darker text color */
        }

        .info-side ol {
            text-align: left;
            margin: 0 auto 20px;
            padding-left: 20px;
            max-width: 400px;
            color: #555; /* Slightly darker text color */
        }

        .info-side ol li {
            margin-bottom: 10px;
        }

        .info-side a.email-link {
            color: #159ed5; /* Theme color for email links */
            text-decoration: none;
        }

        .info-side a.email-link:hover {
            text-decoration: underline;
        }

        .btn-apply {
            display: inline-block;
            padding: 10px 20px;
            background-color: #159ed5; /* Theme color */
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-apply:hover {
            background-color: #117fb3; /* Slightly darker shade for hover */
        }

        .footer {
            background-color: #159ed5; /* Theme color */
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        .footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .application-container {
                flex-direction: column;
                height: auto;
            }

            .image-side {
                height: 200px; /* Adjust height for smaller screens */
            }

            .info-side {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Include the navigation blade component -->
@include('layouts.navigation')

<div class="application-container">
    <!-- Left side with image -->
    <div class="image-side">
        <!-- The image will cover this side -->
    </div>

    <!-- Right side with information -->
    <div class="info-side">
        <h1>Internship Application</h1>
        
        <p>Thank you for your interest in applying for an internship at Kijabe Hospital. Please follow the steps below to submit your application:</p>
        
        <ol>
            <li>Prepare your <strong>CV</strong> and an <strong>Application Letter</strong>.</li>
            <li>Fill out the form that will be sent to your email after this step.</li>
            <li>Send your CV and Application Letter to: <a href="mailto:recruit@kijabehospital.org" class="email-link">recruit@kijabehospital.org</a>.</li>
        </ol>
        
        <p>If you have any questions or need further assistance, please feel free to reach out to us at <a href="mailto:recruit@kijabehospital.org" class="email-link">recruit@kijabehospital.org</a>.</p>
        
       <!--  <a href="https://meet.google.com/yhg-occh-dzp" class="btn-apply">Join Information Session</a> -->
    </div>
</div>

<!-- Include the footer blade component -->
@include('layouts.footer')

</body>
</html>
