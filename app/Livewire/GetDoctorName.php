<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GetDoctorName extends Component
{
    public $name;

    public function mount()
    {
        //$this->appointments = Appointment::;
        $email = Auth::user()->email;
        $this->name = User::where('email', $email)->value('name');


    }

    public function render()
    {
        return view('livewire.get-doctor-name', [
            'name' => $this->name,
        ]);
    }
}
