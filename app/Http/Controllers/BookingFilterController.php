<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentReminder;
use App\Models\BkAppointments;
use App\Models\Holiday;
use App\Mail\ContactFormMail;
use App\Models\NewAppointment;
use App\Models\ReviewAppointment;
use App\Models\PostOpAppointment;
use App\Models\ExternalPendingApproval;
use App\Models\ExternalApproved;
use App\Models\CancelledAppointment;
// use App\Models\Specialization;
use App\Models\BkSpecializations;
use App\Models\BkDoctor;
use App\Models\BkSpecializationsGroup;
use App\Models\BkRescheduledAppointments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BookingFilterController extends Controller
{

    public function statusFilter(Request $request, $status = null)
    {

        $status = $status ? strtolower($status) : null;
        $validationRules = [
            'ajax' => 'nullable|boolean',
            'export_csv' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
        $user = Auth::guard('booking')->user();

        // Define user roles for clarity
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';

        $userBranch = $user ? $user->hospital_branch : null;
        $selectedBranch = null; // Default to no branch selected initially

        if ($isSuperadmin) {
            $validationRules['branch'] = 'nullable|in:kijabe,westlands,naivasha,marira';
            $request->validate($validationRules); // Validate here if branch is allowed
            $selectedBranch = $request->input('branch'); // Superadmin can explicitly select
        } else {
            // Admins (and other users if any) are restricted to their assigned branch.
            // Any 'branch' parameter in the request is ignored for them.
            $request->validate($validationRules); // Validate here if branch is NOT allowed in request
            $selectedBranch = $userBranch;
        }

        $hospitalBranches = $this->getHospitalBranchEnumValues();
        $data = [];
        $specializations = BkSpecializations::where('hospital_branch', $userBranch)->orderBy('name')->get();

        //get the filter date
        $startDate = $validated['start_date'] ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $validated['end_date'] ?? Carbon::now()->endOfMonth()->toDateString();


        if ($status) {
            // --- Logic for specific appointment statuses (new, review, postop, rescheduled, external_pending, external_approved, cancelled) ---
            switch ($status) {
                case 'new':
                case 'review':
                case 'postop':
                    $data['title'] = ucfirst($status) . ' Appointments';
                    $bookingType = str_replace('postop', 'post_op', $status);
                    $query = BkAppointments::query()
                        ->select([
                            'bk_appointments.id',
                            'bk_appointments.appointment_number',
                            'bk_appointments.full_name',
                            'bk_appointments.patient_number',
                            'bk_appointments.email',
                            'bk_appointments.phone',
                            'bk_appointments.appointment_date',
                            'bk_appointments.appointment_time',
                            'bk_specializations.name as specialization',
                            'bk_appointments.doctor_name as doctor',
                            'bk_appointments.booking_type',
                            'bk_appointments.appointment_status',
                            'bk_appointments.doctor_comments',
                            'bk_appointments.hospital_branch',
                            'bk_appointments.visit_date',
                            'bk_appointments.created_at',
                            DB::raw("'$status' as source_table"),
                        ])
                        ->join('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                        ->where('bk_appointments.booking_type', $bookingType)
                        ->where('bk_appointments.appointment_status', '!=', 'rescheduled')
                        ->whereBetween('appointment_date', [$startDate, $endDate]);

                    // Apply branch filter if it's not a superadmin OR if a specific branch is selected by superadmin
                    if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                        $query->where('bk_appointments.hospital_branch', $selectedBranch);
                    }
                    Log::info("Query built for {$status}. Has branch filter: " . ($selectedBranch ? 'Yes' : 'No'));
                    break;

                case 'rescheduled':
                    $data['title'] = 'Rescheduled Appointments';
                    $query = DB::table('bk_rescheduled_appointments as a')
                        ->join('bk_appointments as b', 'a.appointment_id', '=', 'b.id')
                        ->join('bk_appointments as c', 'a.re_appointment_id', '=', 'c.id')
                        ->join('bk_specializations as bs', 'b.specialization', '=', 'bs.id')
                        ->join('bk_specializations as cs', 'c.specialization', '=', 'cs.id')
                        ->select([
                            'b.id',
                            'b.appointment_number as previous_number',
                            'b.full_name',
                            'bs.name as previous_specialization',
                            'b.appointment_date as previous_date',
                            'b.appointment_time as previous_time',
                            DB::raw("'=>' as from_to"),
                            'c.appointment_number as current_number',
                            'cs.name as current_specialization',
                            'c.appointment_date as current_date',
                            'c.appointment_time as current_time',
                            'a.reason',
                            'b.hospital_branch as previous_branch',
                            'c.hospital_branch as current_branch',
                            DB::raw('"rescheduled" as source_table'),
                        ])
                        ->where('b.appointment_status', 'rescheduled')
                        ->whereBetween('b.appointment_date', [$startDate, $endDate]);

                    // Apply branch filter if it's not a superadmin OR if a specific branch is selected by superadmin
                    if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                        $query->where(function ($q) use ($selectedBranch) {
                            $q->where('b.hospital_branch', $selectedBranch)
                                ->orWhere('c.hospital_branch', $selectedBranch);
                        });
                    }
                    Log::info("Query built for rescheduled. Has branch filter: " . ($selectedBranch ? 'Yes' : 'No'));
                    break;

                case 'external_pending':
                    $data['title'] = 'External Pending Approval';
                    $query = ExternalPendingApproval::query()
                        ->select([
                            'id',
                            'appointment_number',
                            'full_name',
                            'patient_number',
                            'email',
                            'phone',
                            'appointment_date',
                            DB::raw('NULL as appointment_time'),
                            'specialization',
                            DB::raw('NULL as doctor'),
                            DB::raw('NULL as booking_type'),
                            'status as appointment_status',
                            'notes',
                            DB::raw('NULL as doctor_comments'),
                            DB::raw('NULL as hospital_branch'), // Explicitly set to NULL
                            DB::raw('NULL as cancellation_reason'),
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"external_pending" as source_table'),
                        ]);
                    Log::info("Query built for external_pending. No branch filter applied (hospital_branch column not present).");
                    break;

                case 'external_approved':
                    $data['title'] = 'External Approved Appointments';
                    $query = ExternalApproved::query()
                        ->select([
                            'id',
                            'booking_id as appointment_number',
                            'full_name',
                            'patient_number',
                            'email',
                            'phone',
                            'appointment_date',
                            'appointment_time',
                            'specialization',
                            'doctor_name as doctor',
                            'hospital_branch',
                            DB::raw('NULL as booking_type'),
                            'appointment_status',
                            'notes',
                            'doctor_comments',
                            DB::raw('NULL as cancellation_reason'),
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"external_approved" as source_table'),
                        ])->whereBetween('appointment_date', [$startDate, $endDate]);
                    // Apply branch filter if it's not a superadmin OR if a specific branch is selected by superadmin
                    if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                        $query->where('hospital_branch', $selectedBranch);
                    }
                    Log::info("Query built for external_approved. Has branch filter: " . ($selectedBranch ? 'Yes' : 'No'));
                    break;

                case 'cancelled':
                    $data['title'] = 'Cancelled Appointments';
                    $query = CancelledAppointment::query()
                        ->select([
                            'id',
                            'appointment_number',
                            'full_name',
                            'patient_number',
                            'email',
                            'phone',
                            'appointment_date',
                            'appointment_time',
                            'specialization',
                            'doctor_name as doctor',
                            'booking_type',
                            DB::raw('"cancelled" as appointment_status'),
                            'notes',
                            'cancellation_reason',
                            DB::raw('NULL as doctor_comments'),
                            'hospital_branch', // Assuming this column exists for filtering
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"cancelled" as source_table'),
                        ])->whereBetween('appointment_date', [$startDate, $endDate]);
                    // Apply branch filter if it's not a superadmin OR if a specific branch is selected by superadmin
                    // Also assume CancelledAppointment has 'hospital_branch' column
                    if ((!$isSuperadmin || ($isSuperadmin && $selectedBranch)) && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                        $query->where('hospital_branch', $selectedBranch);
                    }
                    Log::info("Query built for cancelled. Has branch filter: " . ($selectedBranch ? 'Yes' : 'No'));
                    break;

                case 'all':
                    $data['title'] = 'All Appointments';

                    $internalQuery = BkAppointments::query()
                        ->select([
                            'bk_appointments.id',
                            'bk_appointments.appointment_number',
                            'bk_appointments.full_name',
                            'bk_appointments.patient_number',
                            'bk_appointments.email',
                            'bk_appointments.phone',
                            'bk_appointments.appointment_date',
                            'bk_appointments.appointment_time',
                            'bk_specializations.name as specialization',
                            'bk_appointments.doctor_name as doctor',
                            'bk_appointments.booking_type',
                            'bk_appointments.appointment_status',
                            DB::raw('NULL as notes'),
                            'bk_appointments.doctor_comments',
                            'bk_appointments.hospital_branch',
                            DB::raw('NULL as cancellation_reason'),
                            DB::raw('NULL as visit_date'),
                            'bk_appointments.created_at',
                            DB::raw('"internal" as source_table'),
                        ])
                        ->join('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                        ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate]);

                    if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                        $internalQuery->where('bk_appointments.hospital_branch', $selectedBranch);
                    }

                    $pendingQuery = ExternalPendingApproval::query()
                        ->select([
                            'id',
                            'appointment_number',
                            'full_name',
                            'patient_number',
                            'email',
                            'phone',
                            'appointment_date',
                            DB::raw('NULL as appointment_time'),
                            'specialization',
                            DB::raw('NULL as doctor'),
                            DB::raw('NULL as booking_type'),
                            'status as appointment_status',
                            'notes',
                            DB::raw('NULL as doctor_comments'),
                            DB::raw('NULL as hospital_branch'),
                            DB::raw('NULL as cancellation_reason'),
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"external_pending" as source_table'),
                        ]);

                    $approvedQuery = ExternalApproved::query()
                        ->select([
                            'id',
                            'booking_id as appointment_number',
                            'full_name',
                            'patient_number',
                            'email',
                            'phone',
                            'appointment_date',
                            'appointment_time',
                            'specialization',
                            'doctor_name as doctor',
                            'hospital_branch',
                            DB::raw('NULL as booking_type'),
                            'appointment_status',
                            'notes',
                            'doctor_comments',
                            DB::raw('NULL as cancellation_reason'),
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"external_approved" as source_table'),
                        ])->whereBetween('appointment_date', [$startDate, $endDate]);
                    ;
                    if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                        $approvedQuery->where('hospital_branch', $selectedBranch);
                    }

                    $cancelledQuery = CancelledAppointment::query()
                        ->select([
                            'id',
                            'appointment_number',
                            'full_name',
                            'patient_number',
                            'email',
                            'phone',
                            'appointment_date',
                            'appointment_time',
                            'specialization',
                            'doctor_name as doctor',
                            'booking_type',
                            DB::raw('"cancelled" as appointment_status'),
                            'notes',
                            DB::raw('NULL as doctor_comments'),
                            'cancellation_reason',
                            'hospital_branch',
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"cancelled" as source_table'),
                        ])->whereBetween('appointment_date', [$startDate, $endDate]);
                    if ((!$isSuperadmin || ($isSuperadmin && $selectedBranch)) && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                        $cancelledQuery->where('hospital_branch', $selectedBranch);
                    }

                    try {
                        $query = $internalQuery->union($pendingQuery)
                            ->union($approvedQuery)
                            ->union($cancelledQuery);


                        $appointments = $query->get();
                        // Normalize data to ensure consistent structure
                        $appointments = $appointments->map(function ($appointment) {
                            return [
                                'id' => $appointment->id ?? null,
                                'appointment_number' => $appointment->appointment_number ?? '-',
                                'full_name' => $appointment->full_name ?? '-',
                                'patient_number' => $appointment->patient_number ?? '-',
                                'email' => $appointment->email ?? '-',
                                'phone' => $appointment->phone ?? '-',
                                'appointment_date' => $appointment->appointment_date ? (string) $appointment->appointment_date : null,
                                'appointment_time' => $appointment->appointment_time ?? null,
                                'specialization' => $appointment->specialization ?? '-',
                                'doctor' => $appointment->doctor ?? '-',
                                'booking_type' => $appointment->booking_type ?? '-',
                                'appointment_status' => $appointment->appointment_status ?? '-',
                                'notes' => $appointment->notes ?? '-',
                                'doctor_comments' => $appointment->doctor_comments ?? '-',
                                'hospital_branch' => $appointment->hospital_branch ?? '-',
                                'cancellation_reason' => $appointment->cancellation_reason ?? '-',
                                'created_at' => $appointment->created_at ? (string) $appointment->created_at : (string) now(),
                                'source_table' => $appointment->source_table ?? 'unknown',
                            ];
                        })->unique('id')->values();


                        if ($request->ajax()) {
                            return response()->json([
                                'data' => $appointments,
                                'status' => 'success'
                            ]);
}

                    } catch (\Exception $e) {
                        
                            return response()->json(['error' => 'Error loading table data: ' . $e->getMessage()], 500);
                    }
                    break;

                default:
                    return response()->json(['error' => 'Error sending data: '], 500);
                    
            }

        
            // Handle AJAX request
            if ($request->has('ajax')) {
                try {
                    $appointmentsArray = $appointments->toArray(); // Explicitly convert to array
                    Log::info("AJAX response data for status {$status}:", [
                        'appointments_count' => count($appointmentsArray),
                        'first_appointment' => !empty($appointmentsArray) ? $appointmentsArray[0] : null,
                        'status' => $status,
                        'specializations_count' => $specializations->count(),
                        'branch' => $selectedBranch,
                    ]);
                    return response()->json([
                        'appointments' => $appointmentsArray,
                        'status' => $status,
                        'specializations' => $specializations->toArray(),
                        'branch' => $selectedBranch,
                    ], 200, [], JSON_NUMERIC_CHECK);
                } catch (\Exception $e) {
                    Log::error("AJAX response failed: " . $e->getMessage(), [
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json(['error' => 'Error sending data: ' . $e->getMessage()], 500);
                }
            }

            $data['appointments'] = $appointments;
            $data['status'] = $status;
            $data['specializations'] = $specializations;
            $data['hospitalBranches'] = $hospitalBranches;
            $data['selectedBranch'] = $selectedBranch;
            $data['isSuperadmin'] = $isSuperadmin;
            $data['isAdmin'] = $isAdmin;
            $view = 'booking.appointments_list';

        }
    }


    protected function getHospitalBranchEnumValues()
    {
        try {
            // Get enum values from bk_appointments table
            $result = DB::select("SHOW COLUMNS FROM bk_appointments WHERE Field = 'hospital_branch'");

            if (empty($result)) {
                \Log::warning('Could not fetch hospital_branch enum values from bk_appointments table');
                return ['kijabe', 'naivasha', 'westlands', 'marira'];
            }

            preg_match("/^enum\((.*)\)$/", $result[0]->Type, $matches);
            if (empty($matches[1])) {
                \Log::warning('Invalid enum format for hospital_branch', ['type' => $result[0]->Type]);
                return ['kijabe', 'naivasha', 'westlands', 'marira'];
            }

            $enumValues = array_map(
                fn($value) => trim($value, "'"),
                explode(',', $matches[1])
            );

            \Log::info('Fetched hospital_branch enum values from bk_appointments:', ['values' => $enumValues]);
            return $enumValues;

        } catch (\Exception $e) {
            \Log::error('Error fetching enum values: ' . $e->getMessage());
            return ['kijabe', 'naivasha', 'westlands', 'marira'];
        }
    }
}