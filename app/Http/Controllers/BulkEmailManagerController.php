<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BulkEmail as BulkEmailMailable;
use App\Models\BulkEmail as BulkEmailModel;
use Illuminate\Support\Facades\Log;

class BulkEmailManagerController extends Controller
{
    public function index()
    {
        // Define available email groups
        $emailGroups = ['bulk_emails' => 'Bulk Emails'];

        // Define email templates
        $emailTemplates = [
            'reminder' => "
                <html>
                <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
                    <div style=\"max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;\">
                        <p>Dear :name,</p>
                        <p>This is a polite reminder about our ongoing prequalification process for the supply of goods, works and services for the period 2024-2026 under the various categories. The process started on 20th August 2024 as advertised in the dailies and will proceed up to 2nd September 2024 1700 hrs. Please submit your application by <strong>September 2nd</strong>.</p>
                        <p>For more information and to submit your application, please visit <a href=\"https://prequalification.kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">prequalification.kijabehospital.org</a>. In case you are experiencing any challenges, please do not hesitate to write to us or call us on our official numbers as provided.</p>
                        <p>Thank you,</p>
                        <p>Procurement Department<br>AIC Kijabe Hospital</p>
                        <p style=\"margin-top: 30px; font-size: 0.9em; color: #888;\">Phone: +254 702 798 245<br>Email: <a href=\"mailto:procurement@kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">procurement@kijabehospital.org</a></p>
                    </div>
                </body>
                </html>",
            
            'thank_you' => "
                <html>
                <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
                    <div style=\"max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;\">
                        <p>Dear :name,</p>
                        <p>Thank you for your interest in partnering with AIC Kijabe Hospital. We appreciate your application and look forward to collaborating with you.</p>
                        <p>We will review your submission and reach out to you soon with further details.</p>
                        <p>Best regards,</p>
                        <p>Procurement Department<br>AIC Kijabe Hospital</p>
                        <p style=\"margin-top: 30px; font-size: 0.9em; color: #888;\">Phone: +254 702 798 245<br>Email: <a href=\"mailto:procurement@kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">procurement@kijabehospital.org</a></p>
                    </div>
                </body>
                </html>",

            'extension' => "
                <html>
                <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
                    <div style=\"max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;\">
                        <p>Dear :name,</p>
                        <p>This is a reminder of the extension of our ongoing <strong>prequalification</strong> process for the supply of goods, works, and services for the period 2024-2026 under various categories. This is your opportunity to partner with us in impacting lives for Christ. The submission deadline has been extended to <strong>Friday, 6th September 2024 (Around 2 hours to deadline!)</strong>.</p>
                        <p>For more information and to submit your application, please visit <a href=\"https://prequalification.kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">prequalification.kijabehospital.org</a>. If you experience any challenges, please reach out to the undersigned through the provided contacts.</p>
                        <p>If you have already completed your application, kindly ignore this notice.</p>
                        <p>Thank you,</p>
                        <p>Procurement Department<br>AIC Kijabe Hospital</p>
                        <p style=\"margin-top: 30px; font-size: 0.9em; color: #888;\">Phone: +254 702 798 245<br>Email: <a href=\"mailto:procurement@kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">procurement@kijabehospital.org</a></p>
                    </div>
                </body>
                </html>"
        ];

        // Pass data to the view
        return view('staff.bulk-emails', compact('emailGroups', 'emailTemplates'));
    }

    public function getRecipientCount($groupName)
    {
        // Get the count of recipients in the specified email group
        $count = BulkEmailModel::count();
        return response()->json(['count' => $count]);
    }

