<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Subscription Confirmed')
                    ->view('emails.subscription_confirmed');
    }
}
