<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function createMeeting(Request $request)
    {
        // Get the id from the query string (e.g., ?appointment_id=9)
        $id = $request->query('appointment_id'); // Extract the id from the URL

        // Fetch the appointment and user details
        $appointment = DB::table('appointments')
            ->join('users', 'appointments.user_id', '=', 'users.id') // Assuming appointments are linked to users
            ->select('appointments.id', 'appointments.meeting_id', 'users.name', 'users.dob')
            ->where('appointments.id', $id)
            ->first();

        // Calculate the age based on the birth_date if available
        $age = null;
        if ($appointment && $appointment->dob) {
            $age = Carbon::parse($appointment->dob)->age; // Use Carbon to calculate the age
        }

        // Return the view with the appointment and age data
        return view('livewire.create-Room', [
            'appointments' => $appointment,
            'age' => $age
        ]);
    }
}

