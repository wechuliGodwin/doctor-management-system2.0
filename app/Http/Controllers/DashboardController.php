<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    public function index()
    {

        //get today's date
        $todayDate = Carbon::now()->format('Y-m-d');

        // Fetch available services
        $availableServices = DB::table('services')->count(); // Count of available services
        $services = Service::select('id', 'name', 'cost', 'status', 'department')->get(); // Fetch id, name, and cost for all services

        $upId = Auth::id();

        // Fetch available time slots (appointments in the future)
        // $availableTimeSlots = Appointment::where('created_at', '>', $todayDate)->count();
        $availableTimeSlots = DB::table('appointments')
            ->where('user_id', $upId)
            ->where('status', 'confirmed')
            ->get()->count();

        // Fetch available doctors
        $availableDoctors = Doctor::where('is_available', true)->count();

        // $upcomingAppointments = DB::table('appointments')
        //     ->where('user_id', $upId)
        //     ->where('updated_at', '>', $todayDate) // Carbon::now()
        //     ->get();
                $userId = Session::get('user_id');

$upcomingAppointments = Appointment::with(['user.uploads' => function ($query) {
    $query->select('user_id', 'filepath'); // Only select user_id and filepath
}])->where('user_id', $upId)
  ->where('status', 'confirmed')
  ->get();

$allAppointments = Appointment::with(['user.uploads' => function ($query) {
    $query->select('user_id', 'filepath'); // Only select user_id and filepath
}])->where('user_id', $upId)
  ->where('status', 'confirmed')
  ->get();

        // Fetch the authenticated user
        $user = Auth::user();

        if ($user) {
            // Store the user_id in the session for later use
            Session::put('user_id', $user->id);


            if ($user) {
                if ($user->role == 'doctor') {
                    return view('livewire.doctor-dashboard');
                } elseif ($user->role == 'superadmin') {
                    return view('livewire.superadmin');
                } else {
                    // Default for regular users
                    return view('/dashboard', [
                        'availableServices' => $availableServices,
                        'availableTimeSlots' => $availableTimeSlots,
                        'availableDoctors' => $availableDoctors,
                        'upcomingAppointments' => $upcomingAppointments,
                        'services' => $services,
                        'allAppointments' => $allAppointments,
                    ]);
                }
            }

        }
    }
}
