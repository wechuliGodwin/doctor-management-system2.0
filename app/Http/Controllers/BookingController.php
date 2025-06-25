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

class BookingController extends Controller
{

    //internal booking form
    public function internal()
    {
        Log::Error("Error working .....");
        $user = Auth::guard('booking')->user();

        // Define user roles for clarity
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';

        $userBranch = $user ? $user->hospital_branch : null;
        Log::error('The user branch is ', $userBranch);
        $bk_specializations = BkSpecializations::filter('hospital_branch', $userBranch)->pluck('name')->toArray(); // filter by logged in user
        return view('booking.internal_booking_form', compact('bk_specializations', ));
    }

    //to show booking form in the dashboard
    public function add()
    {
        $user = Auth::guard('booking')->user();
        // Define user roles for clarity
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';

        $userBranch = $user ? $user->hospital_branch : null;

        // Fetch all doctors/ Currently westlands
        $doctors = BkDoctor::where('hospital_branch', $userBranch)->get();
        $bk_specializations = BkSpecializations::where('hospital_branch', $userBranch)->pluck('name')->toArray(); // filter by logged in user
        //$bk_specializations = BkSpecializations::all(); // Fetch specializations (adjust based on your model)
        #$hospitalBranches = $this->getHospitalBranchEnumValues();
        return view('booking.add', compact('bk_specializations', 'doctors', 'userBranch'));
    }

    //external booking form
    public function show()
    {
        $specializations = BkSpecializations::where('hospital_branch', 'kijabe')->pluck('name')->toArray();
        return view('booking.show', compact('specializations'));
    }

