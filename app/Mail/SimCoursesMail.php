<?php 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimCoursesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->from('systemdev@kijabehospital.org')
                    ->subject('New Application for Simulation Course')
                    ->markdown('emails.simulation_courses_application', ['data' => $this->data]);
    }
}

