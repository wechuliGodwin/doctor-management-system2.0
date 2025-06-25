<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Emails</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #159ed5;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group select, .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .email-preview {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .progress-bar-container {
            width: 100%;
            background-color: #f3f3f3;
            border-radius: 5px;
            margin-top: 20px;
            display: none; /* Initially hidden */
        }
        .progress-bar {
            height: 20px;
            background-color: #159ed5;
            width: 0;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #159ed5;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0d7ca7;
        }
    </style>
</head>
<body>
    <!-- Include the Menu -->
    @include('layouts.menu')

    <!-- Main Content Area -->
    <div class="container">
        <h2>Send Bulk Emails</h2>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form id="bulk-email-form" action="{{ route('staff.send-bulk-emails') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="email-group">Select Email Group</label>
                <select name="email_group" id="email-group" required>
                    @foreach($emailGroups as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="email-template">Select Email Message</label>
                <select name="email_template" id="email-template" required>
                    @foreach($emailTemplates as $key => $template)
                        <option value="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="attachment">Attach a File (Optional)</label>
                <input type="file" name="attachment" id="attachment">
            </div>
            <div class="form-group">
                <label>Preview:</label>
                <div class="email-preview" id="email-preview">Select an email template to see the preview here.</div>
            </div>
            <div class="form-group">
                <label>Expected Number of Recipients: <span id="recipient-count">0</span></label>
            </div>
            <button type="submit">Send Emails</button>
        </form>

        <!-- Progress Bar -->
        <div class="progress-bar-container" id="progress-bar-container">
            <div class="progress-bar" id="progress-bar"></div>
        </div>
    </div>

    <script>
        // Function to update the recipient count
        document.getElementById('email-group').addEventListener('change', function() {
            const groupName = this.value;
            fetch(`/get-recipient-count/${groupName}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('recipient-count').textContent = data.count;
                });
        });

        // Function to update the email preview
        document.getElementById('email-template').addEventListener('change', function() {
            const templateKey = this.value;
            const templates = {
                'reminder': `
                    <html>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <div style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                            <p>Dear Vendor Name,</p>
                            <p>This is a polite reminder about the ongoing prequalification process for the supply of goods, works, and services for the period 2024-2026 under the various categories. This is your opportunity to partner with us in impacting lives for Christ. The process started on 20th August 2024 as advertised in the Daily Nation and the deadline is 2nd September 2024 1700 hrs. Please submit your application by <strong>September 2nd (Today)</strong>.</p>
                            <p>For more information on your submission, please visit <a href="https://prequalification.kijabehospital.org" style="color: #159ed5; text-decoration: none;">prequalification.kijabehospital.org</a>. In case you experience any challenge, please reach out to the undersigned through the provided contacts.</p>
                            <p><i>Ignore this reminder if you have already completed your submission</i></p>
                            <p>Thank you,</p>
                            <p>Procurement Department<br>AIC Kijabe Hospital</p>
                            <p style="margin-top: 30px; font-size: 0.9em; color: #888;">Phone: +254 702 798 245<br>Email: <a href="mailto:procurement@kijabehospital.org" style="color: #159ed5; text-decoration: none;">procurement@kijabehospital.org</a></p>
                        </div>
                    </body>
                    </html>`,
                'thank_you': `
                    <html>
                    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <div style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                            <p>Dear Vendor Name,</p>
                            <p>Thank you for your interest in partnering with AIC Kijabe Hospital. We appreciate your application and look forward to collaborating with you.</p>
                            <p>Best regards,</p>
                            <p>Procurement Department<br>AIC Kijabe Hospital</p>
                            <p style="margin-top: 30px; font-size: 0.9em; color: #888;">Phone: +254 702 798 245<br>Email: <a href="mailto:procurement@kijabehospital.org" style="color: #159ed5; text-decoration: none;">procurement@kijabehospital.org</a></p>
                        </div>
                    </body>
                    </html>`
            };

            const preview = templates[templateKey] || 'No preview available';
            document.getElementById('email-preview').innerHTML = preview;
        });

        // Submit form and update progress bar
        document.getElementById('bulk-email-form').addEventListener('submit', function(event) {
            const progressBarContainer = document.getElementById('progress-bar-container');
            const progressBar = document.getElementById('progress-bar');
            let width = 0;

            progressBarContainer.style.display = 'block';
            progressBar.style.width = '0%'; // Reset progress bar

            // This is a placeholder for actual AJAX form submission logic.
            // Update the progress bar based on email sending progress
            const interval = setInterval(function() {
                fetch('/check-email-progress')
                    .then(response => response.json())
                    .then(data => {
                        width = data.progress; // Assume `progress` is a percentage value from the backend
                        progressBar.style.width = width + '%';

                        if (width >= 100) {
                            clearInterval(interval);
                            alert('Emails have been successfully sent!');
                        }
                    });
            }, 1000); // Check progress every second
        });
    </script>
</body>
</html>
