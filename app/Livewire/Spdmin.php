<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MyAppointment;
use App\Models\Schedule;
use App\Models\TimeSlot;
use Livewire\Component;
use Illuminate\Support\Str; 


class Spdmin extends Component
{

    public $appointments;
    public $doctors;
    public $hmis_number;
    public $patient_name;
    public $appointment_date;
    public $payment_status;
    public $notes;
    public $appointment_id;

    public function mount()
    {
        $this->loadAppointments();
        $this->loadDoctors();
    }

    public function loadAppointments()
    {
        $this->appointments = Appointment::with('doctor', 'patient')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function loadDoctors()
    {
        $this->doctors = Doctor::all();

        foreach ($this->doctors as $doctor) {
            $doctor->available_slots = TimeSlot::where('doctor_id', $doctor->id)
                ->where('is_booked', false)
                ->pluck('slot_time')
                ->toArray();
        }
    }

    public function updateAppointment()
    {
        $this->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'hmis_number' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'payment_status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($this->appointment_id);

        $meetingId = Str::random(10);

        $appointment->hmis_patient_number = $this->hmis_number;
        $appointment->patient_name = $this->patient_name;
        $appointment->appointment_date = $this->appointment_date;
        $appointment->payment_status = $this->payment_status;
        $appointment->notes = $this->notes;
        $appointment->meeting_id = $meetingId;
        $appointment->save();

        session()->flash('success', 'Appointment updated successfully. Meeting ID: ' . $meetingId);
        
        // Refresh appointments after updating
        $this->loadAppointments();
    }

    public function saveSchedules()
    {
        $appointments = Appointment::all();

        foreach ($appointments as $appointment) {
            Schedule::create([
                'hmis_patient_number' => $appointment->hmis_patient_number,
                'patient_name' => $appointment->patient_name,
                'appointment_date' => $appointment->appointment_date,
                'payment_status' => $appointment->payment_status,
                'notes' => $appointment->notes,
            ]);
        }

        session()->flash('success', 'Schedules saved successfully!');
    }

    public function storeAppointment()
    {
        $validated = $this->validate([
            'hmis_number' => 'required|string|max:255',
            'doctor_id' => 'required|string|max:255',
            'time_slot' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        MyAppointment::create($validated);

        return response()->json(['success' => true]);
    }
    public function render()
    {
        return view('livewire.spdmin', [
            'appointments' => $this->appointments,
            'doctors' => $this->doctors,
        ]);
    }
}
