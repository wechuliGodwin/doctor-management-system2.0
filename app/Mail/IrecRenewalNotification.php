<?php

// app/Mail/IrecRenewalNotification.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IrecRenewalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IREC Renewal Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.irec-renewal-notification', // Updated to use the new view
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
