<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Supplier;
use Illuminate\Http\UploadedFile;

class BulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $messageContent;
    public $attachment;
    public $supplier;

    public function __construct(Supplier $supplier, $subject = 'New Services Alert', $messageContent = null, $attachment = null)
    {
        $this->supplier = $supplier;
        $this->subject = $subject;
        $this->messageContent = $messageContent ?: "Dear {$supplier->name}, access expert care with Kijabe Hospital's Telemedicine Services, now including Telepharmacy with delivery. Book at: https://kijabehospital.org/telemedicine-patient";
        $this->attachment = $attachment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'suppliers.template',
            with: [
                'supplier' => $this->supplier,
                'messageContent' => $this->messageContent,
            ],
        );
    }

    public function attachments(): array
    {
        if ($this->attachment) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($this->attachment->getRealPath())
                    ->as($this->attachment->getClientOriginalName())
                    ->withMime($this->attachment->getClientMimeType()),
            ];
        }
        return [];
    }

    public function build()
    {
        $email = $this->subject($this->subject)
            ->view('suppliers.template')
            ->with([
                'supplier' => $this->supplier,
                'messageContent' => $this->messageContent,
            ]);

        if ($this->attachment) {
            $email->attach($this->attachment->getRealPath(), [
                'as' => $this->attachment->getClientOriginalName(),
                'mime' => $this->attachment->getClientMimeType(),
            ]);
        }

        return $email;
    }
}