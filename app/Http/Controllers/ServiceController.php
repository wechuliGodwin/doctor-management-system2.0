<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\BookingMail;
use App\Models\Upload;
use App\Notifications\AppointmentCreatedNotification;

class ServiceController extends Controller
{
    public function showReports()
    {
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)
            ->whereNotNull('path')
            ->get();
        return view('reports.index', compact('appointments'));
    }

	public function showCareArea($care)
    {
        // Normalize the care area name for display (e.g., "Gen Surg OPD" -> "General Surgery OPD")
        $careName = str_replace(['-', '_'], ' ', ucwords(strtolower($care)));
        return view('services.care-area', compact('careName'));
    }

    public function showUploads()
    {
        $uploads = Upload::where('user_id', Auth::id())->get();
        return view('uploads.index', compact('uploads'));
    }

	public function showResearchDayImages()
{
    return view('education.research-day-images');
}

    public function downloadReport($filename)
    {
        $locations = [
            storage_path('app/uploads/' . $filename),
            public_path($filename),
        ];
        foreach ($locations as $path) {
            if (file_exists($path)) {
                return response()->file($path);
            }
        }
        abort(404, 'File not found in specified locations.');
    }

    public function showBookingForm($serviceId)
    {
        $service = Service::find($serviceId);
        if (!$service) {
            return redirect()->back()->with('error', 'Service not found.');
        }
        return view('services.booking_form', ['service' => $service]);
    }

    public function book(Request $request, $serviceId)
    {
        Log::info('Book method called for service ID: ' . $serviceId);

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'platform' => 'required|string|max:50',
            'mpesa_code' => 'nullable|string|max:10',
        ]);

        $user = Auth::user();
        $service = Service::find($serviceId);
        if (!$service) {
            return redirect()->back()->with('error', 'Service not found.');
        }

        $appointment = new Appointment();
        $appointment->patient_id = $user->id;
        $appointment->service_id = $service->id;
        $appointment->patient_name = $user->name;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->platform = $request->platform;
        $appointment->user_id = $user->id;

        if ($request->filled('mpesa_code')) {
            $appointment->mpesa_code = $request->mpesa_code;
            $appointment->payment_status = 'paid';
        } else {
            $appointment->payment_status = 'pending';
        }
        $appointment->status = 'confirmed';

        try {
            $appointment->save();
            Log::info('Appointment saved successfully.');

            $superAdmin = User::where('role', 'superadmin')->first();
            if ($superAdmin) {
                $superAdmin->notify(new AppointmentCreatedNotification([
                    'service_name' => $service->name,
                    'appointment_date' => $appointment->appointment_date,
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Failed to save appointment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save appointment. Please try again.');
        }

        try {
            $emailData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'appointment_date' => $appointment->appointment_date,
                'service' => $service->name,
                'notes' => '',
                'message' => '',
            ];
            Mail::to($user->email)
                ->cc('telemedicine@kijabehospital.org')
                ->send(new BookingMail($emailData));
            Log::info('Booking confirmation email sent.');
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Appointment booked but email sending failed.');
        }

        return redirect()->route('dashboard')->with('success', 'Appointment booked successfully!');
    }

    // Method for /telemedicine-patient tracking
    public function showTelemedicinePatient()
    {
        // Track the page view for /telemedicine-patient
        $today = now()->toDateString();
        DB::statement(
            'INSERT INTO page_views (url, view_date, view_count) 
             VALUES (?, ?, 1) 
             ON DUPLICATE KEY UPDATE view_count = view_count + 1',
            ['telemedicine-patient', $today]
        );

        return view('services.telemedicine-patient');
    }
}