    public function dashboard(Request $request, $status = null)
    {
        $status = $status ? strtolower($status) : null;
        Log::info("Dashboard accessed with status: " . ($status ?? 'none'), $request->all());

        // Validate request inputs. Remove 'branch' validation for non-superadmins.
        $validationRules = [
            'ajax' => 'nullable|boolean',
            'export_csv' => 'nullable|boolean',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ];

        $user = Auth::guard('booking')->user();

        // Define user roles for clarity
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';

        $userBranch = $user ? $user->hospital_branch : null;
        $selectedBranch = null; // Default to no branch selected initially

        if ($isSuperadmin && $status !== 'external_pending') {
            $validationRules['branch'] = 'nullable|in:kijabe,westlands,naivasha,marira';
            $request->validate($validationRules); // Validate here if branch is allowed
            $selectedBranch = $request->input('branch'); // Superadmin can explicitly select
        } else {
            // Admins (and other users if any) are restricted to their assigned branch.
            $request->validate($validationRules);
            if ($status !== 'external_pending') {
                $selectedBranch = $userBranch;
            }
        }

        $hasDateFilter = $request->filled('start_date') || $request->filled('end_date');

        $startDate = $request->input('start_date') ?
            Carbon::parse($request->input('start_date'))->startOfDay()->toDateString() :
            Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->input('end_date') ?
            Carbon::parse($request->input('end_date'))->endOfDay()->toDateString() :
            Carbon::now()->endOfMonth()->toDateString();


        $hospitalBranches = $this->getHospitalBranchEnumValues();

        $data = [];
        $specializations = BkSpecializations::where('hospital_branch', $userBranch)->orderBy('name')->get();
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
                        ->where('bk_appointments.appointment_status', '!=', 'rescheduled')->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate]);

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
                            'c.created_at',
                            DB::raw('"rescheduled" as source_table'),
                        ])
                        ->where('b.appointment_status', 'rescheduled')->whereBetween('c.appointment_date', [$startDate, $endDate]);

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

                    if ((!$isSuperadmin || ($isSuperadmin && $selectedBranch)) && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                        $query->where('hospital_branch', $selectedBranch);
                    }

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
                    // Restrict external_pending to Kijabe for non-superadmins
                    if (!$isSuperadmin && $userBranch !== 'kijabe') {
                        $pendingQuery->whereRaw('1 = 0'); // Return empty result
                    }

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

                    if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                        $internalQuery->where('bk_appointments.hospital_branch', $selectedBranch);
                        $approvedQuery->where('hospital_branch', $selectedBranch);
                        if (DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                            $cancelledQuery->where('hospital_branch', $selectedBranch);
                        }
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

                        Log::info('Appointments fetched for all after normalization:', [
                            'count' => $appointments->count(),
                            'first_item' => $appointments->first() ? json_encode($appointments->first()) : null,
                            'is_array' => is_array($appointments->toArray()),
                        ]);

                    } catch (\Exception $e) {
                        Log::error("Query failed for status all: " . $e->getMessage(), [
                            'sql' => $query->toSql(),
                            'bindings' => $query->getBindings(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        if ($request->has('ajax')) {
                            return response()->json(['error' => 'Error loading table data: ' . $e->getMessage()], 500);
                        }
                        return redirect()->route('booking.dashboard')->with('error', 'Failed to load appointments: ' . $e->getMessage());
                    }
                    break;

                default:
                    Log::warning("Invalid status provided: {$status}");
                    return redirect()->route('booking.dashboard')->with('error', 'Invalid status: ' . $status);
            }

            // Fetch appointments
            try {
                $appointments = $query->get()->unique('id');
                Log::info("Appointments fetched for {$status}: " . $appointments->count(), [
                    'branch' => $selectedBranch ?? 'all',
                    'user_role' => $user ? $user->role : 'guest',
                    'user_branch' => $userBranch ?? 'none'
                ]);
            } catch (\Exception $e) {
                Log::error("Query failed for status {$status}: " . $e->getMessage(), [
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                    'trace' => $e->getTraceAsString()
                ]);
                if ($request->has('ajax')) {
                    return response()->json(['error' => 'Error loading table data: ' . $e->getMessage()], 500);
                }
                return redirect()->route('booking.dashboard')->with('error', 'Failed to load appointments: ' . $e->getMessage());
            }

            // Handle AJAX request
            if ($request->has('ajax')) {
                try {
                    $appointments = $query->get()->map(function ($appointment) {
                        $appointment->unique_key = $appointment->id . '-' . $appointment->source_table;
                        return $appointment;
                    })->unique('unique_key')->values();

                    $appointmentsArray = $appointments->map(function ($appointment) use ($status) {
                        $base = [
                            'id' => $appointment->id ?? null,
                            'full_name' => $appointment->full_name ?? '-',
                            'patient_number' => $appointment->patient_number ?? '-',
                            'email' => $appointment->email ?? '-',
                            'phone' => $appointment->phone ?? '-',
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

                        if ($status === 'rescheduled') {
                            return array_merge($base, [
                                'appointment_number' => $appointment->previous_number ?? '-',
                                'previous_number' => $appointment->previous_number ?? '-',
                                'previous_specialization' => $appointment->previous_specialization ?? '-',
                                'previous_date' => $appointment->previous_date ? (string) $appointment->previous_date : '-',
                                'previous_time' => $appointment->previous_time ?? '-',
                                'from_to' => $appointment->from_to ?? '=>',
                                'current_number' => $appointment->current_number ?? '-',
                                'current_specialization' => $appointment->current_specialization ?? '-',
                                'current_date' => $appointment->current_date ? (string) $appointment->current_date : '-',
                                'current_time' => $appointment->current_time ?? '-',
                                'reason' => $appointment->reason ?? '-',
                                'previous_branch' => $appointment->previous_branch ?? '-',
                                'current_branch' => $appointment->current_branch ?? '-',
                            ]);
                        } else {
                            return array_merge($base, [
                                'appointment_number' => $appointment->appointment_number ?? '-',
                                'appointment_date' => $appointment->appointment_date ? (string) $appointment->appointment_date : '-',
                                'appointment_time' => $appointment->appointment_time ?? '-',
                            ]);
                        }
                    })->toArray();

                    return response()->json(['data' => $appointmentsArray], 200, [], JSON_NUMERIC_CHECK);
                } catch (\Exception $e) {
                    Log::error("AJAX query failed for status {$status}: " . $e->getMessage(), [
                        'sql' => $query->toSql(),
                        'bindings' => $query->getBindings(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json(['error' => 'Error loading table data: ' . $e->getMessage()], 500);
                }
            }

            // Export as CSV
            if ($request->has('export_csv')) {
                $filename = ($selectedBranch ? "{$selectedBranch}_" : "") . "{$status}_appointments_" . now()->format('Ymd_His') . ".csv";
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                ];

                if ($appointments->isEmpty()) {
                    return response()->stream(function () {
                        $file = fopen('php://output', 'w');
                        fputcsv($file, ['No records found']);
                        fclose($file);
                    }, 200, $headers);
                }

                $columns = array_keys((array) $appointments->first());
                $columns = array_diff($columns, ['id']);
                return response()->stream(function () use ($appointments, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    foreach ($appointments as $appointment) {
                        $row = [];
                        foreach ($columns as $col) {
                            $row[$col] = $appointment->$col;
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                }, 200, $headers);
            }

            $data['appointments'] = $appointments;
            $data['status'] = $status;
            $data['specializations'] = $specializations;
            $data['hospitalBranches'] = $hospitalBranches;
            $data['selectedBranch'] = $selectedBranch;
            $data['isSuperadmin'] = $isSuperadmin;
            $data['isAdmin'] = $isAdmin;
            $view = 'booking.appointments_list';

        } else {

            $baseQuery = BkAppointments::query()
                ->selectRaw("
                    SUM(CASE WHEN booking_type = 'new' THEN 1 ELSE 0 END) as new_count,
                    SUM(CASE WHEN booking_type = 'review' THEN 1 ELSE 0 END) as review_count,
                    SUM(CASE WHEN booking_type = 'post_op' THEN 1 ELSE 0 END) as postop_count
                ")
                ->where('appointment_status', '!=', 'rescheduled')
                ->whereBetween('appointment_date', [$startDate, $endDate]);

            if ($selectedBranch) {
                $baseQuery->where('hospital_branch', $selectedBranch);
            }
            $counts = $baseQuery->first();

            $data['totalNewAppointments'] = (int) ($counts->new_count ?? 0);
            $data['totalReviewAppointments'] = (int) ($counts->review_count ?? 0);
            $data['totalPostopAppointments'] = (int) ($counts->postop_count ?? 0);

            $rescheduledQuery = DB::table('bk_rescheduled_appointments as a')
                ->join('bk_appointments as b', 'a.appointment_id', '=', 'b.id')
                ->join('bk_appointments as c', 'a.re_appointment_id', '=', 'c.id')
                ->where('b.appointment_status', 'rescheduled')
                ->whereBetween('c.appointment_date', [$startDate, $endDate]);

            if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                $rescheduledQuery->where(function ($q) use ($selectedBranch) {
                    $q->where('b.hospital_branch', $selectedBranch)
                        ->orWhere('c.hospital_branch', $selectedBranch);
                });
            }
            $data['totalRescheduledAppointments'] = $rescheduledQuery->count();

            // External pending count, restricted to Kijabe for non-superadmins
            if (!$isSuperadmin && $userBranch !== 'kijabe') {
                $data['totalExternalPendingAppointments'] = 0;
            } else {
                $data['totalExternalPendingAppointments'] = ExternalPendingApproval::count();
            }

            // External approved count
            $externalApprovedQuery = ExternalApproved::whereBetween('appointment_date', [$startDate, $endDate]);
            if ($selectedBranch) {
                $externalApprovedQuery->where('hospital_branch', $selectedBranch);
            }
            if (!$isSuperadmin && $userBranch !== 'kijabe') {
                $externalApprovedQuery->whereRaw('1 = 0'); // Return empty result
            }
            $data['totalExternalApprovedAppointments'] = $externalApprovedQuery->count();


            // Cancelled: filter by branch if selectedBranch is defined and column exists
            $cancelledQuery = CancelledAppointment::whereBetween('appointment_date', [$startDate, $endDate]);
            ;
            if ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                $cancelledQuery->where('hospital_branch', $selectedBranch);
            }
            $data['totalCancelledAppointments'] = $cancelledQuery->count();

            $data['totalAppointments'] = $data['totalNewAppointments'] +
                $data['totalReviewAppointments'] +
                $data['totalPostopAppointments'] +
                // $data['totalRescheduledAppointments'] +
                $data['totalExternalPendingAppointments'] +
                $data['totalExternalApprovedAppointments'] +
                $data['totalCancelledAppointments'];

            // Specialization chart data
            $specializationData = $specializations->map(function ($specialization) use ($isSuperadmin, $selectedBranch, $startDate, $endDate,$userBranch) {
                $apptQuery = BkAppointments::where('specialization', $specialization->id)
                    ->whereBetween('appointment_date', [$startDate, $endDate]);
                if ($selectedBranch) {
                    $apptQuery->where('hospital_branch', $selectedBranch);
                }

                $externalApprovedSpecializationQuery = ExternalApproved::where('specialization', $specialization->name)
                    ->whereBetween('appointment_date', [$startDate, $endDate]);
                if ($selectedBranch) {
                    $externalApprovedSpecializationQuery->where('hospital_branch', $selectedBranch);
                }
                if (!$isSuperadmin && $userBranch !== 'kijabe') {
                    $externalApprovedSpecializationQuery->whereRaw('1 = 0');
                }

                $pendingCount = (!$isSuperadmin && $userBranch !== 'kijabe') ? 0 : ExternalPendingApproval::where('specialization', $specialization->name)->count();

                $cancelledCount = CancelledAppointment::where('specialization', $specialization->name)
                    ->whereBetween('appointment_date', [$startDate, $endDate]);
                if ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                    $cancelledCount = $cancelledCount->where('hospital_branch', $selectedBranch);
                }
                $cancelledCount = $cancelledCount->count();

                return [
                    'name' => $specialization->name,
                    'total' => $apptQuery->count() +
                        $pendingCount +
                        $externalApprovedSpecializationQuery->count() +
                        $cancelledCount
                ];
            })->filter()->sortByDesc('total');

            $data['labels'] = $specializationData->pluck('name')->toArray();
            $data['chartData'] = [
                [
                    'label' => 'Appointments by Specialization',
                    'data' => $specializationData->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#007bff',
                        '#28a745',
                        '#dc3545',
                        '#ffc107',
                        '#17a2b8',
                        '#6610f2',
                        '#fd7e14',
                        '#6f42c1',
                        '#e83e8c',
                        '#20c997'
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1
                ]
            ];

            // Branch chart data - only for superadmins or a single branch for admins
            if ($isSuperadmin) {
                $branchCounts = collect($hospitalBranches)->map(function ($branch) use ($startDate, $endDate) {
                    $apptCount = BkAppointments::where('hospital_branch', $branch)
                        ->whereBetween('appointment_date', [$startDate, $endDate])
                        ->count();
                    $externalApprovedCount = ExternalApproved::where('hospital_branch', $branch)
                        ->whereBetween('appointment_date', [$startDate, $endDate])
                        ->count();
                    return [
                        'name' => ucfirst($branch),
                        'total' => $apptCount + $externalApprovedCount
                    ];
                })->filter()->sortByDesc('total');

                $data['branchLabels'] = $branchCounts->pluck('name')->toArray();
                $data['branchChartData'] = [
                    [
                        'label' => 'Appointments by Branch',
                        'data' => $branchCounts->pluck('total')->toArray(),
                        'backgroundColor' => ['#28a745', '#007bff', '#ffc107', '#dc3545'],
                        'borderColor' => '#ffffff',
                        'borderWidth' => 1
                    ]
                ];
            } else {
                $data['branchLabels'] = [ucfirst($selectedBranch)];
                $data['branchChartData'] = [
                    [
                        'label' => 'Appointments for ' . ucfirst($selectedBranch),
                        'data' => [
                            BkAppointments::where('hospital_branch', $selectedBranch)
                                ->whereBetween('appointment_date', [$startDate, $endDate])
                                ->count() +
                            ExternalApproved::where('hospital_branch', $selectedBranch)
                                ->whereBetween('appointment_date', [$startDate, $endDate])
                                ->count()
                        ],
                        'backgroundColor' => ['#28a745'],
                        'borderColor' => '#ffffff',
                        'borderWidth' => 1
                    ]
                ];
            }

            $data['hospitalBranches'] = $hospitalBranches;
            $data['selectedBranch'] = $selectedBranch;
            $data['isSuperadmin'] = $isSuperadmin;
            $data['isAdmin'] = $isAdmin;
            $view = 'booking.dashboard';
        }

        return view($view, $data);
    }
    // Internal booking submit
    public function submitInternalAppointments(Request $request)
    {
        $consents = $request->input('consent', []);
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'patient_number' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable',
            'specialization' => 'required|string|max:255',
            'doctor_name' => 'nullable|string|max:100',
            'booking_type' => 'required|in:new,post_op,review',
            'phone' => 'required|string|max:100',
            'hospital_branch' => 'required|in:kijabe,naivasha,westlands,marira',
        ]);

        $bkspecialization = BkSpecializations::where('name', $validated['specialization'])
            ->where('hospital_branch', $validated['hospital_branch'])
            ->first();
        //Retrieve the doctor id for the selected doctor name 
        $doctor = BkDoctor::where('doctor_name', $validated['doctor_name'])
            ->where('hospital_branch', $validated['hospital_branch'])
            ->first();
        $doctor_id = $doctor ? $doctor->id : null;
        //........................................................................
        if (!$bkspecialization) {
            return back()->withInput()->with('error', 'Specialization not found.');
        }

        // Check booking limits before creating appointment
        $appointmentDate = $validated['appointment_date'];
        $currentBookings = $this->getActiveBookingsForDate($validated['specialization'], $appointmentDate);

        // Check if the specialization has a limit set and if it would be exceeded
        if ($bkspecialization->limits && $currentBookings >= $bkspecialization->limits) {
            $errorMessage = "Cannot book appointment. The daily limit of {$bkspecialization->limits} appointments for {$validated['specialization']} on " .
                date('Y-m-d', strtotime($appointmentDate)) . " has been reached. Current bookings: {$currentBookings}.";


            \Log::warning("Appointment booking limit exceeded", [
                'specialization' => $validated['specialization'],
                'date' => $appointmentDate,
                'current_bookings' => $currentBookings,
                'limit' => $bkspecialization->limits,
                'hospital_branch' => $validated['hospital_branch']
            ]);

            return back()->withInput()->with('error', $errorMessage);
        }

        try {
            $appointmentNumber = 'APPT-' . date('Y') . '-' . Str::upper(Str::random(8));
            $appointmentData = [
                'appointment_number' => $appointmentNumber,
                'full_name' => $validated['full_name'],
                'patient_number' => $validated['patient_number'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'] . ':00',
                'specialization' => $bkspecialization->id,
                'doctor_name' => $validated['doctor_name'],
                'hospital_branch' => $validated['hospital_branch'],
                'booking_type' => $validated['booking_type'],
                'appointment_status' => 'pending',
                'consent' => isset($validated['consent']) ? implode(',', $validated['consent']) : null,
                //consent checkboxes values
                'sms_check' => in_array('sms', $consents) ? 1 : 0,
                'whatsapp_check' => in_array('whatsapp', $consents) ? 1 : 0,
                'email_check' => in_array('email', $consents) ? 1 : 0,
                'doctor_id' => $doctor_id,
            ];

            $appointment = BkAppointments::create($appointmentData);

            \Log::info("Internal appointment booked", [
                'specialization' => $bkspecialization->name,
                'date' => $validated['appointment_date'],
                'appointment_id' => $appointment->id,
                'hospital_branch' => $validated['hospital_branch'],
                'bookings_after_creation' => $currentBookings + 1,
                'limit' => $bkspecialization->limits
            ]);

            return redirect()->back()->with('success', 'Appointment booked successfully!');
        } catch (\Exception $e) {
            \Log::error('Appointment creation failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Failed to book appointment: ' . $e->getMessage());
        }
    }
    // External booking submit
    public function submitExternalAppointment(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after_or_equal:today',
            'specialization_awareness' => 'required|in:not_aware,aware',
            'specialization' => 'required_if:specialization_awareness,aware|string|max:100',
            'notes' => 'required|string|max:5000',
            'patient_number' => 'nullable|string|max:255',
            'honey_name' => 'nullable|string|max:0', // Must be empty
            'honey_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    $submitTime = (int) $value;
                    $currentTime = (int) floor(time());
                    if ($currentTime - $submitTime < 5) {
                        $fail('Form submitted too quickly. Please try again.');
                    }
                },
            ],
        ]);

        // Log suspected bot attempt if honeypot fields are filled
        if (!empty($request->honey_name)) {
            Log::warning('Possible bot detected: honeypot field filled', [
                'honey_name' => $request->honey_name,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return back()->withInput()->with('error', 'Invalid submission. Please try again.');
        }

        // // Check for duplicate booking on the same date
        // $existingBooking = ExternalPendingApproval::where('appointment_date', $validated['appointment_date'])
        //     ->where(function ($query) use ($validated) {
        //         $query->where('email', $validated['email'])
        //             ->orWhere('phone', $validated['phone']);
        //         if (!empty($validated['patient_number'])) {
        //             $query->orWhere('patient_number', $validated['patient_number']);
        //         }
        //     })
        //     ->first();

        // if ($existingBooking) {
        //     return back()->withInput()->with(
        //         'error',
        //         "You already have a booking on {$validated['appointment_date']}. Only one booking per day is allowed. Please choose a different date or contact support for assistance."
        //     );
        // }

        $specializationName = $validated['specialization_awareness'] === 'aware' ? $validated['specialization'] : 'Not Aware';

        $appointmentNumber = 'EXT-' . Str::upper(Str::random(8));
        $patientNumber = $request->patient_number; // No auto-generation

        $appointmentData = [
            'appointment_number' => $appointmentNumber,
            'full_name' => strip_tags($request->full_name),
            'email' => strip_tags($request->email),
            'phone' => strip_tags($request->phone),
            'appointment_date' => $request->appointment_date,
            'specialization' => $specializationName,
            'notes' => strip_tags($request->notes),
            'status' => 'pending',
            'patient_number' => $patientNumber,
        ];

        $appointment = ExternalPendingApproval::create($appointmentData);

        // Prepare patient confirmation email data
        $patientEmailData = $appointmentData;
        $patientEmailData['message'] = 'Your appointment has been successfully booked. Please note that the appointment is subject to change based on availability or unforeseen circumstances. You will be notified incase of any updates or adjustments.';
        $patientEmailData['subject'] = 'Booking Confirmation';
        $patientEmailData['is_admin'] = false;

        // Send patient confirmation email
        if ($appointment->email) {
            Mail::to($appointment->email)->send(new ContactFormMail($patientEmailData));
            Log::info("Submission email sent to {$appointment->email} for appointment number: {$appointmentNumber}");
        }

        // Prepare admin notification email data
        $adminEmailData = $appointmentData;
        $adminEmailData['subject'] = 'New External Appointment Notification';
        $adminEmailData['is_admin'] = true;

        // Send admin notification email with main admin "To" and others as CC
        $mainAdminEmail = config('admin.main_email', 'test@kijabehospital.org');
        $ccAdminEmails = config('admin.cc_emails', ['test@kijabehospital.rg', 'ictintern007@kijabehospital.org']);
        Mail::to($mainAdminEmail)
            ->cc($ccAdminEmails)
            ->send(new ContactFormMail($adminEmailData));
        Log::info("Admin notification email sent for appointment number: {$appointmentNumber}", [
            'to' => $mainAdminEmail,
            'cc' => $ccAdminEmails,
        ]);

        Log::info("External appointment submitted", [
            'specialization' => $specializationName,
            'date' => $validated['appointment_date'],
            'appointment_number' => $appointmentNumber
        ]);

        return redirect()->back()->with('success', 'Your appointment has been successfully submitted. Please note that the appointment is subject to change based on availability or unforeseen circumstances. You will be notified in case of any updates or adjustments.');
    }

    //TODO: Ensure you filter by hospital_branch
    protected function getActiveBookingsForDate($specializationName, $date)
    {
        $queries = [
            // BkAppointments::where('specialization', $specializationName)
            //     ->whereDate('appointment_date', $date)
            //     ->whereNotIn('appointment_status', ['cancelled', 'rescheduled']),
            BkAppointments::join('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                ->where('bk_specializations.name', $specializationName)
                ->whereDate('bk_appointments.appointment_date', $date)
                ->whereNotIn('bk_appointments.appointment_status', ['cancelled', 'rescheduled']),
            ExternalApproved::where('specialization', $specializationName)
                ->whereDate('appointment_date', $date)
                ->whereNotIn('appointment_status', ['honoured', 'missed', 'cancelled', 'late']),
            ExternalPendingApproval::where('specialization', $specializationName)
                ->whereDate('appointment_date', $date)
                ->whereNotIn('status', ['honoured', 'missed', 'cancelled', 'late']), // Use 'status' here
        ];

        $count = 0;
        foreach ($queries as $query) {
            $count += $query->count();
        }

        return $count;
    }
    // protected function findNextAvailableDate($specializationName, $startDate)
    // {
    //     $specialization = Specialization::where('name', $specializationName)->first();
    //     if (!$specialization) {
    //         return 'Specialization not found.';
    //     }

    //     $date = Carbon::parse($startDate)->addDay();

    //     while ($date->lte(Carbon::now()->addDays(30))) {
    //         $activeBookings = $this->getActiveBookingsForDate($specializationName, $date->toDateString());
    //         if ($activeBookings < $specialization->daily_limit) {
    //             return $date->toDateString();
    //         }
    //         $date->addDay();
    //     }

    //     return 'No available dates in the next 30 days.';
    // }


    public function approveExternalAppointment(Request $request, $appointmentNumber)
    {
        \Log::info('Attempting to approve appointment', ['appointment_number' => $appointmentNumber, 'input' => $request->all()]);

        // Validate the input
        $validated = $request->validate([
            'patient_number' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'doctor_name' => 'nullable|string|max:100',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'nullable|date_format:H:i',
            'booking_type' => 'required|in:new,review,postop,external',
            'specialization' => 'required|string|max:255|exists:bk_specializations,name',
            'hospital_branch' => 'required|in:kijabe,naivasha,westlands,marira',
            'patient_notified' => 'nullable|boolean',
            'notes' => 'required|string|max:5000',
        ]);

        // Find the pending appointment
        $pendingAppointment = ExternalPendingApproval::where('appointment_number', $appointmentNumber)->first();
        if (!$pendingAppointment) {
            \Log::error('Pending appointment not found', ['appointment_number' => $appointmentNumber]);
            return redirect()->route('booking.dashboard.status', 'external_pending')->with('error', 'Pending appointment not found.');
        }

        // Check specialization
        $specializationName = $validated['specialization'];
        $specialization = BkSpecializations::where('name', $specializationName)->first();
        if (!$specialization) {
            \Log::error('Specialization not found', ['specialization' => $specializationName]);
            return back()->withInput()->with('error', "Specialization '{$specializationName}' not found.");
        }

        \DB::beginTransaction();
        try {
            $approvedData = [
                'appointment_status' => 'approved',
                'patient_number' => $validated['patient_number'],
                'booking_id' => $pendingAppointment->appointment_number,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'specialization' => $specializationName,
                'doctor_name' => $validated['doctor_name'],
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'] ? $validated['appointment_time'] . ':00' : null,
                'hospital_branch' => $validated['hospital_branch'],
                'patient_notified' => $validated['patient_notified'] ?? 0,
                'booking_type' => $validated['booking_type'],
                'notes' => $validated['notes'],
            ];

            // Create approved appointment
            $approvedAppointment = ExternalApproved::create($approvedData);
            \Log::info('Created approved appointment', ['booking_id' => $approvedAppointment->booking_id, 'id' => $approvedAppointment->id]);

            // Delete pending appointment
            $pendingAppointment->delete();
            \Log::info('Deleted pending appointment', ['appointment_number' => $appointmentNumber]);

            // Send approval email
            if ($approvedAppointment->email && $approvedAppointment->email !== 'N/A') {
                $emailData = $approvedData;
                $emailData['message'] = 'Your appointment has been approved. Please note that the appointment is subject to change based on availability or unforeseen circumstances. You will be notified in case of any updates or adjustments.';
                $emailData['subject'] = 'Appointment Approval';
                Mail::to($approvedAppointment->email)->send(new ContactFormMail($emailData));
                \Log::info("Approval email sent to {$approvedAppointment->email}", ['booking_id' => $approvedAppointment->booking_id]);
            }

            \DB::commit();
            \Log::info('Appointment approved successfully', ['booking_id' => $approvedAppointment->booking_id]);
            return redirect()->route('booking.dashboard.status', 'external_approved')->with('success', 'Appointment approved successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to approve appointment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'appointment_number' => $appointmentNumber
            ]);
            return back()->withInput()->with('error', 'Failed to approve appointment: ' . $e->getMessage());
        }
    }
    public function view($id, $status)
    {

        $user = Auth::guard('booking')->user();
        // Define user roles for clarity
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';

        $userBranch = $user ? $user->hospital_branch : null;

        $specializations = BkSpecializations::where('hospital_branch', $userBranch); // filter by logged in user

        $appointment = null;

        switch ($status) {
            case 'new':
                $appointment = BkAppointments::find($id);
                break;
            case 'review':
                $appointment = BkAppointments::find($id);
                break;
            case 'postop':
                $appointment = BkAppointments::find($id);
                break;
            case 'external_pending':
                $appointment = ExternalPendingApproval::where('id', $id)->where('status', 'pending')->first();
                break;
            case 'external_approved':
                $appointment = ExternalApproved::find($id);
                break;
            case 'cancelled':
                $appointment = CancelledAppointment::find($id);
                break;
            default:
                return redirect()->route('booking.dashboard')->with('error', 'Invalid status.');
        }

        if (!$appointment) {
            return redirect()->route('booking.dashboard')->with('error', 'Appointment not found.');
        }

        // Log the appointment data to debug email and doctor_comments
        \Log::info("Viewing appointment ID: {$id} with status: {$status}", [
            'email' => $appointment->email ?? 'Not set',
            'doctor_comments' => $appointment->doctor_comments ?? 'Not set',
        ]);

        // Pass both the appointment, status, and specializations for the dropdown
        return view('booking.view', compact('appointment', 'status', 'specializations'));
    }

    public function edit($id, $status)
    {
        \Log::info("Edit accessed", ['id' => $id, 'status' => $status]);
        $appointment = null;
        $specializations = BkSpecializations::all();

        switch ($status) {
            case 'new':
                $appointment = BkAppointments::findOrFail($id);
                break;
            case 'review':
                $appointment = BkAppointments::findOrFail($id);
                break;
            case 'postop':
                $appointment = BkAppointments::findOrFail($id);
                break;
            case 'external_pending':
                $appointment = ExternalPendingApproval::findOrFail($id);
                break;
            case 'external_approved':
                $appointment = ExternalApproved::findOrFail($id);
                break;
            case 'cancelled':
                $appointment = CancelledAppointment::findOrFail($id);
                break;
            default:
                \Log::warning("Invalid status for edit: {$status}", ['id' => $id]);
                return redirect()->route('booking.dashboard')->with('error', 'Invalid status.');
        }

        return view('booking.view', compact('appointment', 'specializations', 'status'));
    }
    public function update(Request $request, $id)
    {
        $status = $request->input('status');
        $source_table = $request->input('source_table');
        $form_type = $request->input('form_type');
        $appointment = null;
        //$hospitalBranches = $this->getHospitalBranchEnumValues();
        // Validate the request
        $rules = [
            'full_name' => 'required|string|max:100',
            'patient_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|date_format:H:i',
            'specialization' => 'required|string|max:100',
            'doctor_name' => 'nullable|string|max:100',
            'appointment_status' => 'required|in:pending,honoured,missed,late,cancelled',
            'booking_type' => 'nullable|in:new,review,post_op' . (in_array($status, ['new', 'review', 'postop', 'branch']) && $source_table !== 'external_approved' ? '|required' : ''),
            'doctor_comments' => 'nullable|string|max:5000',
            'cancellation_reason' => 'nullable|string|max:5000' . ($request->input('appointment_status') === 'cancelled' ? '|required' : ''),
            'notes' => 'nullable|string|max:5000',
            'status_field' => 'nullable|in:pending,approved',
            'patient_notified' => 'nullable|in:0,1',
            'booking_id' => 'nullable|string|max:100',
            //'hospital_branch' => 'required|in:' . implode(',', $hospitalBranches),
            'remarks' => 'nullable|string|max:5000',
            'source_table' => 'nullable|in:new,review,postop,external_pending,external_approved,cancelled',
            'form_type' => 'nullable|in:all_details',
            'visit_date' => 'nullable|date',

        ];

        $validated = $request->validate($rules);

        // Determine the model
        // if ($status === 'branch' && $source_table) {
        //     $status = $source_table;
        // }

        if ($status !== 'all') {
            $modelClass = match ($status) {
                'new' => BkAppointments::class,
                'review' => BkAppointments::class,
                'postop' => BkAppointments::class,
                'external_pending' => ExternalPendingApproval::class,
                'external_approved' => ExternalApproved::class,
                'cancelled' => CancelledAppointment::class,
                default => null,
            };

            if (!$modelClass) {
                \Log::error('Invalid appointment status', ['id' => $id, 'status' => $status, 'source_table' => $source_table]);
                return redirect()->back()->with('error', 'Invalid appointment status.');
            }

            $appointment = $modelClass::find($id);
            if (!$appointment) {
                \Log::error('Appointment not found', ['id' => $id, 'status' => $status, 'model' => $modelClass]);
                return redirect()->back()->with('error', 'Appointment not found.');
            }
        } else {
            $appointment = $this->findAppointmentById($id);
            if (!$appointment) {
                \Log::error('Appointment not found for update', ['id' => $id, 'status' => $status]);
                return redirect()->back()->with('error', 'Appointment not found.');
            }
            $status = $this->getStatusFromModel($appointment); //Status: local, external, ...
        }

        // Check daily limits for specialization
        $specialization = BkSpecializations::where('name', $validated['specialization'])->first();
        $oldStatus = $status === 'external_pending' ? $appointment->status : $appointment->appointment_status;
        $newStatus = $validated['appointment_status'];

        if ($specialization && $oldStatus !== $newStatus) {
            $this->updatePastAppointments($specialization->name, $validated['appointment_date']);
            $activeBookings = $this->getActiveBookingsForDate($specialization->name, $validated['appointment_date']);

            if (
                !in_array($newStatus, ['honoured', 'missed', 'cancelled', 'late']) &&
                in_array($oldStatus, ['honoured', 'missed', 'cancelled', 'late']) &&
                $activeBookings >= $specialization->daily_limit
            ) {
                $nextDate = $this->findNextAvailableDate($specialization->name, $validated['appointment_date']);
                return back()->withInput()->with('error', "Cannot change status to {$newStatus} as the limit for {$specialization->name} on {$validated['appointment_date']} is reached. Next available date: {$nextDate}.");
            }
        }

        // Prepare the update data
        $updateData = [
            'full_name' => $validated['full_name'],
            'patient_number' => $validated['patient_number'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'] ? $validated['appointment_time'] . ':00' : null,
            'specialization' => $specialization->id,
            'doctor_name' => $validated['doctor_name'],
            'appointment_status' => $newStatus,
            'booking_type' => in_array($status, ['new', 'review', 'postop']) ? $validated['booking_type'] : ($appointment->booking_type ?? null),
            'notes' => $validated['notes'] ?? $appointment->notes ?? null,
            //'hospital_branch' => in_array($status, ['new', 'review', 'postop', 'external_approved']) ? $validated['hospital_branch'] : ($appointment->hospital_branch ?? null),
            'visit_date' => $validated['visit_date'] ?? null,
        ];

        if (in_array($status, ['local', 'new', 'review', 'postop', 'external_approved'])) {
            $updateData['doctor_comments'] = $validated['doctor_comments'] ?? $appointment->doctor_comments ?? null;
        }
        if ($newStatus === 'cancelled') {
            $updateData['cancellation_reason'] = $validated['cancellation_reason'] ?? $appointment->cancellation_reason ?? null;
            $updateData['remarks'] = $validated['remarks'] ?? $appointment->remarks ?? null;
        }
        if ($status === 'external_pending') {
            $updateData['status'] = $validated['status_field'] ?? $appointment->status ?? 'pending';
            unset($updateData['appointment_status']);
            unset($updateData['hospital_branch']);
        }
        if ($status === 'external_approved') {
            $updateData['patient_notified'] = $validated['patient_notified'] ?? $appointment->patient_notified ?? 0;
            $updateData['booking_id'] = $validated['booking_id'] ?? $appointment->booking_id ?? null;
        }

        DB::beginTransaction();
        try {
            if ($newStatus === 'cancelled' && $status !== 'cancelled') {
                $newAppointment = CancelledAppointment::create($updateData + [
                    'appointment_number' => $appointment->appointment_number ?? $appointment->booking_id ?? null,
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'remarks' => $validated['remarks'] ?? null,
                ]);
                $appointment->delete();
                $newTableStatus = 'cancelled';
            } elseif (in_array($status, ['local', 'new', 'review', 'postop'])) {
                $appointment->update($updateData);
                $newTableStatus = $status;
            } elseif ($status === 'external_pending' && $validated['status_field'] === 'approved') {
                $newAppointment = ExternalApproved::create($updateData + [
                    'booking_id' => $validated['booking_id'] ?? $appointment->appointment_number ?? null,
                    'hospital_branch' => $validated['hospital_branch'],
                    'patient_notified' => $validated['patient_notified'] ?? 0,
                    'appointment_status' => $newStatus,
                ]);
                $appointment->delete();
                $newTableStatus = 'external_approved';
            } else {
                $appointment->update($updateData);
            }

            \Log::info("Appointment updated/moved", [
                'id' => $id,
                'original_status' => $status,
                'appointment_status' => $newStatus,
                'booking_type' => $updateData['booking_type'] ?? null,
                'form_type' => $form_type,
            ]);

            DB::commit();
            //!important
            // $branch = $request->input('branch') ?? $validated['hospital_branch'];
            // if ($branch && in_array($branch, $hospitalBranches)) {
            //     return redirect()->route('booking.branch', $branch)
            //         ->with('success', 'Appointment updated successfully!');
            // }
            return redirect()->route('booking.dashboard.status', $status)
                ->with('success', 'Appointment updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update appointment', [
                'id' => $id,
                'status' => $status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Failed to update appointment: ' . $e->getMessage());
        }
    }
    /**
     * Mark selected appointments as 'honoured' (came)
     */
    public function markAppointmentsCame(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'status' => 'required|in:new,review,postop,external_approved',
        ]);

        $ids = $validated['ids'];
        $status = $validated['status'];

        // Normalize status for consistency
        $status = $status === 'post-op' ? 'postop' : $status;

        $modelClass = match ($status) {
            'new' => BkAppointments::class,
            'review' => BkAppointments::class,
            'postop' => BkAppointments::class,
            'external_approved' => ExternalApproved::class,
            default => null,
        };

        if (!$modelClass) {
            \Log::error('Invalid status for marking appointments as came', ['status' => $status, 'ids' => $ids]);
            return response()->json(['error' => "Invalid appointment status: {$status}"], 400);
        }

        DB::beginTransaction();
        try {
            // Check if IDs exist in the table
            $existingIds = $modelClass::whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                \Log::warning('No matching appointments found for update', [
                    'status' => $status,
                    'ids' => $ids,
                    'model' => $modelClass,
                ]);
                return response()->json(['error' => 'No valid appointments found for the provided IDs'], 404);
            }

            $nonUpdatableIds = $modelClass::whereIn('id', $ids)
                ->whereIn('appointment_status', ['honoured', 'cancelled'])
                ->pluck('id')
                ->toArray();

            if (count($nonUpdatableIds) === count($ids)) {
                \Log::warning('All selected appointments are already honoured or cancelled', [
                    'status' => $status,
                    'ids' => $ids,
                    'non_updatable_ids' => $nonUpdatableIds,
                ]);
                return response()->json(['error' => 'Selected appointments are already marked as honoured or cancelled'], 400);
            }

            $updatedCount = $modelClass::whereIn('id', $ids)
                ->whereNotIn('appointment_status', ['honoured', 'cancelled'])
                ->update(['appointment_status' => 'honoured']);

            \Log::info("Appointments marked as honoured", [
                'status' => $status,
                'ids' => $ids,
                'existing_ids' => $existingIds,
                'non_updatable_ids' => $nonUpdatableIds,
                'updated_count' => $updatedCount,
                'model' => $modelClass,
            ]);

            DB::commit();
            return response()->json([
                'message' => "$updatedCount appointments marked as came successfully",
                'updated_count' => $updatedCount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to mark appointments as came', [
                'error' => $e->getMessage(),
                'status' => $status,
                'ids' => $ids,
                'model' => $modelClass,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to mark appointments as came: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mark selected appointments as 'missed'
     */
    public function markAppointmentsMissed(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'status' => 'required|in:new,review,postop,external_approved',
        ]);

        $ids = $validated['ids'];
        $status = $validated['status'];

        // Normalize status for consistency
        $status = $status === 'post-op' ? 'postop' : $status;

        $modelClass = match ($status) {
            'new' => BkAppointments::class,
            'review' => BkAppointments::class,
            'postop' => BkAppointments::class,
            'external_approved' => ExternalApproved::class,
            default => null,
        };

        if (!$modelClass) {
            \Log::error('Invalid status for marking appointments as missed', ['status' => $status, 'ids' => $ids]);
            return response()->json(['error' => "Invalid appointment status: {$status}"], 400);
        }

        DB::beginTransaction();
        try {
            // Check if IDs exist in the table
            $existingIds = $modelClass::whereIn('id', $ids)->pluck('id')->toArray();
            if (empty($existingIds)) {
                \Log::warning('No matching appointments found for update', [
                    'status' => $status,
                    'ids' => $ids,
                    'model' => $modelClass,
                ]);
                return response()->json(['error' => 'No valid appointments found for the provided IDs'], 404);
            }

            $nonUpdatableIds = $modelClass::whereIn('id', $ids)
                ->whereIn('appointment_status', ['missed', 'cancelled'])
                ->pluck('id')
                ->toArray();

            if (count($nonUpdatableIds) === count($ids)) {
                \Log::warning('All selected appointments are already missed or cancelled', [
                    'status' => $status,
                    'ids' => $ids,
                    'non_updatable_ids' => $nonUpdatableIds,
                ]);
                return response()->json(['error' => 'Selected appointments are already marked as missed or cancelled'], 400);
            }

            $updatedCount = $modelClass::whereIn('id', $ids)
                ->whereNotIn('appointment_status', ['missed', 'cancelled'])
                ->update(['appointment_status' => 'missed']);

            \Log::info("Appointments marked as missed", [
                'status' => $status,
                'ids' => $ids,
                'existing_ids' => $existingIds,
                'non_updatable_ids' => $nonUpdatableIds,
                'updated_count' => $updatedCount,
                'model' => $modelClass,
            ]);

            DB::commit();
            return response()->json([
                'message' => "$updatedCount appointments marked as missed successfully",
                'updated_count' => $updatedCount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to mark appointments as missed', [
                'error' => $e->getMessage(),
                'status' => $status,
                'ids' => $ids,
                'model' => $modelClass,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to mark appointments as missed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Find an appointment by ID across all models
     */
    protected function findAppointmentById($id)
    {
        $models = [
            'new' => BkAppointments::class,
            'review' => BkAppointments::class,
            'postop' => BkAppointments::class,
            'external_pending' => ExternalPendingApproval::class,
            'external_approved' => ExternalApproved::class,
            'cancelled' => CancelledAppointment::class,
        ];

        foreach ($models as $type => $model) {
            $appointment = $model::find($id);
            if ($appointment) {
                \Log::info("Appointment found in {$model}", ['id' => $id, 'type' => $type]);
                return $appointment;
            }
        }

        \Log::warning("Appointment not found in any model", ['id' => $id]);
        return null;
    }

    /**
     * Get the status from the model class
     */
    protected function getStatusFromModel($appointment)
    {
        switch (get_class($appointment)) {
            case BkAppointments::class:
                return 'local';
            case ExternalPendingApproval::class:
                return 'external_pending';
            case ExternalApproved::class:
                return 'external_approved';
            case CancelledAppointment::class:
                return 'cancelled';
            default:
                \Log::error('Unknown model class for status determination', ['class' => get_class($appointment)]);
                return 'all';
        }
    }
    public function cancelAppointment(Request $request, $id)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:5000',
            'status' => 'required|string',
        ]);

        $status = $validated['status'];

        $modelClass = match ($status) {
            'new' => BkAppointments::class,
            'review' => BkAppointments::class,
            'postop' => BkAppointments::class,
            'external_pending' => ExternalPendingApproval::class,
            'external_approved' => ExternalApproved::class,
            default => null,
        };

        if (!$modelClass) {
            \Log::error('Invalid status for cancellation', ['status' => $status]);
            return redirect()->back()->with('error', 'Invalid appointment status.');
        }

        $appointment = $modelClass::find($id);

        if (!$appointment) {
            \Log::error('Appointment not found for cancellation', ['id' => $id, 'status' => $status]);
            return redirect()->back()->with('error', 'Appointment not found.');
        }

        \DB::beginTransaction();

        try {
            $cancelledData = [
                'patient_number' => $appointment->patient_number ?? 'N/A',
                'booking_id' => $appointment->booking_id ?? $appointment->appointment_number ?? 'N/A',
                'full_name' => $appointment->full_name ?? $appointment->name ?? 'N/A',
                'email' => $appointment->email ?? 'N/A',
                'phone' => $appointment->phone ?? 'N/A',
                'specialization' => $appointment->specialization ?? 'N/A',
                'doctor_name' => $appointment->doctor_name ?? $appointment->doctor ?? 'N/A',
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time ?? null,
                'hospital_branch' => $appointment->hospital_branch ?? 'N/A',
                'remarks' => $appointment->remarks ?? null,
                'notes' => $appointment->notes ?? null,
                'cancellation_reason' => $validated['cancellation_reason'],
                'cancelled_at' => now(),
                'appointment_number' => $appointment->appointment_number ?? 'N/A',
                'appointment_status' => $appointment->appointment_status ?? 'cancelled',
                'booking_type' => $appointment->booking_type ?? $status,
            ];

            if (empty($cancelledData['patient_number'])) {
                $cancelledData['patient_number'] = 'UNKNOWN-' . Str::random(8);
                \Log::warning('Patient number was empty; assigned a default value', ['id' => $id, 'status' => $status]);
            }

            \Log::debug('Creating CancelledAppointment record', $cancelledData);

            $cancelledAppointment = new CancelledAppointment();
            foreach ($cancelledData as $key => $value) {
                $cancelledAppointment->$key = $value;
            }

            $cancelledAppointment->save();

            $savedCancelled = CancelledAppointment::find($cancelledAppointment->id);
            if (!$savedCancelled) {
                throw new \Exception('Failed to save cancelled appointment to the database.');
            }

            \Log::debug('CancelledAppointment record created', $savedCancelled->toArray());

            // Prepare email data
            $emailData = $cancelledData;
            $emailData['message'] = 'Your appointment has been cancelled.';
            $emailData['subject'] = 'Appointment Cancellation';

            // Send cancellation email
            if ($cancelledAppointment->email && $cancelledAppointment->email !== 'N/A') {
                Mail::to($cancelledAppointment->email)->send(new ContactFormMail($emailData));
                \Log::info("Cancellation email sent to {$cancelledAppointment->email} for appointment ID: {$cancelledAppointment->id}");
            }

            $appointment->delete();

            \DB::commit();

            \Log::info('Appointment cancelled successfully', [
                'id' => $cancelledAppointment->id,
                'booking_id' => $cancelledAppointment->booking_id,
            ]);

            return redirect()->route('booking.dashboard.status', 'cancelled')
                ->with('success', 'Appointment cancelled successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Failed to cancel appointment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'cancelled_data' => $cancelledData ?? null,
            ]);

            return redirect()->back()->with('error', 'Failed to cancel appointment: ' . $e->getMessage());
        }
    }


    public function reschedule(Request $request, $id)
    {

        $form_type = $request->input('form_type');
        \Log::info('calling reschedule function');
        $appointment = null;
        //$hospitalBranches = $this->getHospitalBranchEnumValues();
        // Validate the request
        $rules = [
            'full_name' => 'required|string|max:100',
            'patient_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|date_format:H:i:s',
            'specialization' => 'required|string|max:100',
            'doctor_name' => 'nullable|string|max:100',
            'booking_type' => 'nullable|in:new,review,post_op',
            'form_type' => 'nullable|in:all_details',
            'reason' => 'required|string|max:512'
        ];


        $validated = $request->validate($rules);
        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     \Log::error('Validation failed', $validator->errors()->toArray());
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        \Log::error('after validation');
        \Log::error('Testing', ['name' => $validated['full_name']]);

        // Determine the model
        // if ($status === 'branch' && $source_table) {
        //     $status = $source_table;
        // }

        $modelClass = BkAppointments::class;
        if (!$modelClass) {
            \Log::error('Invalid appointment status', ['id' => $id, 'status' => $status, 'source_table' => $source_table]);
            return redirect()->back()->with('error', 'Invalid appointment status.');
        }

        $appointment = $modelClass::find($id);
        if (!$appointment) {
            \Log::error('Appointment not found', ['id' => $id, 'status' => $status, 'model' => $modelClass]);
            return redirect()->back()->with('error', 'Appointment not found.');
        } else {
            $appointment = $this->findAppointmentById($id);
            if (!$appointment) {
                \Log::error('Appointment not found for update', ['id' => $id, 'status' => $status]);
                return redirect()->back()->with('error', 'Appointment not found.');
            }
            //$status = $this->getStatusFromModel($appointment); //Status: local, external, ...
        }

        // Check daily limits for specialization
        $specialization = BkSpecializations::where('name', $validated['specialization'])->first();
        if (!$specialization) {
            \Log::error('[Error] getting the specialization');
        }


        // Prepare the update data

        $appointmentNumber = 'APPT-' . date('Y') . '-' . Str::upper(Str::random(8));
        $newAppointmentData = [
            'appointment_number' => $appointmentNumber,
            'full_name' => $validated['full_name'],
            'patient_number' => $validated['patient_number'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'] ? $validated['appointment_time'] : null,
            'specialization' => $specialization->id,
            'doctor_name' => $validated['doctor_name'],
            'booking_type' => $validated['booking_type'],
        ];

        if (!$appointment) {
            \Log::error('Current appointment not found for rescheduling', ['id' => $id]);
            return redirect()->back()->with('error', 'Rescheduling appointment not found.');
        }

        \DB::beginTransaction();
        \Log::error('[info] Starting the Transaction');

        try {
            $targetModel = BkAppointments::class;


            // Only include doctor_comments for ExternalApproved

            //update the current appointment status to rescheduled.
            $appointment->appointment_status = 'rescheduled';
            $appointment->save();

            //create a  new appointment
            $newAppointment = $targetModel::create($newAppointmentData);
            if (!$newAppointment) {
                throw new \Exception('Failed to Create the new appointment to the database.');
            }

            \Log::debug('New appointment record created', $newAppointment->toArray());

            //create the Reschedule record
            $rescheduled_data = [
                'appointment_id' => $appointment->id,
                're_appointment_id' => $newAppointment->id,
                'reason' => $validated['reason']
            ];

            $rescheduled_record = BkRescheduledAppointments::create($rescheduled_data);
            if (!$rescheduled_record) {
                throw new \Exception('Failed to create the reschedule record.');
            }

            // // Prepare email data
            // $emailData = $reapprovedData;
            // $emailData['message'] = 'Your previously cancelled appointment has been reapproved.';
            // $emailData['subject'] = 'Appointment Re-approval';

            // // Send re-approval email
            // if ($reapprovedAppointment->email && $reapprovedAppointment->email !== 'N/A') {
            //     Mail::to($reapprovedAppointment->email)->send(new ContactFormMail($emailData));
            //     \Log::info("Re-approval email sent to {$reapprovedAppointment->email} for appointment ID: {$reapprovedAppointment->id}");
            // }

            \DB::commit();

            \Log::info('Appointment rescheduling was successfully', [
                'id' => $rescheduled_record->id,
                'booking_id' => $rescheduled_record->appointment_id,
            ]);

            $status = $newAppointment->booking_type === 'post_op' ? 'postop' : $newAppointment->booking_type;
            return redirect()->route('booking.dashboard.status', $status)
                ->with('success', 'Appointment rescheduled successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Failed to reapprove appointment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'reapproved_data' => $reapprovedData ?? null,
            ]);

            return redirect()->back()->with('error', 'Failed to reapprove appointment: ' . $e->getMessage());
        }
    }

    public function reapproveAppointment(Request $request, $id)
    {
        $cancelledAppointment = CancelledAppointment::find($id);

        if (!$cancelledAppointment) {
            \Log::error('Cancelled appointment not found for reapproval', ['id' => $id]);
            return redirect()->back()->with('error', 'Cancelled appointment not found.');
        }

        \DB::beginTransaction();

        try {
            $targetModel = match ($cancelledAppointment->booking_type) {
                'external' => ExternalApproved::class,
                'new' => NewAppointment::class,
                'review' => ReviewAppointment::class,
                'postop' => PostOpAppointment::class,
                default => ExternalApproved::class, // Default fallback
            };

            $reapprovedData = [
                'patient_number' => $cancelledAppointment->patient_number,
                'booking_id' => $cancelledAppointment->booking_id,
                'full_name' => $cancelledAppointment->full_name,
                'email' => $cancelledAppointment->email,
                'phone' => $cancelledAppointment->phone,
                'specialization' => $cancelledAppointment->specialization,
                'doctor_name' => $cancelledAppointment->doctor_name,
                'appointment_date' => $cancelledAppointment->appointment_date,
                'appointment_time' => $cancelledAppointment->appointment_time,
                'hospital_branch' => $cancelledAppointment->hospital_branch,
                'notes' => $cancelledAppointment->notes,
                'patient_notified' => 0,
                'appointment_status' => 'approved',
                'booking_type' => $cancelledAppointment->booking_type,
            ];

            // Only include doctor_comments for ExternalApproved
            if ($targetModel === ExternalApproved::class) {
                $reapprovedData['doctor_comments'] = $cancelledAppointment->remarks;
            }

            \Log::debug('Creating reapproved appointment record', $reapprovedData);

            $reapprovedAppointment = $targetModel::create($reapprovedData);

            $savedReapproved = $targetModel::find($reapprovedAppointment->id);
            if (!$savedReapproved) {
                throw new \Exception('Failed to save reapproved appointment to the database.');
            }

            \Log::debug('Reapproved appointment record created', $savedReapproved->toArray());

            // Prepare email data
            $emailData = $reapprovedData;
            $emailData['message'] = 'Your previously cancelled appointment has been reapproved.';
            $emailData['subject'] = 'Appointment Re-approval';

            // Send re-approval email
            if ($reapprovedAppointment->email && $reapprovedAppointment->email !== 'N/A') {
                Mail::to($reapprovedAppointment->email)->send(new ContactFormMail($emailData));
                \Log::info("Re-approval email sent to {$reapprovedAppointment->email} for appointment ID: {$reapprovedAppointment->id}");
            }

            $cancelledAppointment->delete();

            \DB::commit();

            \Log::info('Appointment reapproved successfully', [
                'id' => $reapprovedAppointment->id,
                'booking_id' => $reapprovedAppointment->booking_id,
            ]);

            return redirect()->route('booking.dashboard.status', 'external_approved')
                ->with('success', 'Appointment reapproved successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Failed to reapprove appointment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'reapproved_data' => $reapprovedData ?? null,
            ]);

            return redirect()->back()->with('error', 'Failed to reapprove appointment: ' . $e->getMessage());
        }
    }
    public function deleteAppointment(Request $request, $id)
    {
        // Get the status from the request (from POST data, not query string)
        $status = $request->input('status', 'external_approved');

        // Determine the model based on the status
        $modelClass = match ($status) {
            'new' => BkAppointments::class,
            'review' => BkAppointments::class,
            'postop' => BkAppointments::class,
            'external_pending' => ExternalPendingApproval::class,
            'external_approved' => ExternalApproved::class,
            default => null,
        };

        if (!$modelClass) {
            \Log::error('Invalid status for deletion', ['status' => $status, 'id' => $id]);
            return redirect()->back()->with('error', 'Invalid appointment status.');
        }

        $appointment = $modelClass::find($id);

        if (!$appointment) {
            \Log::error('Appointment not found for deletion', ['id' => $id, 'status' => $status]);
            return redirect()->back()->with('error', 'Appointment not found.');
        }

        try {
            $appointment->delete();

            \Log::info('Appointment deleted successfully', [
                'id' => $id,
                'status' => $status,
                'booking_id' => $appointment->booking_id ?? $appointment->appointment_number ?? 'N/A',
            ]);

            return redirect()->route('booking.dashboard.status', $status)
                ->with('success', 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete appointment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id,
                'status' => $status,
            ]);

            return redirect()->back()->with('error', 'Failed to delete appointment: ' . $e->getMessage());
        }
    }
    public function bookingReports(Request $request)
    {
        \Log::info("Reports accessed", $request->all());

        $reportType = $request->input('report_type', 'all');

        // Base appointments query with specialization names
        $baseAppointmentsQuery = BkAppointments::query()
            ->leftJoin('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
            ->select([
                'bk_appointments.id',
                'bk_appointments.appointment_number',
                'bk_appointments.full_name',
                'bk_appointments.patient_number',
                'bk_appointments.phone',
                'bk_appointments.email',
                'bk_appointments.appointment_date',
                'bk_appointments.appointment_time',
                'bk_specializations.name as specialization',
                'bk_appointments.doctor_name as doctor',
                'bk_appointments.booking_type',
                'bk_appointments.appointment_status',
                'bk_appointments.hospital_branch',
                DB::raw('NULL as cancellation_reason'),
                DB::raw('NULL as rescheduled_reason'),
                DB::raw('NULL as rescheduled_at'),
                DB::raw('"Regular Appointment" as source_table')
            ]);

        // External approved appointments
        $externalApprovedQuery = ExternalApproved::query()
            ->select([
                'id',
                'booking_id as appointment_number',
                'full_name',
                'patient_number',
                'phone',
                'email',
                'appointment_date',
                'appointment_time',
                'specialization',
                'doctor_name as doctor',
                'booking_type',
                'appointment_status',
                'hospital_branch',
                DB::raw('NULL as cancellation_reason'),
                DB::raw('NULL as rescheduled_reason'),
                DB::raw('NULL as rescheduled_at'),
                DB::raw('"External Approved" as source_table')
            ]);

        // External pending approvals
        $externalPendingQuery = ExternalPendingApproval::query()
            ->select([
                'id',
                'appointment_number',
                'full_name',
                'patient_number',
                'phone',
                'email',
                'appointment_date',
                DB::raw('NULL as appointment_time'),
                'specialization',
                DB::raw('NULL as doctor'),
                DB::raw('NULL as booking_type'),
                'status as appointment_status',
                DB::raw('NULL as hospital_branch'),
                DB::raw('NULL as cancellation_reason'),
                DB::raw('NULL as rescheduled_reason'),
                DB::raw('NULL as rescheduled_at'),
                DB::raw('"External Pending" as source_table')
            ]);

        // Cancelled appointments - joining with original appointment details
        $cancelledQuery = DB::table('bk_cancelled_appointments as c')
            ->leftJoin('bk_appointments as a', 'c.appointment_id', '=', 'a.id')
            ->leftJoin('bk_specializations as s', 'a.specialization', '=', 's.id')
            ->select([
                'c.id',
                'a.appointment_number',
                'a.full_name',
                'a.patient_number',
                'a.phone',
                'a.email',
                'a.appointment_date',
                'a.appointment_time',
                's.name as specialization',
                'a.doctor_name as doctor',
                'a.booking_type',
                DB::raw('"cancelled" as appointment_status'),
                'a.hospital_branch',
                'c.cancellation_reason',
                DB::raw('NULL as rescheduled_reason'),
                'c.cancelled_at as rescheduled_at',
                DB::raw('"Cancelled" as source_table')
            ]);

        // Rescheduled appointments - showing the new appointment details
        $rescheduledQuery = DB::table('bk_rescheduled_appointments as r')
            ->leftJoin('bk_appointments as original', 'r.appointment_id', '=', 'original.id')
            ->leftJoin('bk_appointments as new_appt', 'r.re_appointment_id', '=', 'new_appt.id')
            ->leftJoin('bk_specializations as s', 'new_appt.specialization', '=', 's.id')
            ->select([
                'r.id',
                'new_appt.appointment_number',
                'new_appt.full_name',
                'new_appt.patient_number',
                'new_appt.phone',
                'new_appt.email',
                'new_appt.appointment_date',
                'new_appt.appointment_time',
                's.name as specialization',
                'new_appt.doctor_name as doctor',
                'new_appt.booking_type',
                'new_appt.appointment_status',
                'new_appt.hospital_branch',
                DB::raw('NULL as cancellation_reason'),
                'r.reason as rescheduled_reason',
                'r.created_at as rescheduled_at',
                DB::raw('"Rescheduled" as source_table')
            ]);

        // Build the final query based on report type
        switch ($reportType) {
            case 'regular':
                $query = $baseAppointmentsQuery->where('bk_appointments.appointment_status', '!=', 'cancelled');
                break;

            case 'new':
                $query = $baseAppointmentsQuery->where('bk_appointments.booking_type', 'new');
                break;

            case 'review':
                $query = $baseAppointmentsQuery->where('bk_appointments.booking_type', 'review');
                break;

            case 'postop':
                $query = $baseAppointmentsQuery->where('bk_appointments.booking_type', 'post-op');
                break;

            case 'external_approved':
                $query = $externalApprovedQuery;
                break;

            case 'external_pending':
                $query = $externalPendingQuery;
                break;

            case 'cancelled':
                $query = $cancelledQuery;
                break;

            case 'rescheduled':
                $query = $rescheduledQuery;
                break;

            case 'all':
            default:
                // Union all queries for comprehensive report
                $query = $baseAppointmentsQuery
                    ->union($externalApprovedQuery)
                    ->union($externalPendingQuery)
                    ->union($cancelledQuery)
                    ->union($rescheduledQuery);
                break;
        }

        // Apply date filters if provided
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate || $endDate) {
            // For union queries, we need to wrap them
            if ($reportType === 'all') {
                $subQuery = $query;
                $query = DB::table(DB::raw("({$subQuery->toSql()}) as combined"))
                    ->mergeBindings($subQuery);
            }

            if ($startDate) {
                $query->where('appointment_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('appointment_date', '<=', $endDate);
            }
        }

        // Get all appointments
        $appointments = $query->orderBy('appointment_date', 'desc')->get();

        // Handle CSV export
        if ($request->has('export_csv')) {
            $filename = "appointments_report_{$reportType}_" . now()->format('Ymd_His') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $columns = [
                'appointment_number',
                'full_name',
                'patient_number',
                'phone',
                'email',
                'appointment_date',
                'appointment_time',
                'specialization',
                'doctor',
                'booking_type',
                'appointment_status',
                'hospital_branch',
                'source_table',
                'cancellation_reason',
                'rescheduled_reason',
                'rescheduled_at'
            ];

            $callback = function () use ($appointments, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($appointments as $appointment) {
                    $row = [];
                    foreach ($columns as $column) {
                        $row[] = $appointment->$column ?? '';
                    }
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Pagination for display
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $paginatedAppointments = $appointments->forPage($page, $perPage);
        $appointmentsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedAppointments,
            $appointments->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        \Log::info("Reports fetched: " . $appointments->count() . " total records");

        $data = [
            'title' => ucfirst(str_replace('_', ' ', $reportType)) . ' Appointments Report',
            'appointments' => $appointmentsPaginated,
            'allAppointments' => $appointments,
        ];

        return view('booking.reports', $data);
    }

    public function specializationLimits(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $search = $request->input('search'); // Get the search parameter from the request
        $user = Auth::guard('booking')->user();
        // Define user roles for clarity
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';

        $userBranch = $user ? $user->hospital_branch : null;

        // Start with the base query
        $query = BkSpecializations::query()
            ->join(
                'bk_specialization_group',            // the table to join
                'bk_specializations.group_id',         // local key
                '=',
                'bk_specialization_group.id'          // foreign key
            )
            ->where('bk_specializations.hospital_branch', $userBranch)
            ->select([
                'bk_specializations.*',
                'bk_specialization_group.group_name as group_name',
                // …any other columns you need…
            ]);

        // Apply search filter if search term is provided
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Get the filtered specializations
        $specializations = $query->get();
        $specializations_group = BkSpecializationsGroup::all();

        // Update past appointments for all specializations (regardless of search)
        //$this->updatePastAppointmentsForAll($date);

        // Calculate active bookings for the filtered specializations
        $activeBookings = [];
        foreach ($specializations as $specialization) {
            $activeBookings[$specialization->name] = $this->getActiveBookingsForDate(
                $specialization->name,
                $date
            );
        }

        $data = [
            'title' => 'Specialization Daily Limits - ' . $date,
            'specializations' => $specializations,
            'date' => Carbon::parse($date), // Parse date for easier manipulation in Blade
            'activeBookings' => $activeBookings,
            'specializations_group' => $specializations_group,
        ];

        return view('booking.specialization_limits', $data);
    }
    public function updateSpecializationLimit(Request $request)
    {
        $validated = $request->validate([
            'specialization_id' => 'required|exists:specializations,specialization_id',
            'daily_limit' => 'required|integer|min:1',
            'group_id' => 'required|integer',
            'day_of_week' => 'required|string'
        ]);

        $spec_group = BkSpecializationsGroup::find($validated['group_id']);
        $specialization = BkSpecializations::findOrFail($validated['specialization_id']);
        $specialization->limits = $validated['daily_limit'];
        $specialization->group_id = $spec_group->id;
        $specialization->day_of_week = $validated['day_of_week'];
        $specialization->save();

        \Log::info("Specialization limit updated", [
            'specialization' => $specialization->name,
            'new_limit' => $specialization->limits,
            'day_of_week' => $specialization->day_of_week,
        ]);

        return redirect()->back()->with('success', 'Daily limit updated successfully.');
    }

    protected function updatePastAppointments($specializationName, $currentDate)
    {
        $tables = [
            'new_appointments' => [NewAppointment::class, 'appointment_status'],
            'review_appointments' => [ReviewAppointment::class, 'appointment_status'],
            'post_op_appointments' => [PostOpAppointment::class, 'appointment_status'],
            'external_approved' => [ExternalApproved::class, 'appointment_status'],
            'external_pending_approvals' => [ExternalPendingApproval::class, 'status'],
        ];

        foreach ($tables as $table => [$model, $statusColumn]) {
            $appointments = $model::where('specialization', $specializationName)
                ->whereDate('appointment_date', '<', $currentDate)
                ->whereIn($statusColumn, ['pending', 'approved'])
                ->get();

            foreach ($appointments as $appointment) {
                // Use 'cancelled' for external_pending_approvals, 'missed' for others
                $newStatus = $appointment->appointment_date < now()->toDateString()
                    ? ($table === 'external_pending_approvals' ? 'cancelled' : 'missed')
                    : $appointment->$statusColumn;

                if ($newStatus !== $appointment->$statusColumn) {
                    $appointment->$statusColumn = $newStatus;
                    $appointment->save();
                    \Log::info("Updated past appointment status", [
                        'table' => $table,
                        'id' => $appointment->id,
                        'new_status' => $newStatus,
                    ]);
                }
            }
        }
    }
    protected function updatePastAppointmentsForAll($currentDate)
    {
        $specializations = BkSpecializations::all();
        foreach ($specializations as $specialization) {
            $this->updatePastAppointments($specialization->name, $currentDate);
        }
    }

    public function calendar(Request $request)
    {
        $viewMode = $request->input('view', 'month');
        $date = $request->input('date', now()->toDateString());
        $search = $request->input('search');

        // Get current user's branch
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $appointments = $this->getAllAppointmentsForCalendar($date, $viewMode, $search, $userBranch);
        $bookingsData = $this->calculateBookingsData($appointments, $viewMode, $date);

        // Filter holidays by branch if they have branch column, otherwise show all
        $holidays = Holiday::when(Schema::hasColumn('holidays', 'hospital_branch'), function ($query) use ($userBranch) {
            return $query->where('hospital_branch', $userBranch);
        })->get()->map(function ($holiday) {
            return [
                'title' => $holiday->name,
                'start' => $holiday->date,
                'backgroundColor' => '#808080',
                'borderColor' => '#808080',
                'editable' => false,
            ];
        })->toArray();

        $upcomingAppointments = $this->getUpcomingAppointments($userBranch);
        $this->sendAppointmentReminders($userBranch);

        return view('booking.calendar', [
            'title' => 'Appointment Calendar',
            'bookingsData' => json_encode($bookingsData),
            'holidays' => json_encode($holidays),
            'upcomingAppointments' => $upcomingAppointments,
            'viewMode' => $viewMode,
            'currentDate' => $date,
            'appointments' => $appointments,
            'userBranch' => $userBranch,
        ]);
    }

    protected function getAllAppointmentsForCalendar($date, $viewMode, $search = null, $userBranch = null)
    {
        $startDate = Carbon::parse($date);
        if ($viewMode === 'day') {
            $endDate = $startDate->copy()->endOfDay();
            $startDate = $startDate->startOfDay();
        } elseif ($viewMode === 'week') {
            $startDate = $startDate->startOfWeek();
            $endDate = $startDate->copy()->endOfWeek();
        } else { // month
            $startDate = $startDate->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
        }

        $queries = [
            'new' => NewAppointment::select(['id', 'full_name', 'patient_number', 'phone', 'appointment_date', 'appointment_time', 'specialization', 'doctor_name as doctor', 'booking_type', 'appointment_status'])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->when($userBranch, function ($query) use ($userBranch) {
                    return $query->where('hospital_branch', $userBranch);
                }),
            'review' => ReviewAppointment::select(['id', 'full_name', 'patient_number', 'phone', 'appointment_date', 'appointment_time', 'specialization', 'doctor_name as doctor', 'booking_type', 'appointment_status'])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->when($userBranch, function ($query) use ($userBranch) {
                    return $query->where('hospital_branch', $userBranch);
                }),
            'postop' => PostOpAppointment::select(['id', 'full_name', 'patient_number', 'phone', 'appointment_date', 'appointment_time', 'specialization', 'doctor_name as doctor', 'booking_type', 'appointment_status'])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->when($userBranch, function ($query) use ($userBranch) {
                    return $query->where('hospital_branch', $userBranch);
                }),
            'external_pending' => ExternalPendingApproval::select(['id', 'appointment_number', 'full_name', 'patient_number', 'phone', 'appointment_date', DB::raw('NULL as appointment_time'), 'specialization', DB::raw('NULL as doctor'), DB::raw('NULL as hospital_branch'), DB::raw('"external" as booking_type'), 'status as appointment_status'])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->when($userBranch, function ($query) use ($userBranch) {
                    return $query->where('hospital_branch', $userBranch);
                }),
            'external_approved' => ExternalApproved::select(['id', 'booking_id as appointment_number', 'full_name', 'patient_number', 'phone', 'appointment_date', 'appointment_time', 'specialization', 'doctor_name as doctor', 'booking_type', 'appointment_status'])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->when($userBranch, function ($query) use ($userBranch) {
                    return $query->where('hospital_branch', $userBranch);
                }),
            'cancelled' => CancelledAppointment::select(['id', 'appointment_number', 'full_name', 'patient_number', 'phone', 'appointment_date', 'appointment_time', 'specialization', 'doctor_name as doctor', 'booking_type', 'appointment_status'])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->when($userBranch, function ($query) use ($userBranch) {
                    return $query->where('hospital_branch', $userBranch);
                }),
        ];

        if ($search) {
            foreach ($queries as $type => $query) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('patient_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('specialization', 'like', "%{$search}%")
                        ->orWhere('doctor_name', 'like', "%{$search}%");
                });
            }
        }

        $allAppointments = [];
        $colorMap = [
            'new' => '#28a745',
            'review' => '#007bff',
            'postop' => '#ffc107',
            'external_pending' => '#fd7e14',
            'external_approved' => '#17a2b8',
            'cancelled' => '#dc3545',
        ];

        foreach ($queries as $type => $query) {
            $appointments = $query->get()->map(function ($appt) use ($type, $colorMap) {
                // Normalize the start datetime
                $startDate = Carbon::parse($appt->appointment_date)->startOfDay(); // Strip any time from date
                $start = $appt->appointment_time
                    ? $startDate->setTimeFromTimeString($appt->appointment_time)->toIso8601String()
                    : $startDate->toDateString(); // Use date only if no time

                return [
                    'id' => $appt->id,
                    'title' => $appt->full_name,
                    'start' => $start,
                    'backgroundColor' => $colorMap[$type],
                    'borderColor' => $colorMap[$type],
                    'extendedProps' => [
                        'type' => $type,
                        'patient_number' => $appt->patient_number,
                        'phone' => $appt->phone,
                        'specialization' => $appt->specialization,
                        'doctor' => $appt->doctor,
                        'appointment_status' => $appt->appointment_status,
                        'appointment_time' => $appt->appointment_time,
                        'booking_type' => $appt->booking_type,
                    ],
                ];
            })->toArray();
            $allAppointments = array_merge($allAppointments, $appointments);
        }

        return $allAppointments;
    }

    protected function calculateBookingsData($appointments, $viewMode, $date)
    {
        $startDate = Carbon::parse($date);
        $slots = [];

        if ($viewMode === 'day') {
            $slots[$startDate->toDateString()] = $this->summarizeBookings($appointments, $startDate);
        } elseif ($viewMode === 'week') {
            $weekStart = $startDate->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $day = $weekStart->copy()->addDays($i)->toDateString();
                $slots[$day] = $this->summarizeBookings($appointments, $day);
            }
        } else { // month
            $monthStart = $startDate->startOfMonth();
            $daysInMonth = $monthStart->daysInMonth;
            for ($i = 0; $i < $daysInMonth; $i++) {
                $day = $monthStart->copy()->addDays($i)->toDateString();
                $slots[$day] = $this->summarizeBookings($appointments, $day);
            }
        }

        return $slots;
    }

    protected function summarizeBookings($appointments, $date)
    {
        $dayAppointments = array_filter($appointments, function ($appt) use ($date) {
            return Carbon::parse($appt['start'])->toDateString() === $date;
        });

        $counts = [
            'new' => 0,
            'review' => 0,
            'postop' => 0,
            'external_pending' => 0,
            'external_approved' => 0,
            'cancelled' => 0,
        ];

        foreach ($dayAppointments as $appt) {
            $type = $appt['extendedProps']['type'];
            $counts[$type]++;
        }

        $specializations = BkSpecializations::all();
        $remainingSlots = [];
        foreach ($specializations as $spec) {
            $active = $this->getActiveBookingsForDate($spec->name, $date);
            $remainingSlots[$spec->name] = max(0, $spec->daily_limit - $active);
        }

        return [
            'counts' => $counts,
            'appointments' => $dayAppointments,
            'remaining_slots' => $remainingSlots,
        ];
    }

    private function getUpcomingAppointments()
    {
        $today = now()->startOfDay();
        $end = $today->copy()->addDays(7)->endOfDay();

        // Get current user's branch
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $models = [
            NewAppointment::class => 'new',
            ReviewAppointment::class => 'review',
            PostOpAppointment::class => 'postop',
            ExternalApproved::class => 'external_approved',
        ];

        $appointments = collect();

        foreach ($models as $model => $type) {
            $query = $model::whereBetween('appointment_date', [$today, $end])
                ->where('reminder_cleared', false)
                ->when($userBranch, function ($q) use ($userBranch) {
                    return $q->where('hospital_branch', $userBranch);
                });

            if ($model === ExternalApproved::class) {
                $query->where('patient_notified', false);
            }

            $results = $query->get()->map(function ($appt) use ($type) {
                $appt->booking_type = $type;
                return $appt;
            });
            $appointments = $appointments->merge($results);
        }

        return $appointments->sortBy('appointment_date');
    }

    protected function sendAppointmentReminders()
    {
        $today = now()->startOfDay();
        $twoDaysAhead = $today->copy()->addDays(2)->startOfDay();
        $oneWeekAhead = $today->copy()->addDays(7)->startOfDay();
        $oneWeekAgo = $today->copy()->subWeek()->startOfDay();

        // Get current user's branch
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $queries = [
            'new' => NewAppointment::class,
            'review' => ReviewAppointment::class,
            'postop' => PostOpAppointment::class,
            'external_approved' => ExternalApproved::class,
        ];

        foreach ($queries as $type => $model) {
            // Query for appointments on the exact day
            $queryToday = $model::whereDate('appointment_date', $today)
                ->where('appointment_status', 'approved')
                ->where('reminder_cleared', false)
                ->when($userBranch, function ($q) use ($userBranch) {
                    return $q->where('hospital_branch', $userBranch);
                });

            // Query for two days ahead (for bookings made at least a week ago)
            $queryTwoDays = $model::whereDate('appointment_date', $twoDaysAhead)
                ->whereDate('created_at', '<=', $oneWeekAgo)
                ->where('appointment_status', 'approved')
                ->where('reminder_cleared', false)
                ->when($userBranch, function ($q) use ($userBranch) {
                    return $q->where('hospital_branch', $userBranch);
                });

            // Query for one week ahead
            $queryOneWeek = $model::whereDate('appointment_date', $oneWeekAhead)
                ->where('appointment_status', 'approved')
                ->where('reminder_cleared', false)
                ->when($userBranch, function ($q) use ($userBranch) {
                    return $q->where('hospital_branch', $userBranch);
                });

            // Apply patient_notified filter only for ExternalApproved
            if ($type === 'external_approved') {
                $appointmentsToday = $queryToday->where('patient_notified', false)->get();
                $appointmentsTwoDays = $queryTwoDays->where('patient_notified', false)->get();
                $appointmentsOneWeek = $queryOneWeek->where('patient_notified', false)->get();
            } else {
                $appointmentsToday = $queryToday->get();
                $appointmentsTwoDays = $queryTwoDays->get();
                $appointmentsOneWeek = $queryOneWeek->get();
            }

            // Send reminders for all applicable appointments
            foreach ([$appointmentsToday, $appointmentsTwoDays, $appointmentsOneWeek] as $appointments) {
                foreach ($appointments as $appt) {
                    if ($appt->email && $appt->email !== 'N/A') {
                        Mail::to($appt->email)->send(new AppointmentReminder($appt));
                        if ($type === 'external_approved') {
                            $appt->patient_notified = true;
                            $appt->save();
                        }
                        \Log::info("Reminder sent", [
                            'id' => $appt->id,
                            'type' => $type,
                            'appointment_date' => $appt->appointment_date,
                            'email' => $appt->email,
                            'branch' => $userBranch
                        ]);
                    } else {
                        \Log::warning("No valid email for reminder", [
                            'id' => $appt->id,
                            'type' => $type,
                            'appointment_date' => $appt->appointment_date,
                            'branch' => $userBranch
                        ]);
                    }
                }
            }
        }
    }

    public function reminders(Request $request)
    {
        $today = now()->startOfDay();
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $upcoming = $this->getUpcomingAppointments();
        $missed = $this->getMissedAppointments($today);

        $appointmentModels = [
            'new' => NewAppointment::class,
            'review' => ReviewAppointment::class,
            'postop' => PostOpAppointment::class,
            'external_approved' => ExternalApproved::class,
        ];

        // Update missed appointments to 'missed' status (only for user's branch)
        foreach ($missed as $appt) {
            if ($appt->appointment_status !== 'honoured') {
                $appt->appointment_status = 'missed';
                $appt->save();
            }
        }

        $specializations = BkSpecializations::all();

        // Calculate reminder count for cache (branch-specific)
        $models = [
            NewAppointment::class,
            ReviewAppointment::class,
            PostOpAppointment::class,
            ExternalApproved::class,
        ];

        $upcomingCount = 0;
        $missedCount = 0;

        foreach ($models as $model) {
            $query = $model::whereBetween('appointment_date', [$today, $today->copy()->addDays(7)->endOfDay()])
                ->where('reminder_cleared', false)
                ->when($userBranch, function ($q) use ($userBranch) {
                    return $q->where('hospital_branch', $userBranch);
                });

            if ($model === ExternalApproved::class) {
                $query->where('patient_notified', false);
            }
            $upcomingCount += $query->count();

            $missedCount += $model::where('appointment_date', '<', $today)
                ->whereNotIn('appointment_status', ['honoured', 'cancelled'])
                ->where('reminder_cleared', false)
                ->when($userBranch, function ($q) use ($userBranch) {
                    return $q->where('hospital_branch', $userBranch);
                })
                ->count();
        }

        $reminderCount = $upcomingCount + $missedCount;

        return view('booking.reminders', [
            'title' => 'Appointment Reminders',
            'upcoming' => $upcoming,
            'missed' => $missed,
            'specializations' => $specializations,
            'reminderCount' => $reminderCount,
            'userBranch' => $userBranch,
        ]);
    }
    public function bulkClearReminders(Request $request)
    {
        $appointmentIds = $request->input('appointment_ids', []);
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $appointmentModels = [
            'new' => NewAppointment::class,
            'review' => ReviewAppointment::class,
            'postop' => PostOpAppointment::class,
            'external_approved' => ExternalApproved::class,
        ];

        DB::beginTransaction();
        try {
            foreach ($appointmentIds as $appointmentId) {
                [$id, $type] = explode('-', $appointmentId);
                $model = $appointmentModels[$type] ?? null;
                if ($model) {
                    $appointment = $model::where('id', $id)
                        ->when($userBranch, function ($q) use ($userBranch) {
                            return $q->where('hospital_branch', $userBranch);
                        })
                        ->first();

                    if ($appointment) {
                        $appointment->reminder_cleared = true;
                        $appointment->save();
                    } else {
                        Log::warning("Appointment not found or access denied for clearing", [
                            'id' => $id,
                            'type' => $type,
                            'user_branch' => $userBranch
                        ]);
                    }
                }
            }
            Cache::forget('reminder_count_' . $userBranch);
            DB::commit();
            return redirect()->route('booking.reminders')->with('success', 'Selected reminders cleared successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to clear reminders: ' . $e->getMessage());
            return redirect()->route('booking.reminders')->with('error', 'Failed to clear reminders.');
        }
    }

    public function clearAppointment(Request $request, $id)
    {
        [$id, $type] = explode('-', $id);
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $appointmentModels = [
            'new' => NewAppointment::class,
            'review' => ReviewAppointment::class,
            'postop' => PostOpAppointment::class,
            'external_approved' => ExternalApproved::class,
        ];

        $model = $appointmentModels[$type] ?? null;
        if (!$model) {
            return redirect()->route('booking.reminders')->with('error', 'Invalid appointment type.');
        }

        $appointment = $model::where('id', $id)
            ->when($userBranch, function ($q) use ($userBranch) {
                return $q->where('hospital_branch', $userBranch);
            })
            ->first();

        if (!$appointment) {
            return redirect()->route('booking.reminders')->with('error', 'Appointment not found or access denied.');
        }

        $appointment->reminder_cleared = true;
        $appointment->save();
        Cache::forget('reminder_count_' . $userBranch);

        return redirect()->route('booking.reminders')->with('success', 'Reminder cleared successfully.');
    }

    private function getMissedAppointments($today)
    {
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $models = [
            NewAppointment::class => 'new',
            ReviewAppointment::class => 'review',
            PostOpAppointment::class => 'postop',
            ExternalApproved::class => 'external_approved',
        ];

        $appointments = collect();

        foreach ($models as $model => $type) {
            $results = $model::where('appointment_date', '<', $today)
                ->whereNotIn('appointment_status', ['honoured', 'cancelled'])
                ->where('reminder_cleared', false)
                ->when($userBranch, function ($q) use ($userBranch) {
                    return $q->where('hospital_branch', $userBranch);
                })
                ->get()
                ->map(function ($appt) use ($type) {
                    $appt->booking_type = $type;
                    return $appt;
                });
            $appointments = $appointments->merge($results);
        }

        return $appointments->sortByDesc('appointment_date');
    }

    public function addHoliday(Request $request)
    {
        $userBranch = Auth::guard('booking')->user()->hospital_branch;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
        ]);

        // Add branch to holiday if the table supports it
        if (Schema::hasColumn('holidays', 'hospital_branch')) {
            $validated['hospital_branch'] = $userBranch;
        }

        Holiday::create($validated);

        return redirect()->back()->with('success', 'Holiday added successfully.');
    }

    // Override existing methods to send notifications

    protected function getLatestAppointment($bookingType)
    {
        $model = match ($bookingType) {
            'new' => NewAppointment::class,
            'review' => ReviewAppointment::class,
            'post-op' => PostOpAppointment::class,
            default => null,
        };
        return $model ? $model::orderBy('created_at', 'desc')->first() : null;
    }


    public function searchBookingPatients(Request $request)
    {
        \Log::info("Patient search accessed", $request->all());

        $request->validate([
            'search' => 'nullable|string|max:255',
            'ajax' => 'nullable|boolean',
            'export_csv' => 'nullable|boolean',
        ]);

        $search = $request->input('search');
        $userBranch = Auth::guard('booking')->user()->hospital_branch;
        $specializations = BkSpecializations::all();

        // Build the query using BkAppointments model with specialization name
        $query = BkAppointments::query()
            ->leftJoin('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
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
            ])
            ->when($userBranch, function ($q) use ($userBranch) {
                return $q->where('bk_appointments.hospital_branch', $userBranch);
            });

        // Apply search filter only if search term is provided
        if ($search) {
            $searchPattern = '%' . $search . '%';
            $query->where(function ($q) use ($searchPattern) {
                $q->where('bk_appointments.full_name', 'LIKE', $searchPattern)
                    ->orWhere('bk_appointments.patient_number', 'LIKE', $searchPattern)
                    ->orWhere('bk_appointments.email', 'LIKE', $searchPattern)
                    ->orWhere('bk_appointments.phone', 'LIKE', $searchPattern)
                    ->orWhere('bk_specializations.name', 'LIKE', $searchPattern);
            });

            \Log::info("Search applied with term: {$search} for branch: {$userBranch}");

            try {
                $appointments = $query->orderBy('bk_appointments.appointment_date', 'desc')->get();
                \Log::info("Patient search results fetched: " . $appointments->count() . " for branch: {$userBranch}");
            } catch (\Exception $e) {
                \Log::error("Patient search query failed: " . $e->getMessage(), [
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                    'user_branch' => $userBranch,
                ]);
                if ($request->has('ajax')) {
                    return response()->json(['error' => 'Error loading search results: ' . $e->getMessage()], 500);
                }
                return redirect()->route('booking.dashboard')->with('error', 'Failed to load search results.');
            }
        } else {
            // If no search term, return empty results
            $appointments = collect([]);
            \Log::info("No search term provided, returning empty results");
        }

        // Handle AJAX request
        if ($request->has('ajax')) {
            try {
                $tableView = view('booking.tables.search', [
                    'appointments' => $appointments,
                    'specializations' => $specializations,
                ])->render();
                return response()->json(['table' => $tableView]);
            } catch (\Exception $e) {
                \Log::error("AJAX rendering failed for patient search: " . $e->getMessage());
                return response()->json(['error' => 'Error rendering search results.'], 500);
            }
        }

        // Export as CSV
        if ($request->has('export_csv')) {
            $filename = "patient_search_{$userBranch}_" . now()->format('Ymd_His') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            if (!$search) {
                $callback = function () {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['No search term provided']);
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }

            if ($appointments->isEmpty()) {
                $callback = function () {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['No records found']);
                };
                return response()->stream($callback, 200, $headers);
            }

            // Define the columns to export
            $columns = [
                'appointment_number',
                'full_name',
                'patient_number',
                'email',
                'phone',
                'appointment_date',
                'appointment_time',
                'specialization',
                'doctor',
                'booking_type',
                'appointment_status',
                'doctor_comments',
                'hospital_branch',
            ];

            $callback = function () use ($appointments, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($appointments as $appointment) {
                    $row = [];
                    foreach ($columns as $col) {
                        $row[$col] = $appointment->$col;
                    }
                    fputcsv($file, $row);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        // Prepare data for view
        $data = [
            'title' => 'Patient Search - ' . ucfirst($userBranch),
            'appointments' => $appointments,
            'userBranch' => $userBranch,
            'specializations' => $specializations,
        ];

        return view('booking.search', $data);
    }
    public function booked_branch(Request $request, $branch)
    {
        // Get current user's details
        $user = Auth::guard('booking')->user();
        $userBranch = $user->hospital_branch;
        $userRole = $user->role;
        $isSuperadmin = ($user && $user->role === 'superadmin');
        $isAdmin = ($user && $user->role === 'admin');

        \Log::info("User details:", [
            'user_id' => $user->id,
            'user_branch' => $userBranch,
            'user_role' => $userRole
        ]);

        // Check what branches actually exist in the bk_appointments table
        $actualBranches = DB::table('bk_appointments')
            ->select('hospital_branch')
            ->distinct()
            ->whereNotNull('hospital_branch')
            ->pluck('hospital_branch')
            ->toArray();

        \Log::info("Actual branches found in bk_appointments table:", $actualBranches);

        // Get total records per branch for debugging
        $branchCounts = DB::table('bk_appointments')
            ->select('hospital_branch', DB::raw('COUNT(*) as count'))
            ->groupBy('hospital_branch')
            ->get()
            ->pluck('count', 'hospital_branch')
            ->toArray();

        \Log::info("Records per branch:", $branchCounts);

        // Define branch mapping (URL-friendly to database values)
        $branchMapping = [
            'kijabe' => 'kijabe',
            'naivasha' => 'naivasha',
            'westlands' => 'westlands',
            'marira' => 'marira'
        ];

        $urlBranch = strtolower($branch);
        \Log::info("URL branch (lowercase): {$urlBranch}");

        // Validate branch parameter
        if (!array_key_exists($urlBranch, $branchMapping)) {
            \Log::warning("Invalid branch provided: {$branch}. Valid options: " . implode(', ', array_keys($branchMapping)));
            return redirect()->route('booking.dashboard')
                ->with('error', 'Invalid hospital branch. Available: ' . implode(', ', array_keys($branchMapping)));
        }

        $branchName = $branchMapping[$urlBranch];
        \Log::info("Mapped database branch name: {$branchName}");

        // Check if this branch actually has data
        if (!in_array($branchName, $actualBranches)) {
            \Log::warning("No data found for branch: {$branchName}. Available branches with data: " . implode(', ', $actualBranches));
            // Continue anyway - might be a valid branch with no appointments yet
        }

        // Access control
        if ($userRole === 'superadmin') {
            \Log::info("Admin access granted for branch: {$branchName}");
        } else {
            // Non-admin users can only see their own branch
            if ($userBranch !== $branchName) {
                \Log::warning("Access denied: User from {$userBranch} trying to access {$branchName}");
                return redirect()->route('booking.dashboard')
                    ->with('error', 'Access denied. You can only view your branch data.');
            }
            \Log::info("User access granted for own branch: {$branchName}");
        }

        try {
            // Build the query for bk_appointments table
            $query = DB::table('bk_appointments')
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
                    'appointment_status',
                    'doctor_comments',
                    'hospital_branch',
                    'created_at',
                    'rescheduled',
                    'reminder_cleared',
                    'visit_status',
                    'visit_date',
                    'hmis_visit_date',
                    'hmis_department',
                    'hmis_appointment_purpose',
                    'hmis_doctor',
                    'hmis_county',
                    'hmis_visit_status'
                ])
                ->where('hospital_branch', $branchName)
                ->orderBy('appointment_date', 'desc')
                ->orderBy('appointment_time', 'asc');

            \Log::info("SQL Query: " . $query->toSql());
            \Log::info("Query Bindings: ", $query->getBindings());

            // Execute the query
            $appointments = $query->get();

            \Log::info("Appointments fetched for branch {$branchName}: " . $appointments->count());

            // Debug the results
            if ($appointments->isNotEmpty()) {
                // Log booking type distribution
                $bookingTypes = $appointments->groupBy('booking_type')->map->count();
                \Log::info("Booking type distribution:", $bookingTypes->toArray());

                // Log appointment status distribution  
                $statusDistribution = $appointments->groupBy('appointment_status')->map->count();
                \Log::info("Appointment status distribution:", $statusDistribution->toArray());

                // Sample appointments for debugging
                $sampleAppointments = $appointments->take(3)->map(function ($apt) {
                    return [
                        'id' => $apt->id,
                        'appointment_number' => $apt->appointment_number,
                        'full_name' => $apt->full_name,
                        'hospital_branch' => $apt->hospital_branch,
                        'booking_type' => $apt->booking_type,
                        'appointment_date' => $apt->appointment_date,
                        'appointment_status' => $apt->appointment_status
                    ];
                });
                \Log::info("Sample appointments:", $sampleAppointments->toArray());
            } else {
                \Log::warning("No appointments found for branch: {$branchName}");

                // Check if any appointments exist at all
                $totalAppointments = DB::table('bk_appointments')->count();
                \Log::info("Total appointments in database: {$totalAppointments}");

                if ($totalAppointments > 0) {
                    \Log::info("Appointments exist but none for branch: {$branchName}");
                    \Log::info("This might be normal if this branch has no appointments yet.");
                } else {
                    \Log::warning("No appointments found in entire bk_appointments table!");
                }
            }

        } catch (\Exception $e) {
            \Log::error("Query failed for branch {$branchName}: " . $e->getMessage(), [
                'user_branch' => $userBranch,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('booking.dashboard')
                ->with('error', 'Failed to load appointments: ' . $e->getMessage());
        }

        // Handle CSV export
        if ($request->has('export_csv')) {
            $filename = "{$urlBranch}_appointments_" . now()->format('Ymd_His') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            if ($appointments->isEmpty()) {
                $callback = function () use ($branchName) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Message']);
                    fputcsv($file, ["No appointments found for branch: {$branchName}"]);
                    fputcsv($file, ['Total branches with data: ' . implode(', ', array_keys($branchCounts ?? []))]);
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }

            $columns = [
                'S.No',
                'Appointment No.',
                'Patient Name',
                'Patient No.',
                'Email',
                'Phone',
                'Appointment Date',
                'Appointment Time',
                'Doctor',
                'Specialization',
                'Booking Type',
                'Appointment Status',
                'Hospital Branch',
                'Doctor Comments',
                'Rescheduled',
                'Visit Status',
                'Visit Date',
                'HMIS Visit Date',
                'HMIS Department',
                'HMIS Doctor',
                'HMIS County',
                'HMIS Visit Status'
            ];

            $callback = function () use ($appointments, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($appointments as $index => $appointment) {
                    $row = [
                        $index + 1,
                        $appointment->appointment_number ?? '-',
                        $appointment->full_name ?? '-',
                        $appointment->patient_number ?? '-',
                        $appointment->email ?? '-',
                        $appointment->phone ?? '-',
                        $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '-',
                        $appointment->appointment_time ?? '-',
                        $appointment->doctor ?? '-',
                        $appointment->specialization ?? '-',
                        $appointment->booking_type ?? '-',
                        $appointment->appointment_status ?? '-',
                        $appointment->hospital_branch ?? '-',
                        $appointment->doctor_comments ?? '-',
                        $appointment->rescheduled ? 'Yes' : 'No',
                        $appointment->visit_status ? 'Completed' : 'Pending',
                        $appointment->visit_date ?? '-',
                        $appointment->hmis_visit_date ?? '-',
                        $appointment->hmis_department ?? '-',
                        $appointment->hmis_doctor ?? '-',
                        $appointment->hmis_county ?? '-',
                        $appointment->hmis_visit_status ?? '-',
                    ];
                    fputcsv($file, $row);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        // Get specializations (assuming you have a specializations table)
        $specializations = [];
        try {
            // Try to get from bk_specialization table first
            $specializations = DB::table('bk_specialization')->get();
            \Log::info("Fetched specializations from bk_specialization table: " . $specializations->count());
        } catch (\Exception $e) {
            \Log::warning("Could not fetch specializations from bk_specialization table: " . $e->getMessage());

            // Fallback: Get unique specializations from appointments
            $rawSpecializations = DB::table('bk_appointments')
                ->select('specialization')
                ->distinct()
                ->whereNotNull('specialization')
                ->get();

            // Transform to consistent structure with 'name' property
            $specializations = $rawSpecializations->map(function ($item) {
                return (object) ['name' => $item->specialization];
            });

            \Log::info("Fetched specializations from bk_appointments table: " . $specializations->count());
        }

        // Ensure we always have a collection of objects with 'name' property
        $specializations = collect($specializations)->map(function ($item) {
            // If it already has 'name', keep it. Otherwise, create it from 'specialization'
            if (isset($item->name)) {
                return $item;
            } else {
                return (object) ['name' => $item->specialization ?? ''];
            }
        });

        // Prepare data for the view
        $data = [
            'title' => ucfirst($branchName) . ' Patient Records',
            'appointments' => $appointments,
            'branch' => $urlBranch,
            'actualBranch' => $branchName,
            'specializations' => $specializations,
            'status' => 'branch',
            'hospitalBranches' => $this->getHospitalBranchEnumValues(),
            'userBranch' => $userBranch,
            'userRole' => $userRole,
            'appointmentsCount' => $appointments->count(),
            'branchCounts' => $branchCounts ?? [],
        ];

        \Log::info("=== BRANCH DASHBOARD DEBUG END ===");
        \Log::info("Final data being passed to view:", [
            'title' => $data['title'],
            'appointments_count' => $appointments->count(),
            'branch' => $data['branch'],
            'actual_branch' => $branchName,
            'user_branch' => $userBranch,
            'user_role' => $userRole,
            'total_branches_with_data' => count($actualBranches)
        ]);

        return view('booking.branch', $data);
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

