<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment; // Import the Appointment model
use Illuminate\Support\Facades\DB; // Import the DB facade for raw queries

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        // Fetch the latest meeting_id from appointments
        $latestMeeting = DB::select("SELECT meeting_id FROM appointments ORDER BY id DESC LIMIT 1");

        // Initialize $meeting_id to null
        $meeting_id = null;

        // Check if any meeting ID is found
        if (!empty($latestMeeting)) {
            $meeting_id = $latestMeeting[0]->meeting_id; // Assign the latest meeting_id
        }

        // Pass the latest meeting_id to the view
        return view('livewire.create-meeting', compact('meeting_id')); // Use the correct path for the view
    }
    // MeetingController.php
    public function createMeeting($appointment_id)
    {
        // Fetch the appointment details using the appointment_id
        $appointment = Appointment::find($appointment_id);

        // Assume meeting_id is a field in the Appointment model
        $meeting_id = $appointment->meeting_id; // or however you retrieve it

        // Pass the necessary data to the view
        return view('livewire.create-meeting', [
            'appointment' => $appointment,
            'meeting_id' => $meeting_id, // Pass the meeting_id to the view
    ]);}

}