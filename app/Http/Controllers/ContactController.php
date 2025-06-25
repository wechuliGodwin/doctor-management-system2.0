<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Display the contact page and track the view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Track the page view for /contact
        $today = now()->toDateString();
        DB::statement(
            'INSERT INTO page_views (url, view_date, view_count) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE view_count = view_count + 1',
            ['contact', $today]
        );

        return view('contact');
    }

    /**
     * Handle the submission of the contact form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {
        // Define disallowed words
        $disallowedWords = [
            'sex',
            'seo',
            'marketing',
            'viagra',
            'porn',
            'xxx',
            'gambling',
            'drugs',
            // Add more words as needed
        ];

        // Define allowed domains
        $allowedDomains = [
            'gmail.com',
            'hotmail.com',
            'yahoo.com',
            'kijabehospital.org',
            'org',
            'ac.ke',
            'co.ke',
            'edu'
        ];

        // Disallowed email addresses
        $disallowedEmails = ['testing@example.com'];

        // Regular expression to detect URLs
        $urlPattern = '/(https?:\/\/[^\s]+)/i';

        // Name to block
        $blockedName = 'RobertRog';

        // 1. Validate the form data with custom rules
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($blockedName) {
                    if (stripos($value, $blockedName) !== false) {
                        return $fail('Submissions with this name are not allowed.');
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($allowedDomains, $disallowedEmails) {
                    $emailDomain = substr(strrchr($value, "@"), 1);

                    // Check for disallowed specific emails
                    if (in_array($value, $disallowedEmails)) {
                        return $fail('This email address is not allowed.');
                    }

                    // Extract the top-level domain
                    $tld = substr(strrchr($emailDomain, '.'), 1);

                    // Check if the domain or TLD is allowed
                    if (!in_array($emailDomain, $allowedDomains) && !in_array($tld, $allowedDomains)) {
                        return $fail('Emails from this domain are not allowed. Please use a different email address.');
                    }
                },
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+?[0-9\s\-\(\)]+$/',
            ],
            'message' => [
                'required',
                'string',
                'max:5000',
                'regex:/^[\w\s]*$/',
                function ($attribute, $value, $fail) use ($disallowedWords, $urlPattern) {
                    // Check for disallowed words
                    foreach ($disallowedWords as $word) {
                        if (stripos($value, $word) !== false) {
                            return $fail('Your message contains inappropriate content. Please remove any obscene or prohibited words.');
                        }
                    }
                    // Check for URLs
                    if (preg_match($urlPattern, $value)) {
                        return $fail('Links are not allowed in the message. Please remove any URLs.');
                    }
                },
            ],
        ]);

        // 2. Sanitize input data
        $name = strip_tags($request->input('name'));
        $email = strip_tags($request->input('email'));
        $phone = strip_tags($request->input('phone'));
        $messageContent = strip_tags($request->input('message'));

        // 3. Prepare the email data
        $subscribe = $request->has('subscribe') ? 'Yes' : 'No';
        $emailData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $messageContent,
            'subscribe' => $subscribe,
        ];

        // 4. Send the email to multiple recipients (using try-catch)
        try {
            Mail::to([
                'ictintern007@kijabehospital.org',
                // 'enquiries@kijabehospital.org',
                // 'ictmgr@kijabehospital.org',
                // 'booking@kijabehospital.org',
            ])->send(new ContactFormMail($emailData));
        } catch (\Exception $e) {
            \Log::error('Contact form failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting your message. Please try again later.');
        }
        // 5. Redirect back with a success message
        return back()->with('success', 'Thank you for your message! We will get back to you shortly.');
    }
}