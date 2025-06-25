<?php

namespace App\Http\Controllers;

use App\Models\BkAppointments;
use App\Models\BkMessaging;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    /**
     * Get user's hospital branch from authenticated user
     */
    private function getUserBranch()
    {
        return Auth::guard('booking')->user()->hospital_branch;
    }

    /**
     * Base query for alerts filtered by user's branch
     */
    private function alertsBaseQuery()
    {
        return BkMessaging::with('appointment:id,full_name,patient_number,phone,hospital_branch')
            ->where('hospital_branch', $this->getUserBranch());
    }

    public function getActiveAlerts(Request $request)
    {
        $userBranch = $this->getUserBranch();

        $alerts = $this->alertsBaseQuery()
            ->where('active', 1)
            ->orderBy('messaging_date', 'desc')
            ->get();

        $alertsData = $alerts->map(function ($msg) {
            $status = $msg->is_new_patient ? 'new_patient' : (!empty($msg->feedback) ? 'reopened' : 'new');
            return [
                'id' => $msg->id,
                'full_name' => $msg->is_new_patient ? $msg->patient_name : ($msg->appointment->full_name ?? '-'),
                'patient_number' => $msg->is_new_patient ? ($msg->patient_number ?? '-') : ($msg->appointment->patient_number ?? '-'),
                'phone' => $msg->is_new_patient ? ($msg->phone ?? '-') : ($msg->appointment->phone ?? '-'),
                'messaging_date' => $msg->messaging_date,
                'urgent_message' => $msg->urgent_message,
                'sender_name' => $msg->sender_name,
                'sender_department' => $msg->sender_department,
                'hospital_branch' => $msg->hospital_branch ?? 'kijabe',
                'active' => $msg->active,
                'feedback' => $msg->feedback,
                'recipient' => $msg->recipient,
                'feedback_date' => $msg->feedback_date,
                'status' => $status,
            ];
        });

        // Cache patients for better performance
        $patients = $this->getPatientsList($userBranch);

        return view('booking.alerts', [
            'title' => 'Active Alerts',
            'status' => 'alerts',
            'alerts' => $alertsData,
            'patients' => $patients,
            'current_branch' => $userBranch,
        ]);
    }
    public function getResolvedAlerts(Request $request)
    {
        $userBranch = $this->getUserBranch();

        $resolvedAlerts = $this->alertsBaseQuery()
            ->where('active', 0)
            ->orderBy('messaging_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $alertsData = $resolvedAlerts->map(function ($msg) {
            return [
                'id' => $msg->id,
                'full_name' => $msg->appointment->full_name ?? '-',
                'patient_number' => $msg->appointment->patient_number ?? '-',
                'phone' => $msg->appointment->phone ?? '-',
                'messaging_date' => $msg->messaging_date ?? '-',
                'urgent_message' => $msg->urgent_message,
                'sender_name' => $msg->sender_name,
                'sender_department' => $msg->sender_department,
                'active' => $msg->active,
                'feedback' => $msg->feedback ?? '-',
                'recipient' => $msg->recipient ?? '-',
                'feedback_date' => $msg->feedback_date ?? '-',
                'hospital_branch' => $msg->hospital_branch,
                'status' => $msg->is_new_patient ? 'new_patient' : 'resolved',
            ];
        });

        Log::info('Resolved alerts fetched', ['count' => $alertsData->count(), 'branch' => $userBranch]);

        return view('booking.alerts', [
            'title' => 'Resolved Alerts',
            'status' => 'resolved_alerts',
            'alerts' => $alertsData,
            'current_branch' => $userBranch,
        ]);
    }

    public function getAlertDetails($id)
    {
        $userBranch = $this->getUserBranch();
        Log::info('Fetching alert details', ['id' => $id, 'user_branch' => $userBranch]);

        try {
            $alert = $this->alertsBaseQuery()
                ->where('id', $id)
                ->firstOrFail();

            return response()->json([
                'id' => $alert->id,
                'feedback' => $alert->feedback,
                'recipient' => $alert->recipient,
                'feedback_date' => $alert->feedback_date,
                'active' => $alert->active,
                'hospital_branch' => $alert->hospital_branch,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Alert not found or not accessible', ['id' => $id, 'user_branch' => $userBranch]);
            return response()->json(['error' => 'Alert not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching alert details', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch alert details'], 500);
        }
    }

    public function resolve(Request $request, $id)
    {
        $userBranch = $this->getUserBranch();
        Log::info('Resolving alert with feedback', ['id' => $id, 'user_branch' => $userBranch]);

        try {
            $alert = $this->alertsBaseQuery()
                ->where('id', $id)
                ->where('active', 1)
                ->firstOrFail();

            $validated = $request->validate([
                'feedback' => 'required|string|max:1000',
                'recipient' => 'nullable|string|max:255',
            ]);

            $alert->update([
                'active' => 0,
                'feedback' => $validated['feedback'],
                'recipient' => $validated['recipient'],
                'feedback_date' => now(),
            ]);

            Log::info('Alert resolved with feedback', [
                'id' => $id,
                'feedback' => $validated['feedback'],
                'branch' => $userBranch
            ]);

            return response()->json(['success' => 'Alert resolved successfully with feedback']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for resolve', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Alert not found or not accessible', ['id' => $id, 'user_branch' => $userBranch]);
            return response()->json(['error' => 'Alert not found or already resolved'], 404);
        } catch (\Exception $e) {
            Log::error('Error resolving alert', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to resolve alert'], 500);
        }
    }

    public function reopen(Request $request, $id)
    {
        $userBranch = $this->getUserBranch();
        Log::info('Reopening alert', ['id' => $id, 'user_branch' => $userBranch]);

        try {
            $alert = $this->alertsBaseQuery()
                ->where('id', $id)
                ->where('active', 0)
                ->firstOrFail();

            $alert->update(['active' => 1]);

            Log::info('Alert reopened with feedback preserved', [
                'id' => $id,
                'preserved_feedback' => $alert->feedback ? 'Yes' : 'No',
                'branch' => $userBranch
            ]);

            return response()->json(['success' => 'Alert reopened successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Alert not found or not accessible', ['id' => $id, 'user_branch' => $userBranch]);
            return response()->json(['error' => 'Alert not found or already active'], 404);
        } catch (\Exception $e) {
            Log::error('Error reopening alert', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to reopen alert'], 500);
        }
    }

    public function store(Request $request)
    {
        $userBranch = $this->getUserBranch();
        Log::info('[INFO] ------- Creating new alert', array_merge($request->all(), ['user_branch' => $userBranch]));

        try {
            $validated = $request->validate([
                'appointment_id' => 'required|exists:bk_appointments,id',
                'urgent_message' => 'required|string|max:1000',
                'sender_name' => 'required|string|max:255',
                'sender_department' => 'required|string|max:64',
            ]);

            // Validate appointment belongs to user's branch
            $appointment = BkAppointments::where('id', $validated['appointment_id'])
                ->where('hospital_branch', $userBranch)
                ->first();

            if (!$appointment) {
                Log::error('Appointment not found in user branch', [
                    'appointment_id' => $validated['appointment_id'],
                    'user_branch' => $userBranch
                ]);

                return response()->json([
                    'error' => 'Selected patient does not belong to your branch. Please refresh and try again.'
                ], 403);
            }

            // Check for existing active alert
            $existingAlert = BkMessaging::where('appointment_id', $validated['appointment_id'])
                ->where('active', 1)
                ->where('hospital_branch', $userBranch)
                ->exists();

            if ($existingAlert) {
                Log::info('Active alert already exists for this appointment', [
                    'appointment_id' => $validated['appointment_id'],
                    'branch' => $userBranch
                ]);

                return response()->json([
                    'error' => 'An active alert for this patient already exists. Please resolve it first.'
                ], 409);
            }

            // Use database transaction for consistency
            DB::transaction(function () use ($validated, $userBranch, $appointment) {
                $alert = BkMessaging::create([
                    'appointment_id' => $validated['appointment_id'],
                    'is_new_patient' => 0,
                    'patient_name' => $appointment->full_name,
                    'patient_number' => $appointment->patient_number,
                    'phone' => $appointment->phone,
                    'urgent_message' => $validated['urgent_message'],
                    'sender_name' => $validated['sender_name'],
                    'sender_department' => $validated['sender_department'],
                    'hospital_branch' => $userBranch,
                    'messaging_date' => now()->toDateString(),
                    'active' => 1,
                ]);

                Log::info('Alert created successfully', [
                    'id' => $alert->id,
                    'branch' => $userBranch,
                    'appointment_id' => $validated['appointment_id']
                ]);
            });

            return response()->json(['success' => 'Alert created successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for store', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating alert', ['error' => $e->getMessage(), 'user_branch' => $userBranch]);
            return response()->json(['error' => 'Failed to create alert'], 500);
        }
    }

    public function bulkResolve(Request $request)
    {
        $userBranch = $this->getUserBranch();
        Log::info('Bulk resolving alerts', array_merge($request->all(), ['user_branch' => $userBranch]));

        try {
            $validated = $request->validate([
                'alert_ids' => 'required|array|min:1|max:50', // Limit bulk operations
                'alert_ids.*' => 'integer|exists:bk_messaging,id',
                'feedback' => 'required|string|max:1000',
                'recipient' => 'nullable|string|max:255',
            ]);

            $updatedCount = $this->alertsBaseQuery()
                ->whereIn('id', $validated['alert_ids'])
                ->where('active', 1)
                ->update([
                    'active' => 0,
                    'feedback' => $validated['feedback'],
                    'recipient' => $validated['recipient'],
                    'feedback_date' => now(),
                ]);

            Log::info('Bulk resolve completed', [
                'requested_count' => count($validated['alert_ids']),
                'updated_count' => $updatedCount,
                'branch' => $userBranch
            ]);

            return response()->json([
                'message' => "Successfully resolved {$updatedCount} alerts",
                'updated_count' => $updatedCount
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for bulk resolve', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in bulk resolve', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to resolve alerts'], 500);
        }
    }

    public function storeNewPatient(Request $request)
    {
        $userBranch = $this->getUserBranch();
        Log::info('[INFO] ------- Creating new alert for new patient', array_merge($request->all(), ['user_branch' => $userBranch]));

        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'patient_number' => 'nullable|string|max:32',
                'phone' => 'required|numeric|digits_between:10,15',
                'urgent_message' => 'required|string|max:1000',
                'sender_name' => 'required|string|max:255',
                'sender_department' => 'required|string|max:64',
            ]);

            // Check for existing active alert with the same patient number (if provided)
            if (!empty($validated['patient_number'])) {
                $existingAlert = BkMessaging::where('patient_number', $validated['patient_number'])
                    ->where('active', 1)
                    ->where('hospital_branch', $userBranch)
                    ->exists();

                if ($existingAlert) {
                    Log::info('Active alert already exists for this patient number', [
                        'patient_number' => $validated['patient_number'],
                        'branch' => $userBranch
                    ]);

                    return response()->json([
                        'error' => 'An active alert for this patient number already exists. Please resolve it first.'
                    ], 409);
                }
            }

            // Use database transaction for consistency
            DB::transaction(function () use ($validated, $userBranch) {
                $alert = BkMessaging::create([
                    'appointment_id' => null,
                    'is_new_patient' => 1,
                    'patient_name' => $validated['patient_name'],
                    'patient_number' => $validated['patient_number'] ?? null,
                    'phone' => $validated['phone'],
                    'urgent_message' => $validated['urgent_message'],
                    'sender_name' => $validated['sender_name'],
                    'sender_department' => $validated['sender_department'],
                    'hospital_branch' => $userBranch,
                    'messaging_date' => now()->toDateString(),
                    'active' => 1,
                ]);

                Log::info('Alert for new patient created successfully', [
                    'id' => $alert->id,
                    'branch' => $userBranch,
                    'patient_number' => $validated['patient_number'] ?? 'None'
                ]);
            });

            return response()->json(['success' => 'Alert for new patient created successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for storeNewPatient', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating alert for new patient', ['error' => $e->getMessage(), 'user_branch' => $userBranch]);
            return response()->json(['error' => 'Failed to create alert'], 500);
        }
    }

    public function bulkReopen(Request $request)
    {
        $userBranch = $this->getUserBranch();
        Log::info('Bulk reopening alerts', array_merge($request->all(), ['user_branch' => $userBranch]));

        try {
            $validated = $request->validate([
                'alert_ids' => 'required|array|min:1|max:50',
                'alert_ids.*' => 'integer|exists:bk_messaging,id'
            ]);

            $updatedCount = $this->alertsBaseQuery()
                ->whereIn('id', $validated['alert_ids'])
                ->where('active', 0)
                ->update(['active' => 1]);

            Log::info('Bulk reopen completed', [
                'requested_count' => count($validated['alert_ids']),
                'updated_count' => $updatedCount,
                'branch' => $userBranch
            ]);

            return response()->json([
                'message' => "Successfully reopened {$updatedCount} alerts",
                'updated_count' => $updatedCount
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for bulk reopen', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in bulk reopen', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to reopen alerts'], 500);
        }
    }

    /**
     * Get patients list for a specific branch
     */
    public function getPatients(Request $request)
    {
        $userBranch = $this->getUserBranch();
        Log::info('Fetching patients for branch via AJAX', ['branch' => $userBranch]);

        try {
            $patients = $this->getPatientsList($userBranch);

            return response()->json([
                'patients' => $patients,
                'branch' => $userBranch,
                'count' => $patients->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching patients', ['error' => $e->getMessage(), 'branch' => $userBranch]);
            return response()->json(['error' => 'Failed to fetch patients'], 500);
        }
    }

    /**
     * Helper method to get patients list
     */
    private function getPatientsList($userBranch)
    {
        return BkAppointments::select('id', 'full_name', 'patient_number', 'phone', 'hospital_branch')
            ->where('hospital_branch', $userBranch)
            ->orderBy('full_name')
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'text' => "{$patient->full_name} ({$patient->patient_number}) - {$patient->phone}",
                    'hospital_branch' => $patient->hospital_branch,
                ];
            });
    }
}
