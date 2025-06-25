<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitingLearner;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VisitingLearnerApplicationMail;

class VisitingLearnerController extends Controller
{
    /**
     * Show the form for creating a new Visiting Learner application.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Log::info('VisitingLearnerController@create accessed.');
        return view('visiting-learners.create');
    }

    /**
     * Store a newly created Visiting Learner application in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function index()
    {
        Log::info('VisitingLearnerController@index accessed.');
        $learners = VisitingLearner::all();  // Fetch all visiting learners
        return view('visiting-learners.index', ['learners' => $learners]);
    }
    public function store(Request $request)
    {
        Log::info('VisitingLearnerController@store called.');
        Log::debug('Validating request data.', ['input' => $request->all()]);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'current_institution' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'year_of_training' => 'nullable|string|max:255',
            'preferred_start_date' => 'nullable|date',
            'preferred_end_date' => 'nullable|date|after_or_equal:preferred_start_date',
            'gender' => 'nullable|string|max:10',
            'traveling_with_family' => 'nullable|boolean',
            'preferred_specialty_option1' => 'required|string|max:255',
            'preferred_specialty_option2' => 'nullable|string|max:255',
            'preferred_specialty_option3' => 'nullable|string|max:255',
            'preferred_specialty_other' => 'nullable|string|max:255',
            'coordinating_organization' => 'nullable|string|max:255',
            'referee1_name' => 'nullable|string|max:255',
            'referee1_email' => 'nullable|email|max:255',
            'referee2_name' => 'nullable|string|max:255',
            'referee2_email' => 'nullable|email|max:255',
            'goals' => 'nullable|string',
            'prior_experience' => 'nullable|string',
            'future_plans' => 'nullable|string',
            'faith_practice' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'passport_biodata_page' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'academic_professional_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'curriculum_vitae' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'passport_size_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'md_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'current_practising_licence' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Custom logic to ensure both or neither of md_certificate and current_practising_licence are uploaded
        if ($request->hasFile('md_certificate') !== $request->hasFile('current_practising_licence')) {
            return back()->withErrors([
                'md_certificate' => 'Both MD Certificate and Current Practising Licence must be uploaded. Applies only to applicants who are residents.',
                'current_practising_licence' => 'Both MD Certificate and Current Practising Licence must be uploaded. Applies only to applicants who are residents.',
            ])->withInput();
        }

        Log::info('Validation successful.', ['validatedData' => $validatedData]);

        try {
            // Handle file uploads
            $filePaths = $this->handleFileUploads($request);

            $learner = VisitingLearner::create(array_merge($validatedData, $filePaths));
            Log::info('VisitingLearner record created successfully.', ['learner_id' => $learner->id]);

            // Send email notification
            $this->sendEmailNotification($validatedData);

            // Redirect with success message
            return back()->with('success', 'Your application has been submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to submit Visiting Learner application.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'There was an issue submitting your application. Please try again.');
        }
    }

    private function handleFileUploads($request)
    {
        $filePaths = [];
        $fileFields = [
            'passport_biodata_page',
            'academic_professional_certificate',
            'curriculum_vitae',
            'passport_size_photo',
            'md_certificate',
            'current_practising_licence',
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('uploads', 'public'); // Store in storage/app/public/uploads
                $filePaths[$field] = $path; // Save the relative path (e.g., "uploads/filename.ext")
                Log::debug("File uploaded successfully.", ['field' => $field, 'path' => $path]);
            }
        }

        return $filePaths;
    }

    private function sendEmailNotification($validatedData)
    {
        try {
            Log::info('Attempting to send email notification.', ['recipient' => 'ictintern007@kijabehospital.org']);

            $emailType = 'New Visiting Learner Application';

            // Fetch the learner record to get file paths
            $learner = VisitingLearner::where('contact_email', $validatedData['contact_email'])
                ->orderBy('created_at', 'desc')
                ->first();

            // Merge file paths into validatedData
            $fileFields = [
                'passport_biodata_page',
                'academic_professional_certificate',
                'curriculum_vitae',
                'passport_size_photo',
                'md_certificate',
                'current_practising_licence',
            ];

            foreach ($fileFields as $field) {
                if (!empty($learner->$field)) {
                    $validatedData[$field] = $learner->$field; // Add file path to validatedData
                }
            }

            Mail::to('direduc@kijabehospital.org')  // Main recipient
                ->bcc('ictmgr@kijabehospital.org')  // BCC recipient
                ->cc('visitorgmecoord@kijabehospital.org')  // CC recipient
                ->send(new VisitingLearnerApplicationMail($validatedData, $emailType));

            Log::info('Email notification sent successfully.');
        } catch (\Exception $mailException) {
            Log::error('Failed to send email.', ['error' => $mailException->getMessage()]);
            throw $mailException;
        }
    }
}
