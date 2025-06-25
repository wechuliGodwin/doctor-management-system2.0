<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitingLearnerApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $emailType;

    /**
     * Create a new message instance.
     *
     * @param array $data The validated application data.
     * @param string $emailType The type of email being sent.
     * @return void
     */
    public function __construct(array $data, $emailType = 'New Application')
    {
        $this->data = $data;
        $this->emailType = $emailType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Visiting Learner Application')
            ->markdown('emails.visiting_learner_application')
            ->with([
                'application' => $this->data,
                'emailType' => $this->emailType,
            ]);
    }
}
