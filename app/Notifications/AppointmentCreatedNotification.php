<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCreatedNotification extends Notification
{
    use Queueable;

    protected $appointmentDetails;

    public function __construct($appointmentDetails)
    {
        $this->appointmentDetails = $appointmentDetails;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Appointment Booked')
            ->line('A new appointment has been booked.')
            ->line('Service: ' . $this->appointmentDetails['service_name'])
            ->line('Appointment Date: ' . $this->appointmentDetails['appointment_date']);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new appointment has been booked by a user.',
            'service_name' => $this->appointmentDetails['service_name'],
            'appointment_date' => $this->appointmentDetails['appointment_date'],
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'A new appointment has been booked.',
            'service_name' => $this->appointmentDetails['service_name'],
            'appointment_date' => $this->appointmentDetails['appointment_date'],
        ]);
    }
}
