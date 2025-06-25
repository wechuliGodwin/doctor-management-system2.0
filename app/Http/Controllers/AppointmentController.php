<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\TimeSlot;
use App\Models\MyAppointment;
use App\Models\Schedule;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentMeetingLink;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    public function index()
    {
        $appointments = Appointment::with('patient')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.appointments', compact('appointments'));
    }

    public function update(Request $request)
    {
        try {
            Log::info('Update request data:', $request->all());

            $request->validate([
                'appointment_id' => 'required|exists:appointments,id',
                'hmis_number' => 'required|string|max:255',
                'patient_name' => 'required|string|max:255',
                'appointment_date' => 'required|date_format:Y-m-d\TH:i',
                'payment_status' => 'required|string|in:Completed,Pending,Cancelled',
                'notes' => 'nullable|string',
            ]);

            $appointment = Appointment::findOrFail($request->appointment_id);
            Log::info('Found appointment:', ['id' => $appointment->id]);

            DB::transaction(function () use ($request, $appointment) {
                $appointment->hmis_patient_number = $request->hmis_number;
                $appointment->patient_name = $request->patient_name;
                $appointment->appointment_date = $request->appointment_date;
                $appointment->payment_status = $request->payment_status;
                $appointment->notes = $request->notes;

                if ($request->payment_status === 'Completed' && !$appointment->meeting_id) {
                    $meetingData = [
                        'topic' => "Telemedicine Consult for {$appointment->patient_name}",
                        'type' => 2, // Scheduled meeting (one-time)
                        'start_time' => $appointment->appointment_date,
                        'duration' => 60,
                        'timezone' => 'UTC',
                        'agenda' => "Appointment ID: {$appointment->id}",
                        'settings' => [
                            'host_video' => true,
                            'participant_video' => true,
                            'join_before_host' => false,
                            'mute_upon_entry' => true,
                            'approval_type' => 2,
                            'auto_recording' => 'none',
                            'allow_multiple_devices' => false,
                        ],
                    ];
                    Log::info('Creating Zoom meeting:', $meetingData);

                    $meeting = $this->zoomService->createMeeting($meetingData);
                    if (is_array($meeting) && isset($meeting['start_url']) && isset($meeting['join_url'])) {
                        $appointment->host_start_url = $meeting['start_url']; // Host start link
                        $appointment->meeting_id = $meeting['join_url']; // Patient join link
                        Log::info('Zoom meeting created:', [
                            'start_url' => $meeting['start_url'],
                            'join_url' => $meeting['join_url']
                        ]);

                        // Send email to the patient only
                        if ($appointment->patient && $appointment->patient->email) {
                            Mail::to($appointment->patient->email)
                                ->send(new AppointmentMeetingLink($appointment, $appointment->meeting_id));
                            Log::info('Meeting link email sent to patient:', ['email' => $appointment->patient->email]);
                        } else {
                            Log::warning('No email found for patient:', [
                                'appointment_id' => $appointment->id,
                                'join_url' => $meeting['join_url']
                            ]);
                        }
                    } else {
                        throw new \Exception('Invalid Zoom response: start_url or join_url missing');
                    }
                }

                $appointment->save();
                Log::info('Appointment saved:', $appointment->toArray());
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment updated successfully.',
                    'start_url' => $appointment->host_start_url,
                    'meeting_id' => $appointment->meeting_id,
                ]);
            }

            return redirect()->back()->with('success', 'Appointment updated successfully.' . 
                ($appointment->host_start_url ? ' Host Start Link: ' . $appointment->host_start_url : '') .
                ($appointment->meeting_id ? ' Patient Join Link: ' . $appointment->meeting_id : ''));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', ['errors' => $e->errors()]);
            $errorMessages = collect($e->errors())->flatten()->all();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $errorMessages),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment: ' . $e->getMessage(),
            ], 500);
        }
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

        return redirect()->back()->with('success', 'Schedules saved successfully!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hmis_patient_number' => 'required|string|max:255',
            'doctor_id' => 'required|string|max:255',
            'time_slot' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        MyAppointment::create($validated);

        return response()->json(['success' => true]);
    }
}
