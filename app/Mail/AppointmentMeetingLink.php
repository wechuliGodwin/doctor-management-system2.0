<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentMeetingLink extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $meetingLink;

    public function __construct($appointment, $meetingLink)
    {
        $this->appointment = $appointment;
        $this->meetingLink = $meetingLink;
    }

    public function build()
    {
        return $this->subject('Your Telemedicine Appointment Meeting Link')
                    ->view('emails.appointment_meeting_link');
    }
}
