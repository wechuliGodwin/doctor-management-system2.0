<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionConfirmed;

class NewsletterController extends Controller
{
    // Show the subscription form (optional if the form is on a different page)
    public function showForm()
    {
        return view('newsletter.subscribe');
    }

    // Handle the subscription
    public function subscribe(Request $request)
    {
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Store the subscriber
        NewsletterSubscriber::create([
            'email' => $request->email,
        ]);

        // Optionally, send a confirmation email
        Mail::to($request->email)->send(new SubscriptionConfirmed());

        // Redirect back with success message
        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
