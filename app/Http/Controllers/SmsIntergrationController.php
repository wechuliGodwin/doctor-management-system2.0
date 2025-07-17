<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\BkAppointments;
use App\Models\BkNotifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmsIntergrationController extends Controller
{
    public function showBulk(Request $request)
    {
        // Handle both GET and POST by checking the request method
        $selectedIds = $request->isMethod('post')
            ? json_decode($request->input('selected_ids', '[]'), true)
            : $request->query('selected_ids', []);

        // Validate selected_ids
        if (!is_array($selectedIds)) {
            Log::warning('Invalid selected_ids format', ['selected_ids' => $request->input('selected_ids')]);
            $selectedIds = [];
        }

        $appointments = [];

        if (!empty($selectedIds)) {
            $appointments = BkAppointments::whereIn('id', $selectedIds)
                ->where('hospital_branch', Auth::guard('booking')->user()->hospital_branch)
                ->with('specialization')
                ->get()
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'full_name' => $appointment->full_name,
                        'phone' => $appointment->phone,
                        'specialization' => $appointment->specialization->name ?? '-',
                        'appointment_date' => Carbon::parse($appointment->appointment_date)->format('Y-m-d'),
                        'appointment_time' => $appointment->appointment_time,
                        'appointment_number' => $appointment->appointment_number
                    ];
                })->toArray();
        }

        return view('booking.sms', [
            'selectedAppointments' => $appointments
        ]);
    }

    public function searchPatients(Request $request)
    {
        $query = $request->input('search', '');

        if (strlen($query) < 3) {
            return response()->json(['data' => []]);
        }

        $appointments = BkAppointments::query()
            ->where('hospital_branch', Auth::guard('booking')->user()->hospital_branch)
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', '%' . $query . '%')
                    ->orWhere('patient_number', 'like', '%' . $query . '%')
                    ->orWhere('appointment_number', 'like', '%' . $query . '%');
            })
            ->with('specialization')
            ->take(50) // Limit results to prevent overload
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'full_name' => $appointment->full_name,
                    'phone' => $appointment->phone,
                    'specialization' => $appointment->specialization->name ?? '-',
                    'appointment_date' => Carbon::parse($appointment->appointment_date)->format('Y-m-d'),
                    'appointment_time' => $appointment->appointment_time,
                    'appointment_number' => $appointment->appointment_number,
                    'patient_number' => $appointment->patient_number
                ];
            });

        return response()->json(['data' => $appointments]);
    }

    public function sendBulkSMS(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array|min:1',
            'message' => 'required|string|max:160'
        ]);

        $client = new Client();
        $results = [];
        $successCount = 0;
        $failedCount = 0;

        foreach ($request->recipients as $recipient) {
            try {
                // Replace placeholders in the message
                $personalizedMessage = $request->message;
                $personalizedMessage = str_replace(
                    ['{name}', '{specialization}', '{date}', '{time}'],
                    [$recipient['full_name'], $recipient['specialization'], $recipient['appointment_date'], $recipient['appointment_time']],
                    $personalizedMessage
                );

                $response = $client->post('https://sms.digitalleo.co.ke/sms/v3/sendsms', [
                    'verify' => false,
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'api_key' => env('DIGITAL_LEO_API_KEY'),
                        'service_id' => env('DIGITAL_LEO_SERVICE_ID'),
                        'mobile' => $recipient['phone'],
                        'response_type' => 'json',
                        'shortcode' => env('DIGITAL_LEO_SENDER_ID'),
                        'message' => $personalizedMessage
                    ]
                ]);

                $result = json_decode($response->getBody(), true)[0];

                // Save to bk_notifications with correct column names
                BkNotifications::create([
                    'appointment_id' => $recipient['id'],
                    'notification_date' => now()->toDateString(),
                    'appointment_time' => now()->toTimeString(),
                    'hospital_branch' => Auth::guard('booking')->user()->hospital_branch ?? 'kijabe',
                    'status' => $result['status_code'] == '1000' ? 'sent' : 'failed',
                    'message' => $personalizedMessage,
                    'status_code' => $result['status_code'],
                    'status_desc' => $result['status_desc']
                ]);

                $results[] = [
                    'appointment_id' => $recipient['id'],
                    'appointment_number' => $recipient['appointment_number'],
                    'specialization' => $recipient['specialization'],
                    'appointment_date' => $recipient['appointment_date'],
                    'message' => $personalizedMessage,
                    'status_code' => $result['status_code'],
                    'status_desc' => $result['status_desc'],
                    'sent_at' => now()->toDateTimeString()
                ];

                if ($result['status_code'] == '1000') {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                Log::error('SMS sending failed for recipient ' . $recipient['id'] . ': ' . $e->getMessage());
                $failedCount++;

                BkNotifications::create([
                    'appointment_id' => $recipient['id'],
                    'notification_date' => now()->toDateString(),
                    'appointment_time' => now()->toTimeString(),
                    'hospital_branch' => Auth::guard('booking')->user()->hospital_branch ?? 'kijabe',
                    'status' => 'failed',
                    'message' => $personalizedMessage,
                    'status_code' => '1003',
                    'status_desc' => $e->getMessage()
                ]);

                $results[] = [
                    'appointment_id' => $recipient['id'],
                    'appointment_number' => $recipient['appointment_number'],
                    'specialization' => $recipient['specialization'],
                    'appointment_date' => $recipient['appointment_date'],
                    'message' => $personalizedMessage,
                    'status_code' => '1003',
                    'status_desc' => $e->getMessage(),
                    'sent_at' => now()->toDateTimeString()
                ];
            }
        }

        Log::info('SMS send results', ['success_count' => $successCount, 'failed_count' => $failedCount, 'results' => $results]);

        return response()->json([
            'success' => $successCount > 0,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'results' => $results
        ]);
    }

    public function getDeliveryLog(Request $request)
    {
        $logs = BkNotifications::where('hospital_branch', Auth::guard('booking')->user()->hospital_branch)
            ->join('bk_appointments', 'bk_notifications.appointment_id', '=', 'bk_appointments.id')
            ->select(
                'bk_notifications.*',
                'bk_appointments.appointment_number',
                'bk_appointments.specialization',
                'bk_appointments.appointment_date'
            )
            ->with(['appointment.specialization'])
            ->orderBy('bk_notifications.created_at', 'desc')
            ->take(50)
            ->get()
            ->map(function ($log) {
                return [
                    'appointment_id' => $log->appointment_id,
                    'appointment_number' => $log->appointment->appointment_number,
                    'specialization' => $log->appointment->specialization->name ?? '-',
                    'appointment_date' => Carbon::parse($log->appointment->appointment_date)->format('Y-m-d'),
                    'message' => $log->message,
                    'status' => $log->status,
                    'status code' => $log->{'status code'},
                    'status description' => $log->{'status description'},
                    'sent_at' => Carbon::parse($log->created_at)->toDateTimeString()
                ];
            });

        return response()->json(['logs' => $logs]);
    }

    public function getTemplates(Request $request)
    {
        $templates = BkNotifications::where('hospital_branch', Auth::guard('booking')->user()->hospital_branch)
            ->get()
            ->map(function ($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->name,
                    'type' => $template->type,
                    'content' => $template->content
                ];
            });

        return response()->json(['templates' => $templates]);
    }

    public function saveTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:default,urgent,followup,custom',
            'content' => 'required|string|max:160',
            'is_default' => 'boolean'
        ]);

        $template = BkNotifications::updateOrCreate(
            ['name' => $request->name, 'hospital_branch' => Auth::guard('booking')->user()->hospital_branch],
            [
                'type' => $request->type,
                'content' => $request->content,
                'is_default' => $request->is_default ?? false
            ]
        );

        return response()->json(['success' => true, 'template' => $template]);
    }
}
