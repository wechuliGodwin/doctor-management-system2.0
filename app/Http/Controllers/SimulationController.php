<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SimApplication;
use App\Mail\SimCoursesMail;
use Illuminate\Support\Facades\Mail;

class SimulationController extends Controller
{
    public function index()
    {
        return view('simulation');
    }

    public function register()
    {
        // Handle registration logic here
        return view('simulation.register'); // Assuming you have a registration form view
    }

    public function submitApplication(Request $request) {
        //handle incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:100',
            'course' => 'required|string'
        ]);

        try {
            $newApplication = SimApplication::create($validated);
            Log::info('New simlab course application submitted successfully.', ['id' => $newApplication->id]);
            // Send an email notification
            Mail::to('simtech@kijabehospital.org')  // Main recipient
                 ->cc('simlabadmin@kijabehospital.org')  // CC to simlabadmin
                // ->bcc('simlabadmin@kijabehospital.org')  // BCC to IT manager
                ->send(new SimCoursesMail($validated));
            Log::info('Email notification sent successfully.', ['to' => 'simtech@kijabehospital.org']);
            return back()->with('success', 'Your application has been submitted successfully');

        } catch (\Exception $e) {
            Log::error('Failed to submit application:', ['error' => $e->getMessage()]);
            return back()->with('error', 'There was an issue submitting your application. Please try again.');
        }
    }
}
