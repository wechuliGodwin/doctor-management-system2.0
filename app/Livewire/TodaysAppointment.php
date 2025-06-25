<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;

class TodaysAppointment extends Component
{
    public $appointments;

    public function mount() {
         $this->appointments = Appointment::whereDate('appointment_date', today())->get();
    }
    public function render()
    {
        return view('livewire.todays-appointment');
    }
}
