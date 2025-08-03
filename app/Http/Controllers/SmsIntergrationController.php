<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\BkAppointments;
use App\Models\BkNotifications;
use App\Models\BkNotificationTemplates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmsIntergrationController extends Controller
{
    public function showDeliveryLog(Request $request)
    {
        return view('booking.delivery_log');
    }
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

        return view('booking.reminders', [
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
    private function isValidPhoneNumber($phone)
    {
        // Remove whitespace and non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', trim($phone));        
        // Check for Kenyan mobile number format: 07xxxxxxxx or +254xxxxxxxx
        return (preg_match('/^07[0-9]{8}$/', $phone) || preg_match('/^\+254[0-9]{9}$/', $phone));
    }
    public function sendBulkSMS(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array|min:1',
            'message' => 'required|string|max:1000'
        ]);

        $client = new Client();
        $results = [];
        $successCount = 0;
        $failedCount = 0;

        $statusCodeMap = [
            '0' => ['status' => 'failed', 'description' => 'Unknown error'],
            '1' => ['status' => 'sent', 'description' => 'Success'],
            '1000' => ['status' => 'sent', 'description' => 'Success'],
            '1001' => ['status' => 'inv_sender', 'description' => 'Invalid sender name'],
            '1002' => ['status' => 'failed', 'description' => 'Network not allowed'],
            '1003' => ['status' => 'inv_mobile', 'description' => 'Invalid mobile number'],
            '1004' => ['status' => 'failed', 'description' => 'Low bulk credits'],
            '1005' => ['status' => 'failed', 'description' => 'Failed. System error'],
            '1006' => ['status' => 'failed', 'description' => 'Invalid credentials'],
            '1007' => ['status' => 'failed', 'description' => 'Database connection failed'],
            '1008' => ['status' => 'failed', 'description' => 'Database selection failed'],
            '1009' => ['status' => 'failed', 'description' => 'No dlr or unsupported data type'],
            '1010' => ['status' => 'failed', 'description' => 'Unsupported request type'],
            '1011' => ['status' => 'failed', 'description' => 'Invalid user state']
        ];

        foreach ($request->recipients as $recipient) {
            $personalizedMessage = str_replace(
                ['{name}', '{specialization}', '{date}', '{time}'],
                [$recipient['full_name'], $recipient['specialization'], $recipient['appointment_date'], $recipient['appointment_time']],
                $request->message
            );

            // Split phone numbers by '|'
            $phoneNumbers = array_map('trim', explode('|', $recipient['phone']));
            $attemptedPhone = null;
            $statusCode = '1003';
            $statusDesc = 'Invalid mobile number';
            $statusInfo = $statusCodeMap['1003'];
            $messageId = null;
            $success = false;

            // Try each phone number until one succeeds or all fail
            foreach ($phoneNumbers as $phone) {
                if (empty($phone)) {
                    continue; // Skip empty numbers
                }

                if (!$this->isValidPhoneNumber($phone)) {
                    continue; // Skip invalid numbers
                }

                try {
                    $response = $client->post('https://sms.digitalleo.co.ke/sms/v3/sendsms', [
                        'verify' => false,
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'api_key' => env('DIGITAL_LEO_API_KEY'),
                            'service_id' => env('DIGITAL_LEO_SERVICE_ID'),
                            'mobile' => $phone,
                            'response_type' => 'json',
                            'shortcode' => env('DIGITAL_LEO_SENDER_ID'),
                            'message' => $personalizedMessage
                        ]
                    ]);

                    $result = json_decode($response->getBody(), true)[0];
                    $statusCode = (string) $result['status_code'];
                    $statusInfo = $statusCodeMap[$statusCode] ?? ['status' => 'failed', 'description' => 'Unknown status code'];
                    $messageId = $result['messageId'] ?? null;
                    $statusDesc = $result['status_desc'] ?? $statusInfo['description'];
                    $attemptedPhone = $phone;

                    if ($statusInfo['status'] === 'sent') {
                        $success = true;
                        $successCount++;
                        break; // Stop on success
                    } elseif ($statusCode === '1003') {
                        continue; // Try next number for invalid mobile
                    } else {
                        $failedCount++;
                        break; // Stop on other errors (e.g., 1001, 1004)
                    }
                } catch (\Exception $e) {
                    Log::error('SMS sending failed for recipient ' . $recipient['id'] . ', phone ' . $phone . ': ' . $e->getMessage());
                    $errorMessage = $e->getMessage();

                    if (stripos($errorMessage, 'invalid mobile') !== false) {
                        $statusCode = '1003';
                        $statusDesc = 'Invalid mobile number';
                        continue; // Try next number
                    } elseif (stripos($errorMessage, 'authentication') !== false || stripos($errorMessage, 'credential') !== false) {
                        $statusCode = '1006';
                        $statusDesc = 'Invalid credentials';
                    } elseif (stripos($errorMessage, 'database') !== false) {
                        $statusCode = '1007';
                        $statusDesc = 'Database connection failed';
                    } else {
                        $statusCode = '1005';
                        $statusDesc = 'Failed. System error';
                    }

                    $statusInfo = $statusCodeMap[$statusCode] ?? ['status' => 'failed', 'description' => $statusDesc];
                    $attemptedPhone = $phone;
                    $failedCount++;
                    break; // Stop on non-1003 errors
                }
            }

            // If no valid phone number or all attempts failed
            if (!$attemptedPhone) {
                $statusCode = '1003';
                $statusDesc = 'No valid phone number provided';
                $statusInfo = $statusCodeMap['1003'];
                $failedCount++;
            } elseif (!$success) {
                $failedCount = $successCount > 0 ? $failedCount : $failedCount + 1; // Adjust count if no success
            }

            $notification = BkNotifications::create([
                'appointment_id' => $recipient['id'],
                'message_id' => $messageId,
                'notification_date' => now()->toDateString(),
                'appointment_time' => now()->toTimeString(),
                'hospital_branch' => Auth::guard('booking')->user()->hospital_branch ?? 'kijabe',
                'status' => $statusInfo['status'],
                'message' => $personalizedMessage,
                'phone_used' => $attemptedPhone,
                'status_code' => $statusCode,
                'status_desc' => $statusDesc
            ]);

            $results[] = [
                'appointment_id' => $recipient['id'],
                'appointment_number' => $recipient['appointment_number'],
                'specialization' => $recipient['specialization'],
                'appointment_date' => $recipient['appointment_date'],
                'message' => $personalizedMessage,
                'phone_used' => $attemptedPhone,
                'message_id' => $messageId,
                'status_code' => $statusCode,
                'status_desc' => $statusDesc,
                'sent_at' => now()->toDateTimeString()
            ];
        }

        Log::info('SMS send results', [
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'results' => $results
        ]);

        return response()->json([
            'success' => $successCount > 0,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'results' => $results
        ]);
    }

    public function getDeliveryLog(Request $request)
    {
        try {
            $logs = BkNotifications::where('bk_notifications.hospital_branch', Auth::guard('booking')->user()->hospital_branch)
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
                ->get();

            $client = new Client();
            $successCodes = ['1', '1000'];

            foreach ($logs as $log) {
                if (in_array($log->status_code, $successCodes) && is_null($log->delivery_status) && $log->message_id) {
                    try {
                        $response = $client->get('https://api.tililtech.com/sms/v3/getdlr', [
                            'verify' => false,
                            'query' => [
                                'api_key' => env('DIGITAL_LEO_API_KEY'),
                                'messageId' => $log->message_id
                            ]
                        ]);

                        $dlrData = explode(';', trim($response->getBody()->getContents()));

                        if (count($dlrData) >= 5 && $dlrData[0] !== '1009') {
                            $log->update([
                                'delivery_status' => $dlrData[2],
                                'delivery_desc' => $dlrData[3],
                                'delivery_time' => $dlrData[4] ? Carbon::parse($dlrData[4]) : null,
                                'tat' => $dlrData[5] ?? null
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to fetch delivery report for message_id ' . $log->message_id . ': ' . $e->getMessage());
                    }
                }
            }

            $statusCodeMap = [
                '0' => ['status' => 'failed', 'description' => 'Unknown error'],
                '1' => ['status' => 'sent', 'description' => 'Success'],
                '1000' => ['status' => 'sent', 'description' => 'Success'],
                '1001' => ['status' => 'inv_sender', 'description' => 'Invalid sender name'],
                '1002' => ['status' => 'failed', 'description' => 'Network not allowed'],
                '1003' => ['status' => 'inv_mobile', 'description' => 'Invalid mobile number'],
                '1004' => ['status' => 'failed', 'description' => 'Low bulk credits'],
                '1005' => ['status' => 'failed', 'description' => 'Failed. System error'],
                '1006' => ['status' => 'failed', 'description' => 'Invalid credentials'],
                '1007' => ['status' => 'failed', 'description' => 'Database connection failed'],
                '1008' => ['status' => 'failed', 'description' => 'Database selection failed'],
                '1009' => ['status' => 'failed', 'description' => 'No dlr or unsupported data type'],
                '1010' => ['status' => 'failed', 'description' => 'Unsupported request type'],
                '1011' => ['status' => 'failed', 'description' => 'Invalid user state']
            ];

            $mappedLogs = $logs->map(function ($log) use ($statusCodeMap) {
                $statusCode = (string) $log->status_code;
                $statusInfo = $statusCodeMap[$statusCode] ?? ['status' => 'failed', 'description' => 'Unknown status code'];

                return [
                    'appointment_id' => $log->appointment_id,
                    'appointment_number' => $log->appointment->appointment_number,
                    'specialization_name' => $log->appointment->specialization->name ?? '-',
                    'appointment_date' => Carbon::parse($log->appointment->appointment_date)->format('Y-m-d'),
                    'message' => $log->message,
                    'phone_used' => $log->phone_used ?? '-',
                    'message_id' => $log->message_id,
                    'status' => $log->status ?? $statusInfo['status'],
                    'status_code' => $log->status_code,
                    'status_desc' => $log->status_desc ?? $statusInfo['description'],
                    'delivery_status' => $log->delivery_status ?? '-',
                    'delivery_desc' => $log->delivery_desc ?? '-',
                    'delivery_time' => $log->delivery_time ? Carbon::parse($log->delivery_time)->toDateTimeString() : '-',
                    'tat' => $log->tat ?? '-',
                    'sent_at' => Carbon::parse($log->created_at)->toDateTimeString()
                ];
            });

            return response()->json(['logs' => $mappedLogs]);
        } catch (\Exception $e) {
            Log::error('Error fetching delivery log: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch delivery log'], 500);
        }
    }
    public function getTemplates(Request $request)
    {
        try {
            $templates = BkNotificationTemplates::where('hospital_branch', Auth::guard('booking')->user()->hospital_branch)
                ->get()
                ->map(function ($template) {
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'type' => $template->type,
                        'content' => $template->getAttribute('content') // Use getAttribute to access protected properties
                    ];
                });

            return response()->json(['templates' => $templates]);
        } catch (\Exception $e) {
            Log::error('Error fetching templates: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch templates'], 500);
        }
    }

    public function saveTemplate(Request $request)
    {
        if (Auth::guard('booking')->user()->role !== 'superadmin') {
            return response()->json(['error' => 'Unauthorized to save templates'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:default,urgent,followup',
            'content' => 'required|string|max:3000',
            'id' => 'nullable|exists:bk_notification_templates,id'
        ]);

        $data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'content' => $request->input('content'),
            'hospital_branch' => Auth::guard('booking')->user()->hospital_branch,
            'updated_by' => Auth::guard('booking')->user()->id
        ];

        if (!$request->input('id')) {
            $data['created_by'] = Auth::guard('booking')->user()->id;
        }

        try {
            $template = BkNotificationTemplates::updateOrCreate(
                [
                    'id' => $request->input('id'),
                    'hospital_branch' => Auth::guard('booking')->user()->hospital_branch
                ],
                $data
            );

            return response()->json(['success' => true, 'template' => [
                'id' => $template->id,
                'name' => $template->name,
                'type' => $template->type,
                'content' => $template->getAttribute('content')
            ]]);
        } catch (\Exception $e) {
            Log::error('Error saving template: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save template'], 500);
        }
    }

    public function deleteTemplate(Request $request)
    {
        if (Auth::guard('booking')->user()->role !== 'superadmin') {
            return response()->json(['error' => 'Unauthorized to delete templates'], 403);
        }

        $request->validate([
            'id' => 'required|exists:bk_notification_templates,id'
        ]);

        try {
            $template = BkNotificationTemplates::where('id', $request->input('id'))
                ->where('hospital_branch', Auth::guard('booking')->user()->hospital_branch)
                ->first();

            if (!$template) {
                return response()->json(['error' => 'Template not found'], 404);
            }

            $template->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting template: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete template'], 500);
        }
    }
}
