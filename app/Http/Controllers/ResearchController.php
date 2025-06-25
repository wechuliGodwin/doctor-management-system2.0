<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResearchRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ResearchController extends Controller
{
    public function showIsercForm()
    {
        Log::info('Showing ISERC form');
        return view('education.iserc');
    }

    public function submitIsercForm(Request $request)
    {
        try {
            Log::info('Attempting to submit ISERC form', ['input' => $request->all()]);

            // Validate the request
            $validatedData = $request->validate([
                'pi_name'                   => 'required|string|max:255',
                'pi_email'                  => 'required|email',
                'pi_address'                => 'required|string',
                'pi_mobile'                 => 'required|string',
                'human_subjects_training'   => 'required|string',
                'pi_cv'                     => 'required|file|mimes:pdf,doc,docx|max:2048',
                'co_investigator_1'         => 'nullable|string|max:255',
                'co_investigator_2'         => 'nullable|string|max:255',
                'co_investigator_3'         => 'nullable|string|max:255',
                'co_investigator_cvs.*'     => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'research_proposal'         => 'required|file|mimes:pdf,doc,docx|max:20480',
                'additional_documents.*'    => 'nullable|file|mimes:pdf,doc,docx|max:20480',
                'conflicts'                 => 'required|string',
                'conflictExplanation'       => 'nullable|string',
                'localSupervisor'           => 'nullable|string',
                'department'                => 'nullable|string',
                'supervisorPhone'           => 'nullable|string',
                'supervisorEmail'           => 'nullable|email',
                'ethicalReview'             => 'required|string',
                'reviewDetails'             => 'nullable|string',
                'payment_reference'         => 'required|string|max:255',
            ]);

            Log::info('Form data validated successfully', ['validatedData' => $validatedData]);

            // Define the common storage path for all uploads
            $storagePath = 'uploads/pi_cvs';

            // Store PI CV
            $piCvPath = $request->file('pi_cv')->store($storagePath, 'public');
            Log::info('PI CV stored', ['path' => $piCvPath]);

            // Store Co-Investigator CVs
            $coInvestigatorCvPaths = $request->file('co_investigator_cvs') ? 
                array_map(fn($file) => $file->store($storagePath, 'public'), $request->file('co_investigator_cvs')) : [];
            Log::info('Co-Investigator CVs stored', ['paths' => $coInvestigatorCvPaths]);

            // Store Research Proposal
            $researchProposalPath = $request->file('research_proposal')->store($storagePath, 'public');
            Log::info('Research Proposal stored', ['path' => $researchProposalPath]);

            // Store Additional Documents
            $additionalDocumentsPaths = $request->file('additional_documents') ? 
                array_map(fn($file) => $file->store($storagePath, 'public'), $request->file('additional_documents')) : [];
            Log::info('Additional Documents stored', ['paths' => $additionalDocumentsPaths]);

            // Create and save the research request
            $researchRequest = ResearchRequest::create(array_merge($validatedData, [
                'pi_cv_path' => $piCvPath,
                'co_investigator_cv_paths' => json_encode($coInvestigatorCvPaths),
                'research_proposal_path' => $researchProposalPath,
                'additional_documents_paths' => json_encode($additionalDocumentsPaths),
            ]));
            Log::info('Research Request saved to database', ['id' => $researchRequest->id]);

            //Send email notification with attachments
            Mail::send('emails.research_request', compact('researchRequest'), function ($message) use ($researchRequest, $piCvPath, $coInvestigatorCvPaths, $researchProposalPath, $additionalDocumentsPaths) {
                $recipients = [
                    'ictmgr@kijabehospital.org',
                    'researchcoord@kijabehospital.org',
                ];

                $message->to($recipients);
                $message->subject('New ISERC Research Request Submitted');

                $this->attachFilesIfExist($message, $piCvPath, $coInvestigatorCvPaths, $researchProposalPath, $additionalDocumentsPaths);
            });

            if (Mail::failures()) {
                Log::error('Failed to send email for Research Request', ['id' => $researchRequest->id, 'failures' => Mail::failures()]);
            } else {
                Log::info('Email sent successfully for Research Request', ['id' => $researchRequest->id]);
            }

             //Return back with success message
             return back()->with('success', 'Research Request submitted successfully! Your payment reference will be validated.');
	    //session()->flash('Success', 'Research Request submitted successfully! Your payment reference will be validated.');
	    //return redirect()->route('iserc');

        } catch (ValidationException $e) {
            // Log validation errors
            Log::error('Validation Error in submitIsercForm', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log any other exceptions
            Log::error('Unexpected Error in submitIsercForm', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'An unexpected error occurred while submitting the form. Please try again.');
        }
    }

    private function attachFilesIfExist($message, ...$files)
    {
        foreach ($files as $file) {
            if (is_array($file)) {
                foreach ($file as $f) {
                    $message->attach(storage_path('app/public/' . $f));
                    Log::info('Attached file', ['path' => $f]);
                }
            } elseif ($file) {
                $message->attach(storage_path('app/public/' . $file));
                Log::info('Attached file', ['path' => $file]);
            }
        }
    }
}