    public function sendBulkEmails(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email_group' => 'required|string',
            'email_template' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
        ]);

        // Get email group, template key, and attachment from the request
        $emailGroup = $request->input('email_group');
        $templateKey = $request->input('email_template');
        $attachment = $request->file('attachment');

        // Define email templates
        $emailTemplates = [
            'reminder' => "
                <html>
                <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
                    <div style=\"max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;\">
                        <p>Dear :name,</p>
                        <p>This is a polite reminder about our ongoing <strong>prequalification</strong> process for the supply of goods, works and services for the period 2024-2026 under the various categories. This is your opportunity to partner with us in impacting lives for Christ. The process started on 20th August 2024 as advertised in the Daily Nation and the deadline is 2nd September 2024 1700 hrs. Please submit your application by <strong>September 2nd (COB Today)</strong>.</p>
                        <p>For more information and to submit your application, please visit <a href=\"https://prequalification.kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">prequalification.kijabehospital.org</a>. In case you experience any challenge, please reach out to the undersigned through the provided contacts.</p>
                        <p>In case you have already submitted your application, kindly ignore this reminder</p>
                        <p>Thank you,</p>
                        <p>Procurement Department<br>AIC Kijabe Hospital</p>
                        <p style=\"margin-top: 30px; font-size: 0.9em; color: #888;\">Phone: +254 702 798 245<br>Email: <a href=\"mailto:procurement@kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">procurement@kijabehospital.org</a></p>
                    </div>
                </body>
                </html>",
            
            'thank_you' => "
                <html>
                <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
                    <div style=\"max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;\">
                        <p>Dear :name,</p>
                        <p>Thank you for your interest in partnering with AIC Kijabe Hospital. We appreciate your application and look forward to collaborating with you.</p>
                        <p>We will review your submission and reach out to you soon with further details.</p>
                        <p>Best regards,</p>
                        <p>Procurement Department<br>AIC Kijabe Hospital</p>
                        <p style=\"margin-top: 30px; font-size: 0.9em; color: #888;\">Phone: +254 702 798 245<br>Email: <a href=\"mailto:procurement@kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">procurement@kijabehospital.org</a></p>
                    </div>
                </body>
                </html>",


            'extension' => "
                <html>
                <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
                    <div style=\"max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;\">
                        <p>Dear :name,</p>
                        <p>This is a notice of extension regarding our ongoing <strong>prequalification</strong> process for the supply of goods, works, and services for the period 2024-2026 under various categories. This is your opportunity to partner with us in impacting lives for Christ. The submission deadline has been extended to <strong>Friday, 6th September 2024</strong>.</p>
                        <p>For more information and to submit your application, please visit <a href=\"https://prequalification.kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">prequalification.kijabehospital.org</a>. If you experience any challenges, please reach out to the undersigned through the provided contacts.</p>
                        <p>If you have already submitted your application, kindly ignore this notice.</p>
                        <p>Thank you,</p>
                        <p>Procurement Department<br>AIC Kijabe Hospital</p>
                        <p style=\"margin-top: 30px; font-size: 0.9em; color: #888;\">Phone: +254 702 798 245<br>Email: <a href=\"mailto:procurement@kijabehospital.org\" style=\"color: #159ed5; text-decoration: none;\">procurement@kijabehospital.org</a></p>
                    </div>
                </body>
                </html>"
        ];

        // Get the selected email content
        $emailContent = $emailTemplates[$templateKey] ?? null;
        if (!$emailContent) {
            return redirect()->back()->withErrors(['error' => 'Invalid email template selected.']);
        }

        // Get all email recipients
        $emails = BulkEmailModel::all();
        $successful = 0;
        $failed = 0;

        // Loop through each recipient and send the email
        foreach ($emails as $recipient) {
            try {
                $emailMessage = str_replace(':name', $recipient->name, $emailContent);
                Mail::to($recipient->email)->send(new BulkEmailMailable($templateKey, $emailMessage, $attachment));
                
                // Mark the email as sent
                $recipient->update(['email_sent' => true]);
                
                $successful++;
            } catch (\Exception $e) {
                $failed++;
                Log::error("Failed to send email to {$recipient->email}: " . $e->getMessage());
            }
        }


        // Redirect back with status message
        return redirect()->route('staff.bulk-emails')->with('status', "Emails sent: $successful successful, $failed failed.");
    }
}
