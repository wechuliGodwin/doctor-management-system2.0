<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Appointment; // Import your Appointment model

class DoctorDashboard extends Component
{
    public function render()
    {
        return view('livewire.doctor-dashboard');
    }
}
