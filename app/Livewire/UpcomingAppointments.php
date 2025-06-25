<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;

class UpcomingAppointments extends Component
{

    use WithPagination; 

    public $todayAppointmentsCount;

    
    public $perPage = 5;

    
    protected $updatesQueryString = ['page']; 

    public function mount()
    {
        
        $this->todayAppointmentsCount = Appointment::whereDate('appointment_date', now())->count();
    }

    public function render()
    {
        
        return view('livewire.upcoming-appointments', [
            'appointments' => Appointment::orderBy('appointment_date', 'asc')->paginate($this->perPage)
        ]);
    }
}
