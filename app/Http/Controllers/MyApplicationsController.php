<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IrecApplication;
use Illuminate\Support\Facades\Log;
use App\Models\Researcher;

class MyApplicationsController extends Controller
{
    /**
     * Display all applications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all applications
        $applications = IrecApplication::all();

        // Return a view with the applications data
        return view('my-applications.index', compact('applications'));
    }

    /**
     * Show the form to create a new application.
     *
     * @return \Illuminate\View\View
     */
    public function createApplication()
    {
        // Get the email of the currently authenticated user
        $userEmail = auth()->user()->email;

        // Fetch the researcher record matching the authenticated user's email
        $researcher = Researcher::where('email', $userEmail)->first();

        // Check if the researcher record was found
        if (!$researcher) {
            return redirect()->route('my-applications.index')->with('error', 'Researcher data not found.');
        }

        // Pass the researcher data to the view
        return view('my-applications.new', compact('researcher'));
    }

    /**
     * Store a new application in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeApplication(Request $request)
    {
        // Log the incoming request data for debugging
        Log::info('Request Data:', $request->all());

        // Custom validation
        $validatedData = $request->validate([
            'researcher_unique_number' => 'required|string',
            'date_of_approval' => 'required|date',
            'date_of_renewal' => 'nullable|date',
            'reference_number_given' => 'required|string',
            'reference_number_2024' => 'required|string',
            'approval_number_2024' => 'required|string',
            'proposal_title' => 'required|string',
            'principal_investigator' => 'required|string',
            'new_resubmission' => 'nullable|string',
            'payment' => 'nullable|string',
            'end_of_study_data' => 'nullable|string',
            'approval_letter' => 'nullable|string',
            'kh_iserc_form' => 'nullable|string',
            'evaluation_1' => 'nullable|string',
            'evaluation_2' => 'nullable|string',
            'cvs_pi_co_pis' => 'nullable|string',
            'cv_co_pi' => 'nullable|string',
            'human_subjects_data_protection' => 'nullable|string',
            'annual_report' => 'required|file|max:2048', // max 2MB
        ]);

        // Check if the researcher_unique_number is filled
        if ($request->filled('researcher_unique_number')) {
            // Log the unique number before saving
            Log::info('Researcher Unique Number before saving:', ['researcher_unique_number' => $request->input('researcher_unique_number')]);
        } else {
            Log::error('Researcher unique number is empty before saving.');
            return redirect()->back()->with('error', 'Researcher unique number is required.');
        }

        // Handle file upload if present
        if ($request->hasFile('annual_report')) {
            $file = $request->file('annual_report');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            $validatedData['annual_report'] = $filePath;

            Log::info('File stored successfully at path: ' . $filePath);
        } else {
            Log::warning('No annual report file found in the request.');
        }

        // Log the data before saving to the database
        Log::info('Storing IREC Application', $validatedData);

        // Create the IrecApplication record
        $irecApplication = new IrecApplication($validatedData);

        // Manually set the researcher_unique_number to ensure it's being saved
        $irecApplication->researcher_unique_number = $request->input('researcher_unique_number');

        // Save the record
        $irecApplication->save();

        // Log success or failure
        if ($irecApplication->wasRecentlyCreated) {
            Log::info('IREC Application created successfully.', $validatedData);
            return redirect()->route('my-applications.index')->with('success', 'IREC Application created successfully.');
        } else {
            Log::error('Failed to create IREC Application.');
            return redirect()->back()->with('error', 'Failed to create IREC Application.');
        }
    }
}
