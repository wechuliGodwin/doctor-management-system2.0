<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Log;


class DoctorController extends Controller
{

    // Handle form submission from "Talk to a Doctor"
public function submitConcern(Request $request)
{
    // Validate incoming request
    $request->validate([
        'platform' => 'required|string',
        'concern' => 'required|string|max:500',
        'mpesa_code' => 'required|string',
        'appointment_date' => 'required|date',
    ]);


    // Create or update the appointment or concern record
    $appointment = new Appointment(); // Assuming you have an Appointment model
    $appointment->platform = $request->platform;
    $appointment->description = $request->concern;
    $appointment->mpesa_code = $request->mpesa_code;
    $appointment->appointment_date = $request->appointment_date;
    $appointment->user_id = FacadesAuth::id(); // Associate with the logged-in user
    $appointment->save();
    
    // Optionally, add success message or redirect
    return redirect()->back()->with('success', 'Concern submitted successfully!');
}

}

