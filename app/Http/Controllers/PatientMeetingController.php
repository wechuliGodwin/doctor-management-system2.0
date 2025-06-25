<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;


class PatientMeetingController extends Controller
{
    
    public function createMeeting(Request $request)
    {
        // Get the id from the query string (e.g., ?appointment_id=9)
        $id = $request->query('appointment_id');  // Extract the id from the URL

        // Fetch the record from the appointments table using the id
        $appointment = DB::table('appointments')
            ->select('id', 'meeting_id')  // Select both id and meeting_id
            ->where('id', $id)  // Find the row where id matches the value from the URL
            ->first();

        // Return the view with the appointment data
        return view('livewire.create-Roompatient', ['appointments' => $appointment]);
    }
}
