<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithFileUploads;

class Appointments extends Component
{
    use WithFileUploads;
    public $appointments;
    public $appointmentId;
    public $feedbackMessage;
    public $feedbackType;
    public $file;
    public function mount()
    {
        $this->appointments = Appointment::whereDate('appointment_date', today())->get();
    }

    public function uploadFile($id)
    {
        $this->appointmentId = $id;

        $this->validate([
            'file' => 'required|file|mimes:pdf|max:2048', // Adjust as needed
        ]);

        // Find the appointment by ID
        $appointment = Appointment::find($this->appointmentId);

        if ($appointment) {
            try {
                $path = $this->file->store('public/uploads'); // Store the file in the 'public/uploads' directory
                $appointment->path = $path; // Save the path
                $appointment->save();
                $this->feedbackMessage = 'File uploaded successfully!';
                $this->feedbackType = 'success';
            } catch (\Exception $e) {
                $this->feedbackMessage = 'File upload failed: ' . $e->getMessage();
                $this->feedbackType = 'error';
            }
        }

        // Reset file input
        $this->file = null;

        // Refresh appointments list (optional)
        $this->appointments = Appointment::all();
    }
    public function render()
    {
        return view('livewire.appointments');
    }
}
