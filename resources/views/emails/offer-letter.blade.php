<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prequalification Notification</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            background-color: #FFFFFF;
            line-height: 1.7;
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }
        .letter-container {
            width: 610px;
            padding: 20px 30px 20px;
            margin: 0 auto;
            position: relative; /* For absolute positioning of footer */
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }
        .content {
            margin-top: 5px;
            margin-bottom: 5px;
            text-align: left;
        }
        .content p {
            margin: 5px 0;
        }
        .content p:first-child {
            margin-top: 10px;
        }
        .signature {
            margin-top: 30px; /* Increased spacing before signature */
            text-align: left;
        }
        .signature img {
            max-width: 120px;
            vertical-align: middle;
            margin-left: 5px;
        }
        .signature p {
            display: inline;
            margin: 0;
            vertical-align: middle;
        }
        .footer {
            text-align: center;
            font-size: 9pt;
            margin-top: 30px; /* Increased margin to push footer down */
        }
        .footer img {
            width: calc(100% + 60px);
            max-height: 232px;
            object-fit: contain;
            position: absolute;
            bottom: -20px; /* Push down a bit more */
            left: -30px; /* Adjust left positioning */
        }
    </style>
</head>
<body>
    <div class="letter-container">
        <div class="header">
            <img src="{{ asset('images/header.jpg') }}" alt="Kijabe Hospital Header">
        </div>
        <div class="content">
           <p style="margin: 0;">{{ date('jS F Y', strtotime('2025-01-07')) }}</p>
            <p style="margin: 0;">Managing Director - {{ $supplier->name }}</p>
            <p>Dear Sir/Madam,</p>
            <p style="font-weight: bold; text-decoration: underline;">RE: PREQUALIFICATION NOTIFICATION FOR FINANCIAL YEARS 2025 - 2026</p>

            <p>The Hospital appreciates your interest in partnering with us as a supplier in our mission to provide compassionate healthcare to God's glory. We acknowledge that every link in the chain that gets supplies to the hospital is a link to providing care to our patients.</p>

            <p>Thank you for your response and submission of documents to the Prequalification of Suppliers advertisement in August 2024. A careful evaluation of bid documents received has now been completed.</p>

            <p>I am pleased to inform you that your organization has been admitted to the Approved Vendor List for a period of Two (2) years effective 1st January, 2025 to 31st December, 2026.</p>

            <p>Request for Quotation will be sent as well as request for samples where applicable for further review and approval respectively.</p>

            <p>Please note prequalification does not mean automatic receipt of purchase orders.</p>

            <p>Congratulations and welcome on board!</p>
            <p style="margin: 15px 0;"> <!-- Added extra spacing --></p>
        </div>
        <div class="signature">
            <p>Yours faithfully,</p><br>
            <p>David Waweru</p>
            <img src="{{ asset('images/sign_david.png') }}" alt="Signature"><br>
            <p>Director, Support Services</p>
        </div>
        <div class="footer">
            <img src="{{ asset('images/footer.jpg') }}" alt="Kijabe Hospital Footer">
        </div>
    </div>
</body>
</html>