<?php

namespace App\Mail;

use App\Models\IrecApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(IrecApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->view('emails.template')
                    ->subject('Renewal Reminder')
                    ->with([
                        'application' => $this->application,
                    ]);
    }
}
