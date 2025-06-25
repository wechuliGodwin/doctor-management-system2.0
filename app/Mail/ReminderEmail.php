<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\IrecApplication; // Correct import from App\Models

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\IrecApplication  $application
     * @return void
     */
    public function __construct(IrecApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reminder')
                    ->subject('Reminder: IREC Application Expiration')
                    ->with(['application' => $this->application]);
    }
}
