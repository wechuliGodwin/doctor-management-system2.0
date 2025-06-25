<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ResearchRequest;

class ResearchRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $researchRequest;

    public function __construct(ResearchRequest $researchRequest)
    {
        $this->researchRequest = $researchRequest;
    }

    public function build()
    {
        return $this->view('emails.research_request')
                    ->subject('New ISERC Research Request Submitted')
                    ->attach(storage_path('app/public/' . $this->researchRequest->pi_cv_path), [
                        'as' => 'Principal_Investigator_CV.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with(['researchRequest' => $this->researchRequest]);
    }
}
