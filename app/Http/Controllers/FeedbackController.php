<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback'); // Make sure 'feedback' is the correct view name
    }

    public function store(Request $request)
    {
        // Validate the request data (optional but recommended)
        $request->validate([
            // Your validation rules here
        ]);

        // Store the feedback data in the database
        $feedback = Feedback::create($request->all());

        // Prepare data for the email, including 'future_contact'
        $data = $request->all();
        $data['future_contact'] = $request->input('future_contact', false); // Default to false if not provided

        // Send the email notification
        Mail::send('emails.feedback', $data, function ($message) {
            $message->to('tmakori@kijabehospital.org')
                ->cc(['ictmgr@kijabehospital.org'])
                ->subject('New Patient Feedback Received');
        });

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}