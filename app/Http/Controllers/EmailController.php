<?php

namespace App\Http\Controllers;

use App\Mail\BulkEmail;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function showSendEmailForm()
    {
        return view('send_email');
    }

    public function sendBulkEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,docx,jpg,png',
        ]);

        $emails = Email::all();
        $failedEmails = [];
        $successCount = 0;

        foreach ($emails as $email) {
            try {
                Mail::to($email->email)->send(new BulkEmail($request->subject, $request->message, $request->file('attachment')));
                $successCount++;
            } catch (\Exception $e) {
                $failedEmails[] = $email->email;
            }
        }

        return back()->with('success', "Emails sent: $successCount, Failed: " . count($failedEmails));
    }
}
