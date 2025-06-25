<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        return $this->view('emails.booking_confirmation')
                    ->subject('New Appointment Booking')
                    ->from('systemdev@kijabehospital.org')
                    ->with([
                        'name' => $this->emailData['name'],
                        'email' => $this->emailData['email'],
                        'phone' => $this->emailData['phone'],
                        'appointment_date' => $this->emailData['appointment_date'],
                        'service' => $this->emailData['service'],
                        'notes' => $this->emailData['notes'],
                        'message' => $this->emailData['message'], // Include the formatted message
                    ]);
    }
}
