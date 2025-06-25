<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kijabe Hospital: Telemedicine Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f5f7fa; font-family: 'Segoe UI', 'Arial', Helvetica, sans-serif; color: #1A252F; line-height: 1.6; padding: 20px 0; }
        a { color: #159ed5; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 15px; border: 1px solid #e6e9ec; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        
        /* Header */
        .header { background: linear-gradient(135deg, #159ed5, #117a9a); padding: 30px 20px; text-align: center; position: relative; }
        .header img { max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .header h1 { color: #ffffff; margin: 20px 0 0; font-size: 28px; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.3); letter-spacing: 0.5px; }

        /* Content */
        .content { padding: 30px; }
        .content h2 { color: #159ed5; margin: 0 0 20px; text-align: center; font-size: 24px; font-weight: 600; position: relative; }
        .content h2:after { content: ''; width: 50px; height: 3px; background: #159ed5; display: block; margin: 8px auto 0; border-radius: 2px; }
        .content p { margin: 0 0 15px; font-size: 16px; color: #333333; }
        .content ul { list-style: none; padding-left: 0; margin: 20px 0; }
        .content ul li { position: relative; padding-left: 35px; margin-bottom: 12px; font-size: 15px; }
        .content ul li i { color: #159ed5; position: absolute; left: 0; top: 2px; font-size: 18px; }
        .content ul li a { color: #1A252F; transition: color 0.2s ease; }
        .content ul li a:hover { color: #159ed5; text-decoration: none; }

        /* Call to Action */
        .cta { text-align: center; margin: 25px 0; }
        .cta-button { display: inline-block; padding: 12px 30px; border-radius: 25px; color: #ffffff !important; font-weight: 600; text-decoration: none; transition: transform 0.2s ease, box-shadow 0.2s ease; font-size: 16px; }
        .cta-button:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); text-decoration: none; }
        .book-now { background: linear-gradient(135deg, #159ed5, #1386b5); }
        .learn-more { background: linear-gradient(135deg, #1386b5, #117a9a); margin-left: 15px; }

        /* Footer */
        .footer { text-align: center; padding: 20px 30px; background: #f9fafb; color: #666666; font-size: 13px; }
        .footer img { max-width: 300px; margin-top: 15px; }
        .footer p { margin: 5px 0; }

        /* Responsive Design */
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; border-radius: 0; box-shadow: none; }
            .header { padding: 20px 10px; }
            .header h1 { font-size: 22px; }
            .content { padding: 20px; }
            .content h2 { font-size: 20px; }
            .content p { font-size: 14px; }
            .content ul li { font-size: 14px; padding-left: 30px; }
            .content ul li i { font-size: 16px; }
            .cta-button { display: block; width: 90%; margin: 10px auto; font-size: 14px; }
            .learn-more { margin-left: 0; }
            .footer { padding: 15px 20px; }
        }
    </style>
</head>
<body>
    <!--[if mso]>
    <style type="text/css">
    body, table, td {font-family: Arial, Helvetica, sans-serif !important;}
    .cta-button {background: #159ed5 !important;}
    </style>
    <![endif]-->
    
    <center style="width: 100%;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" class="container">
            <!-- Header -->
            <tr>
                <td class="header">
                    <img src="https://kijabehospital.org/images/telemed_1.png" alt="Telemedicine Services" width="100%">
                    <h1>Telemedicine Services</h1>
                </td>
            </tr>
            
            <!-- Telemedicine Services Section -->
            <tr>
                <td class="content">
                    <p>Dear {{ $supplier->name }},</p>
                    <h2>Care Anytime, Anywhere</h2>
                    <p>Access expert care from the comfort of your home with Kijabe Hospital's Telemedicine Services, now including Telepharmacy with delivery. Whether it's a consultation, follow-up, or medication management, we've got you covered with:</p>
                    <ul>
                        <li><i class="fas fa-stethoscope"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-General Medicine Consultation</strong> - KSh 500</a></li>
                        <li><i class="fas fa-carrot"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Nutrition Consultation</strong> - KSh 500</a></li>
                        <li><i class="fas fa-user-md"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Telemed - Private Consultation</strong> - KSh 3,000</a></li>
                        <li><i class="fas fa-user-doctor"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Telemed - Specialty Consultation</strong> - KSh 1,000</a></li>
                        <li><i class="fas fa-comments"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Psychotherapy</strong> - KSh 1,000</a></li>
                        <li><i class="fas fa-brain"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Adult Psychiatry</strong> - KSh 4,500</a></li>
                        <li><i class="fas fa-user-md"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Adult Psychiatry Follow-Up</strong> - KSh 4,000</a></li>
                        <li><i class="fas fa-child"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Child Psychiatry</strong> - KSh 6,000</a></li>
                        <li><i class="fas fa-child-reaching"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Child Psychiatry Follow-Up</strong> - KSh 5,000</a></li>
                        <li><i class="fas fa-pills"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Pharmacy with Delivery</strong> - Now Available</a></li>
                        <li><i class="fas fa-microscope"></i> <a href="https://kijabehospital.org/telemedicine-patient"><strong>Tele-Pathology</strong> - Coming Soon</a></li>
                    </ul>
                    <p>Our Telepharmacy service is now live, offering remote consultations with pharmacists and convenient medication delivery to your doorstep, ensuring safe and accessible care from anywhere.</p>
                    <p class="cta">Book your telemedicine appointment today and experience compassionate care at your fingertips!</p>
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
                        <tr>
                            <td>
                                <a href="https://kijabehospital.org/telemedicine-patient" class="cta-button book-now">Book a Telemedicine Appointment</a>
                            </td>
                            <td width="15"></td>
                            <td>
                                <a href="https://kijabehospital.org/telemedicine-patient" class="cta-button learn-more">Learn More</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <!-- Footer -->
            <tr>
                <td class="footer">
                    <p>Need assistance? Contact us at <a href="tel:+254709728215">0709728215</a> or email <a href="mailto:enquiries@kijabehospital.org">enquiries@kijabehospital.org</a></p>
                    <p>#CompassionateHealthcare #110YearsLegacy</p>
                    <img src="https://kijabehospital.org/images/logo_110.png" alt="Kijabe Logo" width="300">
                </td>
            </tr>
        </table>
    </center>
</body>
</html>