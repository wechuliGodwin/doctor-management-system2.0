<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objective;
use App\Models\FeatureRequest;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{
    /**
     * Show the email input form.
     *
     * @return \Illuminate\View\View
     */
    public function showEmailForm()
    {
        return view('services.emr_email_form');
    }

    /**
     * Validate the email and redirect to the EMR page if valid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateEmail(Request $request)
    {
        // Validate the email structure
        $request->validate([
            'email' => 'required|email|ends_with:@kijabehospital.org',
        ]);

        // Store the email in the session for later use
        session(['user_email' => $request->email]);

        // Redirect to the EMR benchmarking page
        return redirect()->route('emr.benchmarking.page');
    }

    /**
     * Show the EMR benchmarking page.
     *
     * @return \Illuminate\View\View
     */
    public function showBenchmarkingPage()
    {
        // Check if the user email is stored in the session
        if (!session()->has('user_email')) {
            return redirect()->route('emr.benchmarking.form')->with('error', 'You must enter a valid Kijabe email to access this page.');
        }

        return view('services.emr');
    }

    /**
     * Store a newly created objective in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitObjective(Request $request)
    {
        try {
            // Log the request data
            Log::info('Objective Submission Data:', $request->all());

            $request->validate([
                'objective' => 'required|string',
                'department' => 'required|string',
                'otherDepartment' => 'nullable|string',
            ]);

            $data = [
                'objective' => $request->objective,
                'department' => $request->department,
                'other_department' => $request->department == 'Other' ? $request->otherDepartment : null,
            ];

            // Log the data that's about to be inserted
            Log::info('Data to be inserted into Objectives:', $data);

            Objective::create($data);

            // Log after creation to check if data was inserted
            Log::info('Objective created successfully for ID:', [Objective::latest()->first()->id]);

            return redirect()->back()->with('success', 'Objective submitted successfully!');
        } catch (Exception $e) {
            Log::error('Error storing objective: ' . $e->getMessage());
            Log::error('Request Data:', $request->all());
            return redirect()->back()->with('error', 'An error occurred while submitting your objective. Please try again.');
        }
    }

    /**
     * Store a newly created feature request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitFeature(Request $request)
    {
        try {
            $request->validate([
                'feature' => 'required|string',
                'department' => 'required|string',
                'otherDepartment' => 'nullable|string',
                'importance' => 'required|string',
            ]);

            $data = [
                'feature' => $request->feature,
                'department' => $request->department,
                'other_department' => $request->department == 'Other' ? $request->otherDepartment : null,
                'importance' => $request->importance,
            ];

            FeatureRequest::create($data);
            return redirect()->back()->with('success', 'Feature request submitted successfully!');
        } catch (Exception $e) {
            // Log the exception
            Log::error('Error storing feature request: ' . $e->getMessage());
            // Optionally, log the request data or other debugging info
            Log::error('Request Data:', $request->all());
            return redirect()->back()->with('error', 'An error occurred while submitting your feature request. Please try again.');
        }
    }

    /**
     * Display a listing of objectives.
     *
     * @return \Illuminate\View\View
     */
    public function showObjectives()
    {
        $objectives = Objective::latest()->paginate(10);
        return view('services.objectives', compact('objectives'));
    }

    /**
     * Display a listing of feature requests.
     *
     * @return \Illuminate\View\View
     */
    public function showFeatures()
    {
        $features = FeatureRequest::latest()->paginate(10);
        return view('services.features', compact('features'));
    }

    /**
     * Show ERP Lifecycle page.
     *
     * @return \Illuminate\View\View
     */
    public function showErpLifecycle()
    {
        return view('services.erp_lifecycle');
    }
}