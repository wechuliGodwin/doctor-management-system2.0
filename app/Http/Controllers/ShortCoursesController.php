<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortCourseApplication;  // Model to handle data storage
use App\Mail\ShortCourseApplicationMail;  // Mailable class
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ShortCoursesController extends Controller
{
    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'course' => 'required|string'
        ]);

        try {
            // Store the application data in the database
            $application = ShortCourseApplication::create($validated);
            Log::info('Short Course application saved successfully.', ['id' => $application->id]);

            // Send an email notification
Mail::to('spmgr@kijabehospital.org')  // Main recipient
    ->cc('direduc@kijabehospital.org')  // CC to Director of Education
    ->bcc('ictmgr@kijabehospital.org')  // BCC to IT manager
    ->send(new ShortCourseApplicationMail($validated));
Log::info('Email notification sent successfully.', ['to' => 'spmgr@kijabehospital.org', 'cc' => 'direduc@kijabehospital.org']);

            return back()->with('success', 'Your application has been received. You will be contacted soon.');
        } catch (\Exception $e) {
            Log::error('Failed to process application:', ['error' => $e->getMessage()]);
            return back()->with('error', 'There was an issue submitting your application. Please try again.');
        }
    }
}
