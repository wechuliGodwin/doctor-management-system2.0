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
use App\Models\BkTracing;
use App\Models\BkSpecializationsGroup;
use App\Models\BkRescheduledAppointments;
use App\Models\BkSpecializationDateLimits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

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
        return view('booking.internal_booking_form', compact('bk_specializations',));
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

    public function reports(Request $request)
    {
        $user = Auth::guard('booking')->user();
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';
        $userBranch = $user ? $user->hospital_branch : null;
        $selectedBranch = null;

        // Validation rules
        $validationRules = [
            'ajax' => 'nullable|boolean',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'time_period' => 'nullable|in:day,month,year,custom',
            'specialization' => 'nullable|exists:bk_specializations,id',
            'doctor' => 'nullable|exists:bk_doctor,id',
        ];

        if ($isSuperadmin) {
            $validationRules['branch'] = 'nullable|in:kijabe,westlands,naivasha,marira';
        }

        $request->validate($validationRules);

        // Determine selected branch
        if ($isSuperadmin) {
            $selectedBranch = $request->input('branch');
        } else {
            $selectedBranch = $userBranch;
        }

        // Determine date range based on time_period
        $timePeriod = $request->input('time_period', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($timePeriod === 'day') {
            $startDate = Carbon::today()->startOfDay()->toDateString();
            $endDate = Carbon::today()->endOfDay()->toDateString();
        } elseif ($timePeriod === 'month') {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        } elseif ($timePeriod === 'year') {
            $startDate = Carbon::now()->startOfYear()->toDateString();
            $endDate = Carbon::now()->endOfYear()->toDateString();
        } elseif ($timePeriod === 'custom' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay()->toDateString();
            $endDate = Carbon::parse($endDate)->endOfDay()->toDateString();
        } else {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        }

        // Fetch hospital branches and specializations
        $hospitalBranches = $this->getHospitalBranchEnumValues();
        $specializations = $isSuperadmin
            ? BkSpecializations::select('id', 'name', 'hospital_branch')
            ->orderBy('name')
            ->get()
            ->map(function ($spec) {
                $spec->display_name = $spec->name . ' (' . ucfirst($spec->hospital_branch) . ')';
                return $spec;
            })
            : BkSpecializations::where('hospital_branch', $userBranch)
            ->orderBy('name')
            ->get()
            ->map(function ($spec) {
                $spec->display_name = $spec->name;
                return $spec;
            });

        // Fetch doctors
        $doctors = $isSuperadmin
            ? BkDoctor::select('id', 'doctor_name', 'hospital_branch')
            ->orderBy('doctor_name')
            ->get()
            ->map(function ($doctor) {
                $doctor->display_name = $doctor->doctor_name . ' - ' . ucfirst($doctor->hospital_branch);
                return $doctor;
            })
            : BkDoctor::where('hospital_branch', $userBranch)
            ->orderBy('doctor_name')
            ->get()
            ->map(function ($doctor) {
                $doctor->display_name = $doctor->doctor_name;
                return $doctor;
            });

        // Fetch specialization performance data
        $query = BkSpecializations::query()
            ->select([
                'bk_specializations.id as specialization_id',
                'bk_specializations.name as specialization_name',
                DB::raw("(
                (SELECT COUNT(*) FROM bk_appointments a 
                 WHERE a.specialization = bk_specializations.id
                 AND a.appointment_status != 'rescheduled'
                 AND a.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . ")
                +
                (SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . ")
                +
                (SELECT COUNT(*) FROM external_pending_approvals epa 
                 WHERE epa.specialization = bk_specializations.name
                 AND epa.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . ")
                +
                (SELECT COUNT(*) FROM cancelled_appointments c 
                 WHERE c.specialization = bk_specializations.name
                 AND c.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch') ? " AND c.hospital_branch = '$selectedBranch'" : "") . ")
            ) as total_appointments"),
                DB::raw("(
                (SELECT COUNT(*) FROM bk_appointments a 
                 WHERE a.specialization = bk_specializations.id
                 AND a.appointment_status = 'pending'
                 AND a.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . ")
                +
                (SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name
                 AND e.appointment_status = 'pending'
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . ")
            ) as confirmed_pending"),
                DB::raw("(
                (SELECT COUNT(*) FROM bk_appointments a 
                 WHERE a.specialization = bk_specializations.id
                 AND a.appointment_status IN ('honoured', 'early', 'late')
                 AND a.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . ")
                +
                (SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name
                 AND e.appointment_status IN ('honoured', 'early', 'late')
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . ")
            ) as patients_seen"),
                DB::raw("(
                (SELECT COUNT(*) FROM bk_appointments a 
                 WHERE a.specialization = bk_specializations.id
                 AND a.appointment_status = 'missed'
                 AND a.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . ")
                +
                (SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name
                 AND e.appointment_status = 'missed'
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . ")
            ) as missed"),
                DB::raw("(SELECT COUNT(*) FROM cancelled_appointments c 
                 WHERE c.specialization = bk_specializations.name 
                 AND c.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch') ? " AND c.hospital_branch = '$selectedBranch'" : "") . ") as cancelled"),
                DB::raw("(SELECT COUNT(*) 
                 FROM bk_rescheduled_appointments r 
                 JOIN bk_appointments a ON r.appointment_id = a.id 
                 WHERE a.specialization = bk_specializations.id 
                 AND r.created_at BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . ") as rescheduled"),
                DB::raw("(SELECT COUNT(*) FROM external_pending_approvals epa 
                 WHERE epa.specialization = bk_specializations.name 
                 AND epa.status = 'pending' 
                 AND epa.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . ") as pending_external_approvals"),
                DB::raw("(SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name 
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . ") as external_approved"),
                DB::raw("(
                (SELECT COUNT(*) FROM bk_appointments a 
                 WHERE a.specialization = bk_specializations.id
                 AND a.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . "
                 AND EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = a.id 
                     AND t.status = 'contacted'
                     AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = a.id)
                 ))
                +
                (SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . "
                 AND EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = e.id 
                     AND t.status = 'contacted'
                     AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = e.id)
                 ))
                +
                (SELECT COUNT(*) FROM cancelled_appointments c 
                 WHERE c.specialization = bk_specializations.name
                 AND c.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch') ? " AND c.hospital_branch = '$selectedBranch'" : "") . "
                 AND EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = c.id 
                     AND t.status = 'contacted'
                     AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = c.id)
                 ))
            ) as traced_contacted"),
                DB::raw("(
                (SELECT COUNT(*) FROM bk_appointments a 
                 WHERE a.specialization = bk_specializations.id
                 AND a.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . "
                 AND EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = a.id 
                     AND t.status = 'no response'
                     AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = a.id)
                 ))
                +
                (SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . "
                 AND EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = e.id 
                     AND t.status = 'no response'
                     AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = e.id)
                 ))
                +
                (SELECT COUNT(*) FROM cancelled_appointments c 
                 WHERE c.specialization = bk_specializations.name
                 AND c.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch') ? " AND c.hospital_branch = '$selectedBranch'" : "") . "
                 AND EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = c.id 
                     AND t.status = 'no response'
                     AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = c.id)
                 ))
            ) as traced_no_response"),
                DB::raw("(
                (SELECT COUNT(*) FROM bk_appointments a 
                 WHERE a.specialization = bk_specializations.id
                 AND a.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND a.hospital_branch = '$selectedBranch'" : "") . "
                 AND NOT EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = a.id
                 ))
                +
                (SELECT COUNT(*) FROM external_approveds e 
                 WHERE e.specialization = bk_specializations.name
                 AND e.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch ? " AND e.hospital_branch = '$selectedBranch'" : "") .
                    (!$isSuperadmin && $userBranch !== 'kijabe' ? " AND 1 = 0" : "") . "
                 AND NOT EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = e.id
                 ))
                +
                (SELECT COUNT(*) FROM cancelled_appointments c 
                 WHERE c.specialization = bk_specializations.name
                 AND c.appointment_date BETWEEN '$startDate' AND '$endDate'" .
                    ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch') ? " AND c.hospital_branch = '$selectedBranch'" : "") . "
                 AND NOT EXISTS (
                     SELECT 1 FROM bk_tracing t 
                     WHERE t.appointment_id = c.id
                 ))
            ) as traced_other"),
            ]);

        if ($selectedBranch) {
            $query->where('bk_specializations.hospital_branch', $selectedBranch);
        }

        $selectedSpecialization = $request->input('specialization');
        if ($selectedSpecialization) {
            $query->where('bk_specializations.id', $selectedSpecialization);
        }

        try {
            $specializationData = $query->get();
            Log::info('Specialization report data fetched', [
                'count' => $specializationData->count(),
                'branch' => $selectedBranch ?? 'all',
                'specialization' => $selectedSpecialization ?? 'all',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'time_period' => $timePeriod,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch specialization report data: ' . $e->getMessage(), [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('booking.reports')->with('error', 'Failed to load report data: ' . $e->getMessage());
        }

        // Calculate totals for tracing status
        $totals = [
            'traced_contacted' => $specializationData->sum('traced_contacted'),
            'traced_no_response' => $specializationData->sum('traced_no_response'),
            'traced_other' => $specializationData->sum('traced_other'),
        ];

        // Fetch hospital branch bookings data (for superadmins and admins only)
        $branchData = collect();
        if ($isSuperadmin || $isAdmin) {
            $branchQuery = BkAppointments::query()
                ->select([
                    'hospital_branch',
                    DB::raw('COUNT(*) as total_bookings'),
                    DB::raw("SUM(CASE WHEN EXISTS (
                    SELECT 1 FROM bk_tracing t 
                    WHERE t.appointment_id = bk_appointments.id 
                    AND t.status = 'contacted'
                    AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = bk_appointments.id)
                ) THEN 1 ELSE 0 END) as traced_contacted"),
                    DB::raw("SUM(CASE WHEN EXISTS (
                    SELECT 1 FROM bk_tracing t 
                    WHERE t.appointment_id = bk_appointments.id 
                    AND t.status = 'no response'
                    AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = bk_appointments.id)
                ) THEN 1 ELSE 0 END) as traced_no_response"),
                    DB::raw("SUM(CASE WHEN NOT EXISTS (
                    SELECT 1 FROM bk_tracing t 
                    WHERE t.appointment_id = bk_appointments.id
                ) THEN 1 ELSE 0 END) as traced_other"),
                ])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('appointment_status', '!=', 'rescheduled')
                ->when($isAdmin && !$isSuperadmin, function ($q) use ($userBranch) {
                    $q->where('hospital_branch', $userBranch);
                })
                ->groupBy('hospital_branch')
                ->union(
                    ExternalApproved::query()
                        ->select([
                            'hospital_branch',
                            DB::raw('COUNT(*) as total_bookings'),
                            DB::raw("SUM(CASE WHEN EXISTS (
                            SELECT 1 FROM bk_tracing t 
                            WHERE t.appointment_id = external_approveds.id 
                            AND t.status = 'contacted'
                            AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = external_approveds.id)
                        ) THEN 1 ELSE 0 END) as traced_contacted"),
                            DB::raw("SUM(CASE WHEN EXISTS (
                            SELECT 1 FROM bk_tracing t 
                            WHERE t.appointment_id = external_approveds.id 
                            AND t.status = 'no response'
                            AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = external_approveds.id)
                        ) THEN 1 ELSE 0 END) as traced_no_response"),
                            DB::raw("SUM(CASE WHEN NOT EXISTS (
                            SELECT 1 FROM bk_tracing t 
                            WHERE t.appointment_id = external_approveds.id
                        ) THEN 1 ELSE 0 END) as traced_other"),
                        ])
                        ->whereBetween('appointment_date', [$startDate, $endDate])
                        ->when($isAdmin && !$isSuperadmin, function ($q) use ($userBranch) {
                            $q->where('hospital_branch', $userBranch);
                        })
                        ->when(!$isSuperadmin && $userBranch !== 'kijabe', function ($q) {
                            $q->whereRaw('1 = 0');
                        })
                        ->groupBy('hospital_branch')
                )
                ->union(
                    CancelledAppointment::query()
                        ->select([
                            'hospital_branch',
                            DB::raw('COUNT(*) as total_bookings'),
                            DB::raw("SUM(CASE WHEN EXISTS (
                            SELECT 1 FROM bk_tracing t 
                            WHERE t.appointment_id = cancelled_appointments.id 
                            AND t.status = 'contacted'
                            AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = cancelled_appointments.id)
                        ) THEN 1 ELSE 0 END) as traced_contacted"),
                            DB::raw("SUM(CASE WHEN EXISTS (
                            SELECT 1 FROM bk_tracing t 
                            WHERE t.appointment_id = cancelled_appointments.id 
                            AND t.status = 'no response'
                            AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = cancelled_appointments.id)
                        ) THEN 1 ELSE 0 END) as traced_no_response"),
                            DB::raw("SUM(CASE WHEN NOT EXISTS (
                            SELECT 1 FROM bk_tracing t 
                            WHERE t.appointment_id = cancelled_appointments.id
                        ) THEN 1 ELSE 0 END) as traced_other"),
                        ])
                        ->whereBetween('appointment_date', [$startDate, $endDate])
                        ->when($isAdmin && !$isSuperadmin, function ($q) use ($userBranch) {
                            $q->where('hospital_branch', $userBranch);
                        })
                        ->when(DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch'), function ($q) {
                            $q->groupBy('hospital_branch');
                        })
                );

            if ($selectedSpecialization) {
                $specName = BkSpecializations::where('id', $selectedSpecialization)->value('name');
                $branchQuery = BkAppointments::query()
                    ->select([
                        'hospital_branch',
                        DB::raw('COUNT(*) as total_bookings'),
                        DB::raw("SUM(CASE WHEN EXISTS (
                        SELECT 1 FROM bk_tracing t 
                        WHERE t.appointment_id = bk_appointments.id 
                        AND t.status = 'contacted'
                        AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = bk_appointments.id)
                    ) THEN 1 ELSE 0 END) as traced_contacted"),
                        DB::raw("SUM(CASE WHEN EXISTS (
                        SELECT 1 FROM bk_tracing t 
                        WHERE t.appointment_id = bk_appointments.id 
                        AND t.status = 'no response'
                        AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = bk_appointments.id)
                    ) THEN 1 ELSE 0 END) as traced_no_response"),
                        DB::raw("SUM(CASE WHEN NOT EXISTS (
                        SELECT 1 FROM bk_tracing t 
                        WHERE t.appointment_id = bk_appointments.id
                    ) THEN 1 ELSE 0 END) as traced_other"),
                    ])
                    ->where('specialization', $specName)
                    ->where('appointment_status', '!=', 'rescheduled')
                    ->whereBetween('appointment_date', [$startDate, $endDate])
                    ->when($isAdmin && !$isSuperadmin, function ($q) use ($userBranch) {
                        $q->where('hospital_branch', $userBranch);
                    })
                    ->groupBy('hospital_branch')
                    ->union(
                        ExternalApproved::query()
                            ->select([
                                'hospital_branch',
                                DB::raw('COUNT(*) as total_bookings'),
                                DB::raw("SUM(CASE WHEN EXISTS (
                                SELECT 1 FROM bk_tracing t 
                                WHERE t.appointment_id = external_approveds.id 
                                AND t.status = 'contacted'
                                AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = external_approveds.id)
                            ) THEN 1 ELSE 0 END) as traced_contacted"),
                                DB::raw("SUM(CASE WHEN EXISTS (
                                SELECT 1 FROM bk_tracing t 
                                WHERE t.appointment_id = external_approveds.id 
                                AND t.status = 'no response'
                                AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = external_approveds.id)
                            ) THEN 1 ELSE 0 END) as traced_no_response"),
                                DB::raw("SUM(CASE WHEN NOT EXISTS (
                                SELECT 1 FROM bk_tracing t 
                                WHERE t.appointment_id = external_approveds.id
                            ) THEN 1 ELSE 0 END) as traced_other"),
                            ])
                            ->where('specialization', $specName)
                            ->whereBetween('appointment_date', [$startDate, $endDate])
                            ->when($isAdmin && !$isSuperadmin, function ($q) use ($userBranch) {
                                $q->where('hospital_branch', $userBranch);
                            })
                            ->when(!$isSuperadmin && $userBranch !== 'kijabe', function ($q) {
                                $q->whereRaw('1 = 0');
                            })
                            ->groupBy('hospital_branch')
                    )
                    ->union(
                        CancelledAppointment::query()
                            ->select([
                                'hospital_branch',
                                DB::raw('COUNT(*) as total_bookings'),
                                DB::raw("SUM(CASE WHEN EXISTS (
                                SELECT 1 FROM bk_tracing t 
                                WHERE t.appointment_id = cancelled_appointments.id 
                                AND t.status = 'contacted'
                                AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = cancelled_appointments.id)
                            ) THEN 1 ELSE 0 END) as traced_contacted"),
                                DB::raw("SUM(CASE WHEN EXISTS (
                                SELECT 1 FROM bk_tracing t 
                                WHERE t.appointment_id = cancelled_appointments.id 
                                AND t.status = 'no response'
                                AND t.tracing_date = (SELECT MAX(tracing_date) FROM bk_tracing WHERE appointment_id = cancelled_appointments.id)
                            ) THEN 1 ELSE 0 END) as traced_no_response"),
                                DB::raw("SUM(CASE WHEN NOT EXISTS (
                                SELECT 1 FROM bk_tracing t 
                                WHERE t.appointment_id = cancelled_appointments.id
                            ) THEN 1 ELSE 0 END) as traced_other"),
                            ])
                            ->where('specialization', $specName)
                            ->whereBetween('appointment_date', [$startDate, $endDate])
                            ->when($isAdmin && !$isSuperadmin, function ($q) use ($userBranch) {
                                $q->where('hospital_branch', $userBranch);
                            })
                            ->when(DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch'), function ($q) {
                                $q->groupBy('hospital_branch');
                            })
                    );
            }

            try {
                $branchData = $branchQuery->get()->groupBy('hospital_branch')->map(function ($group) {
                    return (object) [
                        'hospital_branch' => $group->first()->hospital_branch,
                        'total_bookings' => $group->sum('total_bookings'),
                        'traced_contacted' => $group->sum('traced_contacted'),
                        'traced_no_response' => $group->sum('traced_no_response'),
                        'traced_other' => $group->sum('traced_other'),
                    ];
                })->values();
                Log::info('Branch report data fetched', [
                    'count' => $branchData->count(),
                    'specialization' => $selectedSpecialization ?? 'all',
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'time_period' => $timePeriod,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to fetch branch report data: ' . $e->getMessage(), [
                    'sql' => $branchQuery->toSql(),
                    'bindings' => $branchQuery->getBindings(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $branchData = collect();
            }
        }

        // Fetch daily booking rate data
        $dailyBookingQuery = DB::query()
            ->select([
                DB::raw('DATE(appointment_date) as booking_date'),
                DB::raw('COUNT(*) as total_bookings'),
            ])
            ->fromSub(function ($query) use ($startDate, $endDate, $selectedBranch, $selectedSpecialization, $isSuperadmin, $userBranch) {
                $query->from('bk_appointments')
                    ->select('appointment_date')
                    ->whereBetween('appointment_date', [$startDate, $endDate])
                    ->where('appointment_status', '!=', 'rescheduled');
                if ($selectedBranch) {
                    $query->where('hospital_branch', $selectedBranch);
                }
                if ($selectedSpecialization) {
                    $query->where('specialization', $selectedSpecialization);
                }
                $query->unionAll(
                    ExternalApproved::query()
                        ->select('appointment_date')
                        ->whereBetween('appointment_date', [$startDate, $endDate])
                        ->whereRaw(!$isSuperadmin && $userBranch !== 'kijabe' ? '1 = 0' : '1 = 1')
                        ->when($selectedBranch, function ($q) use ($selectedBranch) {
                            $q->where('hospital_branch', $selectedBranch);
                        })
                        ->when($selectedSpecialization, function ($q) use ($selectedSpecialization) {
                            $specName = BkSpecializations::where('id', $selectedSpecialization)->value('name');
                            $q->where('specialization', $specName);
                        })
                )
                    ->unionAll(
                        ExternalPendingApproval::query()
                            ->select('appointment_date')
                            ->whereBetween('appointment_date', [$startDate, $endDate])
                            ->whereRaw(!$isSuperadmin && $userBranch !== 'kijabe' ? '1 = 0' : '1 = 1')
                            ->when($selectedSpecialization, function ($q) use ($selectedSpecialization) {
                                $specName = BkSpecializations::where('id', $selectedSpecialization)->value('name');
                                $q->where('specialization', $specName);
                            })
                    )
                    ->unionAll(
                        CancelledAppointment::query()
                            ->select('appointment_date')
                            ->whereBetween('appointment_date', [$startDate, $endDate])
                            ->when($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch'), function ($q) use ($selectedBranch) {
                                $q->where('hospital_branch', $selectedBranch);
                            })
                            ->when($selectedSpecialization, function ($q) use ($selectedSpecialization) {
                                $specName = BkSpecializations::where('id', $selectedSpecialization)->value('name');
                                $q->where('specialization', $specName);
                            })
                    );
            }, 'combined')
            ->groupBy(DB::raw('DATE(appointment_date)'))
            ->orderBy('booking_date');

        try {
            $dailyBookingDataRaw = $dailyBookingQuery->get();
            Log::info('Daily booking rate data fetched', [
                'count' => $dailyBookingDataRaw->count(),
                'branch' => $selectedBranch ?? 'all',
                'specialization' => $selectedSpecialization ?? 'all',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // Generate all dates in the range to ensure continuous data
            $dateRange = collect();
            $currentDate = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            while ($currentDate->lte($end)) {
                $dateRange->push($currentDate->toDateString());
                $currentDate->addDay();
            }

            // Map booking data to dates, filling in zeros for missing dates
            $dailyBookingData = $dateRange->map(function ($date) use ($dailyBookingDataRaw) {
                $record = $dailyBookingDataRaw->firstWhere('booking_date', $date);
                return [
                    'booking_date' => $date,
                    'total_bookings' => $record ? (int)$record->total_bookings : 0,
                ];
            });

            $dailyBookingChartData = [
                'labels' => $dailyBookingData->pluck('booking_date')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Daily Bookings',
                        'data' => $dailyBookingData->pluck('total_bookings')->toArray(),
                        'borderColor' => '#007bff',
                        'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch daily booking rate data: ' . $e->getMessage(), [
                'sql' => $dailyBookingQuery->toSql(),
                'bindings' => $dailyBookingQuery->getBindings(),
                'trace' => $e->getTraceAsString(),
            ]);
            $dailyBookingChartData = [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Daily Bookings',
                        'data' => [],
                        'borderColor' => '#007bff',
                        'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
            ];
        }

        // Prepare specialization chart data (top 10 specializations by total appointments)
        $chartSpecializationData = $selectedSpecialization ? $specializationData : $specializationData->sortByDesc('total_appointments')->take(10);
        $chartData = [
            'labels' => $selectedSpecialization ? [$specializationData->first()->specialization_name ?? 'Selected Specialization'] : $chartSpecializationData->pluck('specialization_name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Confirmed (Pending)',
                    'data' => $chartSpecializationData->pluck('confirmed_pending')->toArray(),
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Patients Seen',
                    'data' => $chartSpecializationData->pluck('patients_seen')->toArray(),
                    'backgroundColor' => '#17a2b8',
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Missed',
                    'data' => $chartSpecializationData->pluck('missed')->toArray(),
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Cancelled',
                    'data' => $chartSpecializationData->pluck('cancelled')->toArray(),
                    'backgroundColor' => '#ffc107',
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Rescheduled',
                    'data' => $chartSpecializationData->pluck('rescheduled')->toArray(),
                    'backgroundColor' => '#6f42c1',
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Pending External Approvals',
                    'data' => $chartSpecializationData->pluck('pending_external_approvals')->toArray(),
                    'backgroundColor' => '#fd7e14',
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'External Approved',
                    'data' => $chartSpecializationData->pluck('external_approved')->toArray(),
                    'backgroundColor' => '#20c997',
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
            ],
        ];

        // Prepare branch chart data (for superadmins only)
        $branchChartData = [
            'labels' => $branchData->pluck('hospital_branch')->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Bookings',
                    'data' => $branchData->pluck('total_bookings')->toArray(),
                    'backgroundColor' => [
                        '#007bff', // Blue
                        '#28a745', // Green
                        '#dc3545', // Red
                        '#ffc107', // Yellow
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
            ],
        ];

        // Prepare data for the view
        $data = [
            'title' => 'Specialization Performance Reports',
            'specializationData' => $specializationData,
            'branchData' => $branchData,
            'chartData' => $chartData,
            'branchChartData' => $branchChartData,
            'dailyBookingChartData' => $dailyBookingChartData,
            'hospitalBranches' => $hospitalBranches,
            'specializations' => $specializations,
            'doctors' => $doctors,
            'selectedBranch' => $selectedBranch,
            'selectedSpecialization' => $selectedSpecialization,
            'isSuperadmin' => $isSuperadmin,
            'isAdmin' => $isAdmin,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'timePeriod' => $timePeriod,
            'totals' => $totals,
        ];

        return view('booking.reports', $data);
    }
    public function detailedReport(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        Log::info('Starting detailedReport', ['selected_doctor' => $request->input('doctor')]);

        // Authenticate user
        $user = Auth::guard('booking')->user();
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';
        $userBranch = $user ? $user->hospital_branch : null;

        // Get request inputs
        $timePeriod = $request->input('time_period', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $selectedSpecialization = $request->input('specialization');
        $status = $request->input('status', 'all');
        $selectedBranch = $isSuperadmin ? $request->input('branch', $userBranch) : $userBranch;
        $selectedDoctor = $request->input('doctor');
        $bookingType = $request->input('booking_type');
        $tracingStatus = $request->input('tracing_status');
        $showReport = $request->has('generate_report');
        $isAjax = $request->has('ajax');
        $exportCsv = $request->has('export_csv');

        // Set date range
        if ($timePeriod === 'day') {
            $startDate = Carbon::today()->startOfDay()->toDateString();
            $endDate = Carbon::today()->endOfDay()->toDateString();
        } elseif ($timePeriod === 'month') {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        } elseif ($timePeriod === 'year') {
            $startDate = Carbon::now()->startOfYear()->toDateString();
            $endDate = Carbon::now()->endOfYear()->toDateString();
        } elseif ($timePeriod === 'custom' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay()->toDateString();
            $endDate = Carbon::parse($endDate)->endOfDay()->toDateString();
        } else {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        }

        // Fetch hospital branches, specializations, and doctors
        $hospitalBranches = $this->getHospitalBranchEnumValues();
        $specializations = $isSuperadmin
            ? BkSpecializations::select('id', 'name', 'hospital_branch')
            ->orderBy('name')
            ->get()
            ->map(function ($spec) {
                $spec->display_name = $spec->name . ' (' . ucfirst($spec->hospital_branch) . ')';
                return $spec;
            })
            : BkSpecializations::where('hospital_branch', $userBranch)
            ->orderBy('name')
            ->get()
            ->map(function ($spec) {
                $spec->display_name = $spec->name;
                return $spec;
            });

        $doctors = $isSuperadmin
            ? BkDoctor::select('id', 'doctor_name', 'hospital_branch')
            ->orderBy('doctor_name')
            ->get()
            ->map(function ($doctor) {
                $doctor->display_name = $doctor->doctor_name . ' - ' . ucfirst($doctor->hospital_branch);
                return $doctor;
            })
            : BkDoctor::where('hospital_branch', $userBranch)
            ->orderBy('doctor_name')
            ->get()
            ->map(function ($doctor) {
                $doctor->display_name = $doctor->doctor_name;
                return $doctor;
            });

        \Log::info('Fetched metadata', [
            'hospital_branches_count' => count($hospitalBranches),
            'specializations_count' => $specializations->count(),
            'doctors_count' => $doctors->count(),
        ]);

        // Cache bk_messaging count
        $messagingCount = Cache::remember("messaging_count_{$userBranch}", 60, function () use ($userBranch) {
            return DB::table('bk_messaging')
                ->where('active', 1)
                ->where('hospital_branch', $userBranch)
                ->count();
        });

        // Status map
        $statusMap = [
            'pending' => ['pending'],
            'patients_seen' => ['honoured', 'early', 'late', 'pending'],
            'missed' => ['missed'],
            'all' => ['pending', 'honoured', 'early', 'late', 'missed', 'cancelled', 'rescheduled', 'archived'],
            'cancelled' => ['cancelled'],
            'rescheduled' => ['rescheduled'],
            'archived' => ['archived'],
            'external_approved' => ['pending', 'honoured', 'early', 'late', 'missed', 'archived'],
            'external_pending' => ['pending'],
        ];

        // Initialize appointments collection
        $appointments = collect();
        $specializationMap = $specializations->pluck('name', 'id')->toArray();

        // Build queries based on status and booking type
        $allowedBookingTypes = ['new', 'review', 'post_op', 'external_approved', 'external_pending'];

        // Internal appointments (bk_appointments)
        if ($status === 'all' || in_array($status, ['pending', 'patients_seen', 'missed', 'archived']) || ($bookingType && in_array($bookingType, ['new', 'review', 'post_op']))) {
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
                    DB::raw('COALESCE(bk_doctor.doctor_name, bk_appointments.doctor_name, "-") as doctor'),
                    'bk_appointments.booking_type',
                    'bk_appointments.appointment_status',
                    'bk_appointments.hmis_visit_status',
                    DB::raw("(select status from bk_tracing where bk_tracing.appointment_id = bk_appointments.id order by tracing_date desc limit 1) as tracing_status"),
                    DB::raw('NULL as notes'),
                    'bk_appointments.doctor_comments',
                    'bk_appointments.hospital_branch',
                    DB::raw('NULL as cancellation_reason'),
                    'bk_appointments.visit_date',
                    'bk_appointments.created_at',
                    DB::raw("'bk_appointments' as source_table"),
                ])
                ->leftJoin('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                ->leftJoin('bk_doctor', 'bk_appointments.doctor_id', '=', 'bk_doctor.id')
                ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate]);

            if ($status === 'pending') {
                $internalQuery->where('bk_appointments.appointment_status', 'pending')
                    ->where(function ($query) {
                        $query->whereNull('bk_appointments.hmis_visit_status')
                            ->orWhere('bk_appointments.hmis_visit_status', '!=', 'honoured');
                    });
            } elseif ($status === 'patients_seen') {
                $internalQuery->where(function ($query) {
                    $query->whereIn('bk_appointments.appointment_status', ['honoured', 'early', 'late'])
                        ->orWhere(function ($q) {
                            $q->where('bk_appointments.appointment_status', 'pending')
                                ->where('bk_appointments.hmis_visit_status', 'honoured');
                        });
                });
            } elseif ($status !== 'all') {
                $internalQuery->whereIn('bk_appointments.appointment_status', $statusMap[$status]);
            }

            if ($bookingType && in_array($bookingType, ['new', 'review', 'post_op'])) {
                $internalQuery->where('bk_appointments.booking_type', $bookingType)
                    ->where('bk_appointments.appointment_status', '!=', 'rescheduled')
                    ->where('bk_appointments.appointment_status', '!=', 'cancelled');
            } elseif ($bookingType && in_array($bookingType, ['external_approved', 'external_pending'])) {
                $internalQuery->whereRaw('1 = 0'); // Exclude internal appointments for external booking types
            }

            if ($selectedBranch) {
                $internalQuery->whereRaw('`bk_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
            }
            if ($selectedSpecialization) {
                $internalQuery->where('bk_appointments.specialization', $selectedSpecialization);
            }
            if ($selectedDoctor) {
                $internalQuery->where(function ($query) use ($selectedDoctor) {
                    $query->where('bk_appointments.doctor_id', $selectedDoctor)
                        ->orWhereRaw('LOWER(bk_appointments.doctor_name) COLLATE utf8mb4_unicode_ci = (SELECT LOWER(doctor_name) COLLATE utf8mb4_unicode_ci FROM bk_doctor WHERE id = ?)', [$selectedDoctor]);
                });
                \Log::debug('Internal appointments doctor filter applied', ['doctor_id' => $selectedDoctor]);
            }
            if ($tracingStatus) {
                $internalQuery->whereExists(function ($query) use ($tracingStatus) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'bk_appointments.id')
                        ->where('bk_tracing.status', $tracingStatus)
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                });
            }
            if (!$isSuperadmin) {
                $internalQuery->whereRaw('`bk_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
            }

            $internalAppointments = $internalQuery->get();
            \Log::info('Internal appointments fetched', ['count' => $internalAppointments->count()]);
            if ($selectedDoctor && $internalAppointments->isEmpty()) {
                \Log::warning('No internal appointments found for doctor filter', [
                    'selected_doctor' => $selectedDoctor,
                    'sample_doctor_ids' => BkAppointments::whereNotNull('doctor_id')->limit(5)->pluck('doctor_id')->toArray(),
                    'sample_doctor_names' => BkAppointments::whereNotNull('doctor_name')->limit(5)->pluck('doctor_name')->toArray(),
                ]);
            }
            $appointments = $appointments->merge($internalAppointments);
        }

        // External approved appointments
        if (($status === 'all' || $status === 'external_approved' || $status === 'patients_seen' || $bookingType === 'external_approved') && ($isSuperadmin || $userBranch === 'kijabe')) {
            $approvedQuery = ExternalApproved::query()
                ->select([
                    'external_approveds.id',
                    'external_approveds.booking_id as appointment_number',
                    'external_approveds.full_name',
                    'external_approveds.patient_number',
                    'external_approveds.email',
                    'external_approveds.phone',
                    'external_approveds.appointment_date',
                    'external_approveds.appointment_time',
                    'bk_specializations.name as specialization',
                    DB::raw('COALESCE(bk_doctor.doctor_name, external_approveds.doctor_name, "-") as doctor'),
                    'external_approveds.hospital_branch',
                    DB::raw("'external_approved' as booking_type"),
                    'external_approveds.appointment_status',
                    DB::raw('NULL as hmis_visit_status'),
                    DB::raw("(select status from bk_tracing where bk_tracing.appointment_id = external_approveds.id order by tracing_date desc limit 1) as tracing_status"),
                    'external_approveds.notes',
                    'external_approveds.doctor_comments',
                    DB::raw('NULL as cancellation_reason'),
                    DB::raw('NULL as visit_date'),
                    'external_approveds.created_at',
                    DB::raw("'external_approved' as source_table"),
                ])
                ->leftJoin('bk_specializations', 'external_approveds.specialization', '=', 'bk_specializations.name')
                ->leftJoin('bk_doctor', function ($join) {
                    $join->on('external_approveds.doctor_name', '=', 'bk_doctor.doctor_name')
                        ->orOn(DB::raw('LOWER(external_approveds.doctor_name)'), '=', DB::raw('LOWER(bk_doctor.doctor_name)'));
                })
                ->whereBetween('external_approveds.appointment_date', [$startDate, $endDate]);

            if ($status === 'patients_seen') {
                $approvedQuery->whereIn('external_approveds.appointment_status', ['honoured', 'early', 'late']);
            } elseif ($status !== 'all' && $status !== 'external_approved') {
                $approvedQuery->whereIn('external_approveds.appointment_status', $statusMap[$status]);
            }

            if ($bookingType && $bookingType !== 'external_approved') {
                $approvedQuery->whereRaw('1 = 0'); // Exclude if booking_type is not external_approved
            }

            if ($selectedBranch) {
                $approvedQuery->whereRaw('`external_approveds`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
            }
            if ($selectedSpecialization) {
                $approvedQuery->where('external_approveds.specialization', $selectedSpecialization);
            }
            if ($selectedDoctor) {
                $doctor = BkDoctor::find($selectedDoctor);
                if ($doctor) {
                    $approvedQuery->whereRaw('LOWER(external_approveds.doctor_name) COLLATE utf8mb4_unicode_ci = ?', [strtolower($doctor->doctor_name)]);
                    \Log::debug('External approved doctor filter applied', ['doctor_name' => $doctor->doctor_name]);
                } else {
                    \Log::warning('Doctor not found for filter', ['selected_doctor' => $selectedDoctor]);
                }
            }
            if ($tracingStatus) {
                $approvedQuery->whereExists(function ($query) use ($tracingStatus) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'external_approveds.id')
                        ->where('bk_tracing.status', $tracingStatus)
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                });
            }
            if (!$isSuperadmin) {
                $approvedQuery->whereRaw('`external_approveds`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
            }

            $approvedAppointments = $approvedQuery->get();
            \Log::info('External approved appointments fetched', ['count' => $approvedAppointments->count()]);
            if ($selectedDoctor && $approvedAppointments->isEmpty()) {
                \Log::warning('No external approved appointments found for doctor filter', [
                    'selected_doctor' => $selectedDoctor,
                    'sample_doctor_names' => ExternalApproved::whereNotNull('doctor_name')->limit(5)->pluck('doctor_name')->toArray(),
                ]);
            }
            $appointments = $appointments->merge($approvedAppointments);
        }

        // External pending approvals
        if (($status === 'all' || $status === 'external_pending' || $bookingType === 'external_pending') && ($isSuperadmin || $userBranch === 'kijabe')) {
            $pendingQuery = ExternalPendingApproval::query()
                ->select([
                    'external_pending_approvals.id',
                    'external_pending_approvals.appointment_number',
                    'external_pending_approvals.full_name',
                    'external_pending_approvals.patient_number',
                    'external_pending_approvals.email',
                    'external_pending_approvals.phone',
                    'external_pending_approvals.appointment_date',
                    DB::raw('NULL as appointment_time'),
                    'bk_specializations.name as specialization',
                    DB::raw('"-" as doctor'),
                    DB::raw("'external_pending' as booking_type"),
                    'external_pending_approvals.status as appointment_status',
                    DB::raw('NULL as hmis_visit_status'),
                    DB::raw("(select status from bk_tracing where bk_tracing.appointment_id = external_pending_approvals.id order by tracing_date desc limit 1) as tracing_status"),
                    'external_pending_approvals.notes',
                    DB::raw('NULL as doctor_comments'),
                    DB::raw('NULL as hospital_branch'),
                    DB::raw('NULL as cancellation_reason'),
                    DB::raw('NULL as visit_date'),
                    'external_pending_approvals.created_at',
                    DB::raw("'external_pending' as source_table"),
                ])
                ->leftJoin('bk_specializations', 'external_pending_approvals.specialization', '=', 'bk_specializations.name')
                ->whereBetween('external_pending_approvals.appointment_date', [$startDate, $endDate]);

            if ($status !== 'all' && $status !== 'external_pending') {
                $pendingQuery->whereIn('external_pending_approvals.status', $statusMap[$status]);
            }

            if ($bookingType && $bookingType !== 'external_pending') {
                $pendingQuery->whereRaw('1 = 0'); // Exclude if booking_type is not external_pending
            }

            if ($selectedSpecialization) {
                $pendingQuery->where('external_pending_approvals.specialization', $selectedSpecialization);
            }
            if ($selectedDoctor) {
                \Log::warning('Doctor filter ignored for external_pending_approvals as doctor_id is not available');
            }
            if ($tracingStatus) {
                $pendingQuery->whereExists(function ($query) use ($tracingStatus) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'external_pending_approvals.id')
                        ->where('bk_tracing.status', $tracingStatus)
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                });
            }
            if (!$isSuperadmin) {
                $pendingQuery->whereRaw('1 = 0');
            }

            $pendingAppointments = $pendingQuery->get();
            \Log::info('External pending appointments fetched', ['count' => $pendingAppointments->count()]);
            $appointments = $appointments->merge($pendingAppointments);
        }

        // Cancelled appointments
        if ($status === 'all' || $status === 'cancelled') {
            $cancelledQuery = CancelledAppointment::query()
                ->select([
                    'cancelled_appointments.id',
                    'cancelled_appointments.appointment_number',
                    'cancelled_appointments.full_name',
                    'cancelled_appointments.patient_number',
                    'cancelled_appointments.email',
                    'cancelled_appointments.phone',
                    'cancelled_appointments.appointment_date',
                    'cancelled_appointments.appointment_time',
                    'bk_specializations.name as specialization',
                    DB::raw('COALESCE(bk_doctor.doctor_name, cancelled_appointments.doctor_name, "-") as doctor'),
                    'cancelled_appointments.booking_type',
                    DB::raw("'cancelled' as appointment_status"),
                    DB::raw('NULL as hmis_visit_status'),
                    DB::raw("(select status from bk_tracing where bk_tracing.appointment_id = cancelled_appointments.id order by tracing_date desc limit 1) as tracing_status"),
                    'cancelled_appointments.notes',
                    DB::raw('NULL as doctor_comments'),
                    'cancelled_appointments.hospital_branch',
                    'cancelled_appointments.cancellation_reason',
                    DB::raw('NULL as visit_date'),
                    'cancelled_appointments.created_at',
                    DB::raw("'cancelled' as source_table"),
                ])
                ->leftJoin('bk_specializations', 'cancelled_appointments.specialization', '=', 'bk_specializations.name')
                ->leftJoin('bk_doctor', function ($join) {
                    $join->on('cancelled_appointments.doctor_name', '=', 'bk_doctor.doctor_name')
                        ->orOn(DB::raw('LOWER(cancelled_appointments.doctor_name)'), '=', DB::raw('LOWER(bk_doctor.doctor_name)'));
                })
                ->whereBetween('cancelled_appointments.appointment_date', [$startDate, $endDate]);

            if ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                $cancelledQuery->whereRaw('`cancelled_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
            }
            if ($selectedSpecialization) {
                $cancelledQuery->where('cancelled_appointments.specialization', $selectedSpecialization);
            }
            if ($selectedDoctor) {
                $doctor = BkDoctor::find($selectedDoctor);
                if ($doctor) {
                    $cancelledQuery->whereRaw('LOWER(cancelled_appointments.doctor_name) COLLATE utf8mb4_unicode_ci = ?', [strtolower($doctor->doctor_name)]);
                    \Log::debug('Cancelled appointments doctor filter applied', ['doctor_name' => $doctor->doctor_name]);
                } else {
                    \Log::warning('Doctor not found for filter', ['selected_doctor' => $selectedDoctor]);
                }
            }
            if ($bookingType && in_array($bookingType, ['new', 'review', 'post_op'])) {
                $cancelledQuery->whereRaw('1 = 0'); // Exclude cancelled appointments for new, review, post_op
            } elseif ($bookingType && in_array($bookingType, ['external_approved', 'external_pending'])) {
                $cancelledQuery->whereRaw('1 = 0');
            }
            if ($tracingStatus) {
                $cancelledQuery->whereExists(function ($query) use ($tracingStatus) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'cancelled_appointments.id')
                        ->where('bk_tracing.status', $tracingStatus)
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                });
            }
            if (!$isSuperadmin && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
                $cancelledQuery->whereRaw('`cancelled_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
            }

            $cancelledAppointments = $cancelledQuery->get();
            \Log::info('Cancelled appointments fetched', ['count' => $cancelledAppointments->count()]);
            if ($selectedDoctor && $cancelledAppointments->isEmpty()) {
                \Log::warning('No cancelled appointments found for doctor filter', [
                    'selected_doctor' => $selectedDoctor,
                    'sample_doctor_names' => CancelledAppointment::whereNotNull('doctor_name')->limit(5)->pluck('doctor_name')->toArray(),
                ]);
            }
            $appointments = $appointments->merge($cancelledAppointments);
        }

        // Rescheduled appointments
        if ($status === 'all' || $status === 'rescheduled') {
            $rescheduledQuery = DB::table('bk_rescheduled_appointments as a')
                ->join('bk_appointments as b', 'a.appointment_id', '=', 'b.id')
                ->join('bk_appointments as c', 'a.re_appointment_id', '=', 'c.id')
                ->leftJoin('bk_specializations as bs', 'b.specialization', '=', 'bs.id')
                ->leftJoin('bk_specializations as cs', 'c.specialization', '=', 'cs.id')
                ->leftJoin('bk_doctor as bd', 'b.doctor_id', '=', 'bd.id')
                ->leftJoin('bk_doctor as cd', 'c.doctor_id', '=', 'cd.id')
                ->select([
                    'b.id',
                    'b.appointment_number as previous_number',
                    'b.full_name',
                    'b.patient_number',
                    'b.email',
                    'b.phone',
                    DB::raw('COALESCE(bs.name, "-") as previous_specialization'),
                    'b.appointment_date as previous_date',
                    'b.appointment_time as previous_time',
                    DB::raw("'=>' as from_to"),
                    'c.appointment_number as current_number',
                    DB::raw('COALESCE(cs.name, "-") as current_specialization'),
                    'c.appointment_date as current_date',
                    'c.appointment_time as current_time',
                    'a.reason',
                    'b.hospital_branch as previous_branch',
                    'c.hospital_branch as current_branch',
                    DB::raw('COALESCE(cd.doctor_name, c.doctor_name, "-") as doctor'),
                    'b.booking_type',
                    DB::raw("'rescheduled' as appointment_status"),
                    DB::raw("(select status from bk_tracing where bk_tracing.appointment_id = b.id order by tracing_date desc limit 1) as tracing_status"),
                    DB::raw('NULL as notes'),
                    'c.doctor_comments',
                    DB::raw('NULL as cancellation_reason'),
                    'c.created_at',
                    DB::raw("'rescheduled' as source_table"),
                ])
                ->where('b.appointment_status', 'rescheduled')
                ->whereBetween('c.appointment_date', [$startDate, $endDate]);

            if ($selectedBranch) {
                $rescheduledQuery->where(function ($q) use ($selectedBranch) {
                    $q->whereRaw('b.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch])
                        ->orWhereRaw('c.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
                });
            }
            if ($selectedSpecialization) {
                $rescheduledQuery->where(function ($q) use ($selectedSpecialization) {
                    $q->where('b.specialization', $selectedSpecialization)
                        ->orWhere('c.specialization', $selectedSpecialization);
                });
            }
            if ($selectedDoctor) {
                $rescheduledQuery->where(function ($q) use ($selectedDoctor) {
                    $q->where('b.doctor_id', $selectedDoctor)
                        ->orWhere('c.doctor_id', $selectedDoctor)
                        ->orWhereRaw('LOWER(b.doctor_name) COLLATE utf8mb4_unicode_ci = (SELECT LOWER(doctor_name) COLLATE utf8mb4_unicode_ci FROM bk_doctor WHERE id = ?)', [$selectedDoctor])
                        ->orWhereRaw('LOWER(c.doctor_name) COLLATE utf8mb4_unicode_ci = (SELECT LOWER(doctor_name) COLLATE utf8mb4_unicode_ci FROM bk_doctor WHERE id = ?)', [$selectedDoctor]);
                });
                \Log::debug('Rescheduled appointments doctor filter applied', ['doctor_id' => $selectedDoctor]);
            }
            if ($bookingType && in_array($bookingType, ['new', 'review', 'post_op'])) {
                $rescheduledQuery->where('b.booking_type', $bookingType)
                    ->where('b.appointment_status', '!=', 'rescheduled')
                    ->where('b.appointment_status', '!=', 'cancelled');
            } elseif ($bookingType && in_array($bookingType, ['external_approved', 'external_pending'])) {
                $rescheduledQuery->whereRaw('1 = 0');
            }
            if ($tracingStatus) {
                $rescheduledQuery->whereExists(function ($query) use ($tracingStatus) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'b.id')
                        ->where('bk_tracing.status', $tracingStatus)
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                });
            }
            if (!$isSuperadmin) {
                $rescheduledQuery->where(function ($q) use ($userBranch) {
                    $q->whereRaw('b.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$userBranch])
                        ->orWhereRaw('c.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
                });
            }

            $rescheduledAppointments = $rescheduledQuery->get();
            \Log::info('Rescheduled appointments fetched', ['count' => $rescheduledAppointments->count()]);
            if ($selectedDoctor && $rescheduledAppointments->isEmpty()) {
                \Log::warning('No rescheduled appointments found for doctor filter', [
                    'selected_doctor' => $selectedDoctor,
                    'sample_doctor_ids' => DB::table('bk_appointments')->whereNotNull('doctor_id')->limit(5)->pluck('doctor_id')->toArray(),
                    'sample_doctor_names' => DB::table('bk_appointments')->whereNotNull('doctor_name')->limit(5)->pluck('doctor_name')->toArray(),
                ]);
            }
            $appointments = $appointments->merge($rescheduledAppointments);
        }

        \Log::info('Total appointments fetched', ['count' => $appointments->count()]);

        // Fetch tracing data
        $appointmentIds = $appointments->map(function ($appointment) {
            return is_object($appointment) ? $appointment->id : ($appointment['id'] ?? null);
        })->filter()->unique()->toArray();
        $tracingData = [];
        if (!empty($appointmentIds)) {
            $tracingData = BkTracing::whereIn('appointment_id', $appointmentIds)
                ->select('appointment_id', 'status as tracing_status', 'message as tracing_message', 'tracing_date')
                ->orderBy('tracing_date', 'desc')
                ->get()
                ->groupBy('appointment_id')
                ->map(function ($group) {
                    return $group->first();
                });
        }

        // Normalize appointments
        $normalizeAppointments = function ($appointments, $status, $tracingData, $specializationMap, $isSuperadmin) {
            $seenIds = [];
            return $appointments->map(function ($appointment) use ($status, $tracingData, &$seenIds, $specializationMap, $isSuperadmin) {
                $appointmentArray = is_object($appointment)
                    ? (method_exists($appointment, 'toArray') ? $appointment->toArray() : (array) $appointment)
                    : (array) $appointment;
                $id = $appointmentArray['id'] ?? null;

                if (!$id || in_array($id, $seenIds)) {
                    return null;
                }

                $seenIds[] = $id;
                $tracing = isset($id) && isset($tracingData[$id]) ? $tracingData[$id] : null;

                if ($status === 'rescheduled') {
                    $currentSpecialization = $appointmentArray['current_specialization'] ?? '-';
                    $previousSpecialization = $appointmentArray['previous_specialization'] ?? '-';
                    $hospitalBranch = $appointmentArray['current_branch'] ?? '-';
                    if ($isSuperadmin && $hospitalBranch !== '-') {
                        if ($currentSpecialization !== '-') {
                            $currentSpecialization .= ' (' . ucfirst($hospitalBranch) . ')';
                        }
                        if ($previousSpecialization !== '-') {
                            $previousSpecialization .= ' (' . ucfirst($appointmentArray['previous_branch'] ?? '-') . ')';
                        }
                    }
                } else {
                    $currentSpecialization = $appointmentArray['specialization'] ?? '-';
                    $previousSpecialization = '-';
                    $hospitalBranch = $appointmentArray['hospital_branch'] ?? '-';
                    if ($isSuperadmin && $hospitalBranch !== '-' && $currentSpecialization !== '-') {
                        $currentSpecialization .= ' (' . ucfirst($hospitalBranch) . ')';
                    }
                }

                $doctor = $appointmentArray['doctor'] ?? '-';
                if ($doctor === '-' && isset($appointmentArray['doctor_name'])) {
                    $doctor = $appointmentArray['doctor_name'];
                    \Log::debug('Fallback to doctor_name', [
                        'id' => $id,
                        'source_table' => $appointmentArray['source_table'] ?? 'unknown',
                        'doctor_name' => $doctor,
                    ]);
                }

                return [
                    'id' => $id,
                    'appointment_number' => $appointmentArray['appointment_number'] ?? ($appointmentArray['current_number'] ?? '-'),
                    'full_name' => $appointmentArray['full_name'] ?? '-',
                    'patient_number' => $appointmentArray['patient_number'] ?? '-',
                    'email' => $appointmentArray['email'] ?? '-',
                    'phone' => $appointmentArray['phone'] ?? '-',
                    'appointment_date' => $status === 'rescheduled' && isset($appointmentArray['current_date'])
                        ? ($appointmentArray['current_date'] ? Carbon::parse($appointmentArray['current_date'])->format('Y-m-d') : '-')
                        : (isset($appointmentArray['appointment_date']) && $appointmentArray['appointment_date'] ? Carbon::parse($appointmentArray['appointment_date'])->format('Y-m-d') : '-'),
                    'appointment_time' => $status === 'rescheduled' ? ($appointmentArray['current_time'] ?? '-') : ($appointmentArray['appointment_time'] ?? '-'),
                    'specialization' => $currentSpecialization,
                    'doctor' => $doctor,
                    'booking_type' => $appointmentArray['booking_type'] ?? '-',
                    'appointment_status' => $status === 'rescheduled' ? 'rescheduled' : ($appointmentArray['appointment_status'] ?? 'pending'),
                    'hmis_visit_status' => $appointmentArray['hmis_visit_status'] ?? '-',
                    'tracing_status' => $tracing ? $tracing->tracing_status : ($appointmentArray['tracing_status'] ?? '-'),
                    'tracing_message' => $tracing ? $tracing->tracing_message : '-',
                    'tracing_date' => $tracing ? Carbon::parse($tracing->tracing_date)->format('Y-m-d') : '-',
                    'notes' => $appointmentArray['notes'] ?? '-',
                    'doctor_comments' => $appointmentArray['doctor_comments'] ?? '-',
                    'hospital_branch' => $hospitalBranch,
                    'cancellation_reason' => $appointmentArray['cancellation_reason'] ?? '-',
                    'previous_number' => $status === 'rescheduled' ? ($appointmentArray['previous_number'] ?? '-') : '-',
                    'previous_specialization' => $previousSpecialization,
                    'previous_date' => $status === 'rescheduled' && isset($appointmentArray['previous_date'])
                        ? ($appointmentArray['previous_date'] ? Carbon::parse($appointmentArray['previous_date'])->format('Y-m-d') : '-')
                        : '-',
                    'previous_time' => $status === 'rescheduled' ? ($appointmentArray['previous_time'] ?? '-') : '-',
                    'reason' => $status === 'rescheduled' ? ($appointmentArray['reason'] ?? '-') : '-',
                    'current_number' => $status === 'rescheduled' ? ($appointmentArray['current_number'] ?? '-') : '-',
                    'current_date' => $status === 'rescheduled' && isset($appointmentArray['current_date'])
                        ? ($appointmentArray['current_date'] ? Carbon::parse($appointmentArray['current_date'])->format('Y-m-d') : '-')
                        : '-',
                    'current_time' => $status === 'rescheduled' ? ($appointmentArray['current_time'] ?? '-') : '-',
                    'current_branch' => $status === 'rescheduled' ? ($appointmentArray['current_branch'] ?? '-') : '-',
                    'created_at' => isset($appointmentArray['created_at']) && $appointmentArray['created_at']
                        ? Carbon::parse($appointmentArray['created_at'])->format('Y-m-d H:i:s')
                        : now()->format('Y-m-d H:i:s'),
                    'source_table' => $appointmentArray['source_table'] ?? 'unknown',
                ];
            })->filter()->values();
        };

        $appointments = $normalizeAppointments($appointments, $status, $tracingData, $specializationMap, $isSuperadmin);

        if ($isAjax) {
            try {
                return response()->json([
                    'data' => $appointments->values()->toArray(),
                    'recordsTotal' => $appointments->count(),
                    'recordsFiltered' => $appointments->count(),
                    'draw' => (int) $request->input('draw', 1),
                ], 200, [], JSON_NUMERIC_CHECK);
            } catch (\Exception $e) {
                \Log::error('AJAX query failed: ' . $e->getMessage());
                return response()->json(['error' => 'Error loading table data'], 500);
            }
        }

        if ($exportCsv) {
            $filename = ($selectedBranch ? "{$selectedBranch}_" : "") . ($status !== 'all' ? "{$status}_" : "") . "detailed_report_" . now()->format('Ymd_His') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $columns = [
                'appointment_number' => 'ID',
                'full_name' => 'Patient',
                'patient_number' => 'Patient Number',
                'email' => 'Email',
                'phone' => 'Phone',
                'specialization' => 'Specialization',
                'doctor' => 'Doctor',
                'booking_type' => 'Type',
                'appointment_status' => 'Status',
                'hmis_visit_status' => 'HMIS Visit Status',
                'tracing_status' => 'Tracing Status',
                'tracing_message' => 'Tracing Message',
                'tracing_date' => 'Tracing Date',
                'appointment_date' => 'Date',
                'appointment_time' => 'Time',
                'hospital_branch' => 'Branch',
                'notes' => 'Notes',
                'doctor_comments' => 'Doctor Comments',
            ];

            if ($status === 'rescheduled') {
                $columns = [
                    'appointment_number' => 'ID',
                    'full_name' => 'Patient',
                    'patient_number' => 'Patient Number',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'previous_specialization' => 'Previous Specialization',
                    'current_specialization' => 'Current Specialization',
                    'doctor' => 'Doctor',
                    'booking_type' => 'Type',
                    'appointment_status' => 'Status',
                    'hmis_visit_status' => 'HMIS Visit Status',
                    'tracing_status' => 'Tracing Status',
                    'tracing_message' => 'Tracing Message',
                    'tracing_date' => 'Tracing Date',
                    'previous_date' => 'Previous Date',
                    'previous_time' => 'Previous Time',
                    'current_date' => 'Current Date',
                    'current_time' => 'Current Time',
                    'reason' => 'Reason',
                    'previous_branch' => 'Previous Branch',
                    'current_branch' => 'Current Branch',
                    'notes' => 'Notes',
                    'doctor_comments' => 'Doctor Comments',
                ];
            } elseif ($status === 'cancelled') {
                $columns['cancellation_reason'] = 'Cancellation Reason';
            }

            $csvColumns = array_keys($columns);

            if ($appointments->isEmpty()) {
                \Log::warning('No appointments found for CSV export');
                return response()->stream(function () {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['No records found']);
                    fclose($file);
                }, 200, $headers);
            }

            try {
                return response()->stream(function () use ($appointments, $csvColumns, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($columns));

                    foreach ($appointments as $appointment) {
                        $appointment = (array) $appointment;
                        $row = [];
                        foreach ($csvColumns as $col) {
                            $value = $appointment[$col] ?? '-';
                            if (is_array($value) || is_object($value)) {
                                $value = '-';
                            }
                            if (is_string($value) && in_array($value[0] ?? '', ['=', '+', '-', '@'])) {
                                $value = "'" . $value;
                            }
                            $row[] = $value;
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                }, 200, $headers);
            } catch (\Exception $e) {
                \Log::error('Failed to generate CSV: ' . $e->getMessage());
                return redirect()->route('booking.detailed-report')->with('error', 'Failed to generate CSV');
            }
        }

        // Calculate totals
        $baseQuery = BkAppointments::query()
            ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate]);
        if ($selectedBranch) {
            $baseQuery->whereRaw('`bk_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
        }
        if ($selectedSpecialization) {
            $baseQuery->where('bk_appointments.specialization', $selectedSpecialization);
        }
        if ($selectedDoctor) {
            $baseQuery->where(function ($query) use ($selectedDoctor) {
                $query->where('bk_appointments.doctor_id', $selectedDoctor)
                    ->orWhereRaw('LOWER(bk_appointments.doctor_name) COLLATE utf8mb4_unicode_ci = (SELECT LOWER(doctor_name) COLLATE utf8mb4_unicode_ci FROM bk_doctor WHERE id = ?)', [$selectedDoctor]);
            });
        }
        if ($bookingType && in_array($bookingType, ['new', 'review', 'post_op'])) {
            $baseQuery->where('bk_appointments.booking_type', $bookingType)
                ->where('bk_appointments.appointment_status', '!=', 'rescheduled')
                ->where('bk_appointments.appointment_status', '!=', 'cancelled');
        }
        if ($tracingStatus) {
            $baseQuery->whereExists(function ($query) use ($tracingStatus) {
                $query->select(DB::raw(1))
                    ->from('bk_tracing')
                    ->whereColumn('bk_tracing.appointment_id', 'bk_appointments.id')
                    ->where('bk_tracing.status', $tracingStatus)
                    ->orderBy('bk_tracing.tracing_date', 'desc')
                    ->limit(1);
            });
        }
        if (!$isSuperadmin) {
            $baseQuery->whereRaw('`bk_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
        }

        $externalApprovedQuery = ExternalApproved::query()
            ->whereBetween('external_approveds.appointment_date', [$startDate, $endDate]);
        if ($selectedBranch) {
            $externalApprovedQuery->whereRaw('`external_approveds`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
        }
        if ($selectedSpecialization) {
            $externalApprovedQuery->where('external_approveds.specialization', $selectedSpecialization);
        }
        if ($selectedDoctor) {
            $doctor = BkDoctor::find($selectedDoctor);
            if ($doctor) {
                $externalApprovedQuery->whereRaw('LOWER(external_approveds.doctor_name) COLLATE utf8mb4_unicode_ci = ?', [strtolower($doctor->doctor_name)]);
            }
        }
        if ($tracingStatus) {
            $externalApprovedQuery->whereExists(function ($query) use ($tracingStatus) {
                $query->select(DB::raw(1))
                    ->from('bk_tracing')
                    ->whereColumn('bk_tracing.appointment_id', 'external_approveds.id')
                    ->where('bk_tracing.status', $tracingStatus)
                    ->orderBy('bk_tracing.tracing_date', 'desc')
                    ->limit(1);
            });
        }
        if (!$isSuperadmin) {
            $externalApprovedQuery->whereRaw('`external_approveds`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
        }

        $pendingExternalQuery = ExternalPendingApproval::query()
            ->whereBetween('external_pending_approvals.appointment_date', [$startDate, $endDate]);
        if ($selectedSpecialization) {
            $pendingExternalQuery->where('external_pending_approvals.specialization', $selectedSpecialization);
        }
        if ($tracingStatus) {
            $pendingExternalQuery->whereExists(function ($query) use ($tracingStatus) {
                $query->select(DB::raw(1))
                    ->from('bk_tracing')
                    ->whereColumn('bk_tracing.appointment_id', 'external_pending_approvals.id')
                    ->where('bk_tracing.status', $tracingStatus)
                    ->orderBy('bk_tracing.tracing_date', 'desc')
                    ->limit(1);
            });
        }
        if (!$isSuperadmin) {
            $pendingExternalQuery->whereRaw('1 = 0');
        }

        $cancelledQuery = CancelledAppointment::query()
            ->whereBetween('cancelled_appointments.appointment_date', [$startDate, $endDate]);
        if ($selectedBranch && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
            $cancelledQuery->whereRaw('`cancelled_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
        }
        if ($selectedSpecialization) {
            $cancelledQuery->where('cancelled_appointments.specialization', $selectedSpecialization);
        }
        if ($selectedDoctor) {
            $doctor = BkDoctor::find($selectedDoctor);
            if ($doctor) {
                $cancelledQuery->whereRaw('LOWER(cancelled_appointments.doctor_name) COLLATE utf8mb4_unicode_ci = ?', [strtolower($doctor->doctor_name)]);
            }
        }
        if ($bookingType && in_array($bookingType, ['new', 'review', 'post_op'])) {
            $cancelledQuery->where('cancelled_appointments.booking_type', $bookingType)
                ->where('cancelled_appointments.appointment_status', '!=', 'rescheduled');
        }
        if ($tracingStatus) {
            $cancelledQuery->whereExists(function ($query) use ($tracingStatus) {
                $query->select(DB::raw(1))
                    ->from('bk_tracing')
                    ->whereColumn('bk_tracing.appointment_id', 'cancelled_appointments.id')
                    ->where('bk_tracing.status', $tracingStatus)
                    ->orderBy('bk_tracing.tracing_date', 'desc')
                    ->limit(1);
            });
        }
        if (!$isSuperadmin && DB::getSchemaBuilder()->hasColumn('cancelled_appointments', 'hospital_branch')) {
            $cancelledQuery->whereRaw('`cancelled_appointments`.`hospital_branch` COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
        }

        $rescheduledQuery = DB::table('bk_rescheduled_appointments as a')
            ->join('bk_appointments as b', 'a.appointment_id', '=', 'b.id')
            ->join('bk_appointments as c', 'a.re_appointment_id', '=', 'c.id')
            ->whereBetween('c.appointment_date', [$startDate, $endDate]);
        if ($selectedBranch) {
            $rescheduledQuery->where(function ($q) use ($selectedBranch) {
                $q->whereRaw('b.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch])
                    ->orWhereRaw('c.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$selectedBranch]);
            });
        }
        if ($selectedSpecialization) {
            $rescheduledQuery->where(function ($q) use ($selectedSpecialization) {
                $q->where('b.specialization', $selectedSpecialization)
                    ->orWhere('c.specialization', $selectedSpecialization);
            });
        }
        if ($selectedDoctor) {
            $rescheduledQuery->where(function ($q) use ($selectedDoctor) {
                $q->where('b.doctor_id', $selectedDoctor)
                    ->orWhere('c.doctor_id', $selectedDoctor)
                    ->orWhereRaw('LOWER(b.doctor_name) COLLATE utf8mb4_unicode_ci = (SELECT LOWER(doctor_name) COLLATE utf8mb4_unicode_ci FROM bk_doctor WHERE id = ?)', [$selectedDoctor])
                    ->orWhereRaw('LOWER(c.doctor_name) COLLATE utf8mb4_unicode_ci = (SELECT LOWER(doctor_name) COLLATE utf8mb4_unicode_ci FROM bk_doctor WHERE id = ?)', [$selectedDoctor]);
            });
        }
        if ($bookingType && in_array($bookingType, ['new', 'review', 'post_op'])) {
            $rescheduledQuery->where('b.booking_type', $bookingType)
                ->where('b.appointment_status', '!=', 'rescheduled');
        }
        if ($tracingStatus) {
            $rescheduledQuery->whereExists(function ($query) use ($tracingStatus) {
                $query->select(DB::raw(1))
                    ->from('bk_tracing')
                    ->whereColumn('bk_tracing.appointment_id', 'b.id')
                    ->where('bk_tracing.status', $tracingStatus)
                    ->orderBy('bk_tracing.tracing_date', 'desc')
                    ->limit(1);
            });
        }
        if (!$isSuperadmin) {
            $rescheduledQuery->where(function ($q) use ($userBranch) {
                $q->whereRaw('b.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$userBranch])
                    ->orWhereRaw('c.hospital_branch COLLATE utf8mb4_unicode_ci = ?', [$userBranch]);
            });
        }

        try {
            $totals = [
                'total_appointments' => $baseQuery->clone()->count() +
                    ($bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ? $externalApprovedQuery->clone()->count() : 0) +
                    ($bookingType === 'external_pending' || $status === 'all' || $status === 'external_pending' ? $pendingExternalQuery->clone()->count() : 0) +
                    ($status === 'all' || $status === 'cancelled' ? $cancelledQuery->clone()->count() : 0),
                'confirmed_pending' => $baseQuery->clone()
                    ->where('bk_appointments.appointment_status', 'pending')
                    ->where(function ($query) {
                        $query->whereNull('bk_appointments.hmis_visit_status')
                            ->orWhere('bk_appointments.hmis_visit_status', '!=', 'honoured');
                    })->count(),
                'patients_seen' => $baseQuery->clone()
                    ->where(function ($query) {
                        $query->whereIn('bk_appointments.appointment_status', ['honoured', 'early', 'late'])
                            ->orWhere(function ($q) {
                                $q->where('bk_appointments.appointment_status', 'pending')
                                    ->where('bk_appointments.hmis_visit_status', 'honoured');
                            });
                    })->count() +
                    ($bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ?
                        $externalApprovedQuery->clone()->whereIn('external_approveds.appointment_status', ['honoured', 'early', 'late'])->count() : 0),
                'missed' => $baseQuery->clone()->where('bk_appointments.appointment_status', 'missed')->count() +
                    ($bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ?
                        $externalApprovedQuery->clone()->where('external_approveds.appointment_status', 'missed')->count() : 0),
                'cancelled' => $status === 'all' || $status === 'cancelled' ? $cancelledQuery->clone()->count() : 0,
                'rescheduled' => $status === 'all' || $status === 'rescheduled' ? $rescheduledQuery->clone()->count() : 0,
                'pending_external_approvals' => $bookingType === 'external_pending' || $status === 'all' || $status === 'external_pending' ?
                    $pendingExternalQuery->clone()->count() : 0,
                'external_approved' => $bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ?
                    $externalApprovedQuery->clone()->count() : 0,
                'archived' => $baseQuery->clone()->where('bk_appointments.appointment_status', 'archived')->count() +
                    ($bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ?
                        $externalApprovedQuery->clone()->where('external_approveds.appointment_status', 'archived')->count() : 0),
                'new_count' => $baseQuery->clone()->where('bk_appointments.booking_type', 'new')->where('bk_appointments.appointment_status', '!=', 'rescheduled')->count(),
                'review_count' => $baseQuery->clone()->where('bk_appointments.booking_type', 'review')->where('bk_appointments.appointment_status', '!=', 'rescheduled')->count(),
                'postop_count' => $baseQuery->clone()->where('bk_appointments.booking_type', 'post_op')->where('bk_appointments.appointment_status', '!=', 'rescheduled')->count(),
                'traced_contacted' => $baseQuery->clone()->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'bk_appointments.id')
                        ->where('bk_tracing.status', 'contacted')
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                })->count() +
                    ($bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ?
                        $externalApprovedQuery->clone()->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bk_tracing')
                                ->whereColumn('bk_tracing.appointment_id', 'external_approveds.id')
                                ->where('bk_tracing.status', 'contacted')
                                ->orderBy('bk_tracing.tracing_date', 'desc')
                                ->limit(1);
                        })->count() : 0) +
                    ($status === 'all' || $status === 'cancelled' ?
                        $cancelledQuery->clone()->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bk_tracing')
                                ->whereColumn('bk_tracing.appointment_id', 'cancelled_appointments.id')
                                ->where('bk_tracing.status', 'contacted')
                                ->orderBy('bk_tracing.tracing_date', 'desc')
                                ->limit(1);
                        })->count() : 0),
                'traced_not_contacted' => $baseQuery->clone()->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'bk_appointments.id')
                        ->where('bk_tracing.status', 'no response')
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                })->count() +
                    ($bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ?
                        $externalApprovedQuery->clone()->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bk_tracing')
                                ->whereColumn('bk_tracing.appointment_id', 'external_approveds.id')
                                ->where('bk_tracing.status', 'no response')
                                ->orderBy('bk_tracing.tracing_date', 'desc')
                                ->limit(1);
                        })->count() : 0) +
                    ($status === 'all' || $status === 'cancelled' ?
                        $cancelledQuery->clone()->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bk_tracing')
                                ->whereColumn('bk_tracing.appointment_id', 'cancelled_appointments.id')
                                ->where('bk_tracing.status', 'no response')
                                ->orderBy('bk_tracing.tracing_date', 'desc')
                                ->limit(1);
                        })->count() : 0),
                'traced_other' => $baseQuery->clone()->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'bk_appointments.id')
                        ->whereNotIn('bk_tracing.status', ['contacted', 'no response'])
                        ->orWhereNull('bk_tracing.status')
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                })->count() +
                    ($bookingType === 'external_approved' || $status === 'all' || $status === 'external_approved' || $status === 'patients_seen' ?
                        $externalApprovedQuery->clone()->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bk_tracing')
                                ->whereColumn('bk_tracing.appointment_id', 'external_approveds.id')
                                ->whereNotIn('bk_tracing.status', ['contacted', 'no response'])
                                ->orWhereNull('bk_tracing.status')
                                ->orderBy('bk_tracing.tracing_date', 'desc')
                                ->limit(1);
                        })->count() : 0) +
                    ($status === 'all' || $status === 'cancelled' ?
                        $cancelledQuery->clone()->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('bk_tracing')
                                ->whereColumn('bk_tracing.appointment_id', 'cancelled_appointments.id')
                                ->whereNotIn('bk_tracing.status', ['contacted', 'no response'])
                                ->orWhereNull('bk_tracing.status')
                                ->orderBy('bk_tracing.tracing_date', 'desc')
                                ->limit(1);
                        })->count() : 0),
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to calculate totals: ' . $e->getMessage());
            return redirect()->route('booking.detailed-report')->with('error', 'Failed to calculate totals');
        }

        $data = [
            'hospitalBranches' => $hospitalBranches,
            'specializations' => $specializations,
            'doctors' => $doctors,
            'selectedBranch' => $selectedBranch,
            'selectedSpecialization' => $selectedSpecialization,
            'selectedDoctor' => $selectedDoctor,
            'bookingType' => $bookingType,
            'tracingStatus' => $tracingStatus,
            'isSuperadmin' => $isSuperadmin,
            'isAdmin' => $isAdmin,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'timePeriod' => $timePeriod,
            'status' => $status,
            'appointments' => $appointments->toArray(),
            'totals' => $totals,
            'showReport' => $showReport,
            'messaging_count' => $messagingCount,
        ];

        if ($isSuperadmin) {
            $branchQuery = BkAppointments::query()
                ->select([
                    'bk_appointments.hospital_branch',
                    DB::raw('COUNT(*) as total_bookings'),
                ])
                ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate])
                ->where('bk_appointments.appointment_status', '!=', 'rescheduled')
                ->groupBy('bk_appointments.hospital_branch')
                ->union(
                    ExternalApproved::query()
                        ->select([
                            'external_approveds.hospital_branch',
                            DB::raw('COUNT(*) as total_bookings'),
                        ])
                        ->whereBetween('external_approveds.appointment_date', [$startDate, $endDate])
                        ->groupBy('external_approveds.hospital_branch')
                );

            if ($selectedSpecialization) {
                $branchQuery = BkAppointments::query()
                    ->select([
                        'bk_appointments.hospital_branch',
                        DB::raw('COUNT(*) as total_bookings'),
                    ])
                    ->where('bk_appointments.specialization', $selectedSpecialization)
                    ->where('bk_appointments.appointment_status', '!=', 'rescheduled')
                    ->where('bk_appointments.appointment_status', '!=', 'cancelled')
                    ->where('bk_appointments.appointment_status', '!=', 'cancelled')
                    ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate])
                    ->groupBy('bk_appointments.hospital_branch')
                    ->union(
                        ExternalApproved::query()
                            ->select([
                                'external_approveds.hospital_branch',
                                DB::raw('COUNT(*) as total_bookings'),
                            ])
                            ->where('external_approveds.specialization', $selectedSpecialization)
                            ->whereBetween('external_approveds.appointment_date', [$startDate, $endDate])
                            ->groupBy('external_approveds.hospital_branch')
                    );
            }

            if ($bookingType && in_array($bookingType, ['new', 'review', 'post_op'])) {
                $branchQuery->where('bk_appointments.booking_type', $bookingType)
                    ->where('bk_appointments.appointment_status', '!=', 'rescheduled')
                    ->where('bk_appointments.appointment_status', '!=', 'cancelled');
            }

            if ($tracingStatus) {
                $branchQuery->whereExists(function ($query) use ($tracingStatus) {
                    $query->select(DB::raw(1))
                        ->from('bk_tracing')
                        ->whereColumn('bk_tracing.appointment_id', 'bk_appointments.id')
                        ->where('bk_tracing.status', $tracingStatus)
                        ->orderBy('bk_tracing.tracing_date', 'desc')
                        ->limit(1);
                });
            }

            try {
                $data['branchData'] = $branchQuery->get()->groupBy('hospital_branch')->map(function ($group) {
                    return (object) [
                        'hospital_branch' => $group->first()->hospital_branch,
                        'total_bookings' => $group->sum('total_bookings'),
                    ];
                })->values();
            } catch (\Exception $e) {
                Log::error('Failed to fetch branch report data: ' . $e->getMessage());
                $data['branchData'] = collect();
            }
        } else {
            $data['branchData'] = collect();
        }

        return view('booking.report-details', $data);
    }
    public function dashboard(Request $request, $status = null)
    {
        $status = $status ? strtolower($status) : null;

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
        $doctors = BkDoctor::where('hospital_branch', $userBranch)->get();

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
                            'bk_appointments.hmis_visit_status',
                            DB::raw("(select status from bk_tracing a where a.appointment_id = bk_appointments.id order by tracing_date desc limit 1) as tracing_status"),
                            DB::raw('NULL as notes'),
                            'bk_appointments.doctor_comments',
                            'bk_appointments.hospital_branch',
                            'bk_appointments.visit_date',
                            'bk_appointments.created_at',
                            DB::raw("'$status' as source_table"),
                        ])
                        ->join('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                        ->where('bk_appointments.booking_type', $bookingType)
                        ->where('bk_appointments.appointment_status', '!=', 'rescheduled')
                        ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate]);

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
                            DB::raw('NULL as hospital_branch'),
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
                            'external_approveds.id',
                            'booking_id as appointment_number',
                            'full_name',
                            'patient_number',
                            'email',
                            'phone',
                            'appointment_date',
                            'appointment_time',
                            'bk_specializations.name as specialization',
                            'doctor_name as doctor',
                            'external_approveds.hospital_branch',
                            DB::raw('NULL as booking_type'),
                            'appointment_status',
                            'notes',
                            'doctor_comments',
                            DB::raw('NULL as cancellation_reason'),
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"external_approved" as source_table'),
                        ])
                        ->leftJoin('bk_specializations', 'external_approveds.specialization', '=', 'bk_specializations.name')
                        ->whereBetween('external_approveds.appointment_date', [$startDate, $endDate]);

                    if (!$isSuperadmin || ($isSuperadmin && $selectedBranch)) {
                        $query->where('external_approveds.hospital_branch', $selectedBranch);
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
                            DB::raw('COALESCE(new_appointments.appointment_date, bk_appointments.appointment_date) as appointment_date'),
                            DB::raw('COALESCE(new_appointments.appointment_time, bk_appointments.appointment_time) as appointment_time'),
                            'bk_specializations.name as specialization',
                            'bk_appointments.doctor_name as doctor',
                            'bk_appointments.booking_type',
                            'bk_appointments.appointment_status',
                            'bk_appointments.hmis_visit_status',
                            DB::raw("(select status from bk_tracing a where a.appointment_id = bk_appointments.id order by tracing_date desc limit 1) as tracing_status"),
                            DB::raw('NULL as notes'),
                            'bk_appointments.doctor_comments',
                            'bk_appointments.hospital_branch',
                            DB::raw('NULL as cancellation_reason'),
                            'bk_appointments.visit_date',
                            'bk_appointments.created_at',
                            'bk_appointments.booking_type as source_table',
                        ])
                        ->join('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                        ->leftJoin('bk_rescheduled_appointments', 'bk_appointments.id', '=', 'bk_rescheduled_appointments.appointment_id')
                        ->leftJoin('bk_appointments as new_appointments', 'bk_rescheduled_appointments.re_appointment_id', '=', 'new_appointments.id')
                        ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate]);

                    $pendingQuery = ExternalPendingApproval::query()
                        ->select([
                            'external_pending_approvals.id',
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
                            DB::raw('NULL as hmis_visit_status'),
                            DB::raw('NULL as tracing_status'),
                            'notes',
                            DB::raw('NULL as doctor_comments'),
                            DB::raw('NULL as hospital_branch'),
                            DB::raw('NULL as cancellation_reason'),
                            DB::raw('NULL as visit_date'),
                            'created_at',
                            DB::raw('"external_pending_approvals" as source_table'),
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
                            DB::raw('NULL as hmis_visit_status'),
                            DB::raw("(select status from bk_tracing a where a.appointment_id = external_approveds.id order by tracing_date desc limit 1) as tracing_status"),
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
                            DB::raw('NULL as hmis_visit_status'),
                            DB::raw("(select status from bk_tracing a where a.appointment_id = cancelled_appointments.id order by tracing_date desc limit 1) as tracing_status"),
                            'notes',
                            DB::raw('NULL as doctor_comments'),
                            'cancelled_appointments.hospital_branch',
                            'cancellation_reason',
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

                    // Restrict external_pending to Kijabe for non-superadmins
                    if (!$isSuperadmin && $userBranch !== 'kijabe') {
                        $pendingQuery->whereRaw('1 = 0');
                    }

                    try {
                        $query = $internalQuery->union($pendingQuery)
                            ->union($approvedQuery)
                            ->union($cancelledQuery);

                        $appointments = $query->get();
                        $invalidIds = $appointments->filter(function ($appointment) {
                            return is_null($appointment->id);
                        })->toArray();
                        $duplicateIds = $appointments->groupBy('id')->filter(function ($group) {
                            return $group->count() > 1;
                        })->keys()->toArray();

                        if (!empty($invalidIds)) {
                            Log::warning('Found appointments with null IDs:', ['invalid' => $invalidIds]);
                        }
                        if (!empty($duplicateIds)) {
                            Log::warning('Found appointments with duplicate IDs:', ['duplicates' => $duplicateIds]);
                        }
                        $appointments = $appointments->unique('id')->values();

                        // Normalize data to ensure consistent structure
                        $appointments = $appointments->map(function ($appointment) {
                            return [
                                'id' => $appointment->id ?? null,
                                'appointment_number' => $appointment->appointment_number ?? '-',
                                'full_name' => $appointment->full_name ?? '-',
                                'patient_number' => $appointment->patient_number ?? '-',
                                'email' => $appointment->email ?? '-',
                                'phone' => $appointment->phone ?? '-',
                                'appointment_date' => $appointment->appointment_date ? Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '-',
                                'appointment_time' => $appointment->appointment_time ?? '-',
                                'specialization' => $appointment->specialization ?? '-',
                                'doctor' => $appointment->doctor ?? '-',
                                'booking_type' => $appointment->booking_type === 'post_op' ? 'postop' : ($appointment->booking_type ?? '-'),
                                'appointment_status' => $appointment->appointment_status ?? 'pending',
                                'hmis_visit_status' => $appointment->hmis_visit_status ?? 'pending',
                                'tracing_status' => $appointment->tracing_status ?? '-',
                                'notes' => $appointment->notes ?? '-',
                                'doctor_comments' => $appointment->doctor_comments ?? '-',
                                'hospital_branch' => $appointment->hospital_branch ?? '-',
                                'cancellation_reason' => $appointment->cancellation_reason ?? '-',
                                'visit_date' => $appointment->visit_date ? Carbon::parse($appointment->visit_date)->format('Y-m-d') : '-',
                                'created_at' => $appointment->created_at && is_string($appointment->created_at) && Carbon::canBeCreatedFromFormat($appointment->created_at, 'Y-m-d H:i:s')
                                    ? Carbon::parse($appointment->created_at)->format('Y-m-d H:i:s')
                                    : now()->format('Y-m-d H:i:s'),
                                'source_table' => $appointment->source_table ?? 'unknown',
                            ];
                        })->unique('id')->values();

                        Log::info('Appointments fetched for all after normalization:', [
                            'count' => $appointments->count(),
                            'first_item' => $appointments->first() ? json_encode($appointments->first()) : null,
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
                            'hmis_visit_status' => $appointment->hmis_visit_status ?? '-',
                            'notes' => $appointment->notes ?? '-',
                            'tracing_status' => $appointment->tracing_status ?? '-',
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
            $data['doctors'] = $doctors;
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
            $cancelledQuery = CancelledAppointment::whereBetween('appointment_date', [$startDate, $endDate]);;
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
            $specializationData = $specializations->map(function ($specialization) use ($isSuperadmin, $selectedBranch, $startDate, $endDate, $userBranch) {
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
            $data['doctors'] = $doctors;

            $view = 'booking.dashboard';
        }

        return view($view, $data);
    }
    // Internal booking submit
    // public function submitInternalAppointments(Request $request)
    // {
    //     $consents = $request->input('consent', []);
    //     $validated = $request->validate([
    //         'full_name' => 'required|string|max:255',
    //         'patient_number' => 'required|string|max:50',
    //         'email' => 'nullable|email|max:100',
    //         'appointment_date' => 'required|date',
    //         'appointment_time' => 'nullable',
    //         'specialization' => 'required|string|max:255',
    //         'doctor_name' => 'nullable|string|max:100',
    //         'booking_type' => 'required|in:new,post_op,review',
    //         'phone' => 'required|string|max:100',
    //         'hospital_branch' => 'required|in:kijabe,naivasha,westlands,marira',
    //     ]);

    //     $bkspecialization = BkSpecializations::where('name', $validated['specialization'])
    //         ->where('hospital_branch', $validated['hospital_branch'])
    //         ->first();

    //     $doctor = BkDoctor::where('doctor_name', $validated['doctor_name'])
    //         ->where('hospital_branch', $validated['hospital_branch'])
    //         ->first();
    //     $doctor_id = $doctor ? $doctor->id : null;

    //     if (!$bkspecialization) {
    //         return back()->withInput()->with('error', 'Specialization not found.');
    //     }

    //     // Check booking limits
    //     $appointmentDate = $validated['appointment_date'];
    //     $currentBookings = $this->getActiveBookingsForDate($validated['specialization'], $appointmentDate);

    //     if ($bkspecialization->limits && $currentBookings >= $bkspecialization->limits) {
    //         $errorMessage = "Cannot book appointment. The daily limit of {$bkspecialization->limits} appointments for {$validated['specialization']} on " .
    //             date('Y-m-d', strtotime($appointmentDate)) . " has been reached. Current bookings: {$currentBookings}.";

    //         // Fetch alternative dates
    //         //$alternativeDates = $this ->getAlternativeDates($validated['specialization'], $validated['hospital_branch'], $bkspecialization->limits);
    //         $alternativeDates = $this->getAlternativeDates($validated['specialization'], $validated['hospital_branch'], $bkspecialization->limits, $bkspecialization->day_of_week, $appointmentDate);

    //         \Log::warning("Appointment booking limit exceeded", [
    //             'specialization' => $validated['specialization'],
    //             'date' => $appointmentDate,
    //             'current_bookings' => $currentBookings,
    //             'limit' => $bkspecialization->limits,
    //             'hospital_branch' => $validated['hospital_branch'],
    //             'suggested_dates' => $alternativeDates,
    //         ]);

    //         return back()->withInput()->with([
    //             'error' => $errorMessage,
    //             'suggested_dates' => $alternativeDates,
    //         ]);
    //     }

    //     try {
    //         $appointmentNumber = 'APPT-' . date('Y') . '-' . Str::upper(Str::random(8));
    //         $appointmentData = [
    //             'appointment_number' => $appointmentNumber,
    //             'full_name' => $validated['full_name'],
    //             'patient_number' => $validated['patient_number'],
    //             'email' => $validated['email'],
    //             'phone' => $validated['phone'],
    //             'appointment_date' => $validated['appointment_date'],
    //             'appointment_time' => $validated['appointment_time'] . ':00',
    //             'specialization' => $bkspecialization->id,
    //             'doctor_name' => $validated['doctor_name'],
    //             'hospital_branch' => $validated['hospital_branch'],
    //             'booking_type' => $validated['booking_type'],
    //             'appointment_status' => 'pending',
    //             'consent' => isset($validated['consent']) ? implode(',', $validated['consent']) : null,
    //             'sms_check' => in_array('sms', $consents) ? 1 : 0,
    //             'whatsapp_check' => in_array('whatsapp', $consents) ? 1 : 0,
    //             'email_check' => in_array('email', $consents) ? 1 : 0,
    //             'doctor_id' => $doctor_id,
    //         ];

    //         $appointment = BkAppointments::create($appointmentData);

    //         \Log::info("Internal appointment booked", [
    //             'specialization' => $bkspecialization->name,
    //             'date' => $validated['appointment_date'],
    //             'appointment_id' => $appointment->id,
    //             'hospital_branch' => $validated['hospital_branch'],
    //             'bookings_after_creation' => $currentBookings + 1,
    //             'limit' => $bkspecialization->limits,
    //         ]);

    //         return redirect()->back()->with('success', 'Appointment booked successfully!');
    //     } catch (\Exception $e) {
    //         Log::error('Appointment creation failed', ['error' => $e->getMessage()]);
    //         return back()->withInput()->with('error', 'Failed to book appointment: ' . $e->getMessage());
    //     }
    // }


    // private function getAlternativeDates($specialization, $hospital_branch, $limit, $day_of_week, $start_date)
    // {
    //     $suggestions = [];
    //     $specialization_id = BkSpecializations::where('name', $specialization)
    //         ->where('hospital_branch', $hospital_branch)
    //         ->value('id');

    //     // Define day map for Carbon day numbers (0=Sunday, 1=Monday, ..., 6=Saturday)
    //     $dayMap = [
    //         'sunday' => 0,
    //         'monday' => 1,
    //         'tuesday' => 2,
    //         'wednesday' => 3,
    //         'thursday' => 4,
    //         'friday' => 5,
    //         'saturday' => 6,
    //     ];

    //     // Check if day_of_week is valid
    //     $isDailyDefault = !isset($dayMap[strtolower($day_of_week ?? '')]);
    //     $targetDayOfWeek = $isDailyDefault ? null : $dayMap[strtolower($day_of_week)];

    //     // Parse the requested start_date
    //     $currentDate = Carbon::parse($start_date)->startOfDay();

    //     // Get existing bookings for future dates
    //     $existingBookings = BkAppointments::selectRaw('appointment_date, COUNT(*) as total')
    //         ->where('specialization', $specialization_id)
    //         ->where('hospital_branch', $hospital_branch)
    //         ->where('appointment_date', '>=', $currentDate)
    //         ->where('appointment_status', '!=', 'rescheduled')
    //         ->groupBy('appointment_date')
    //         ->pluck('total', 'appointment_date')
    //         ->toArray();

    //     // Find the next five available dates
    //     $count = 0;
    //     for ($i = 0; $count < 5; $i++) {
    //         // Calculate the next date
    //         $nextDate = (clone $currentDate)->addDays($i + 1); // Start from the next day

    //         // If a valid day_of_week is provided, adjust to the next occurrence of that day
    //         if (!$isDailyDefault && $nextDate->dayOfWeek != $targetDayOfWeek) {
    //             //$nextDate->next($targetDayOfWeek); // Move to the next occurrence of the target
    //             $nextDate = (clone ($currentDate))->addDays(($i + 1) * 7); // Move to next week

    //         }

    //         $dateStr = $nextDate->format('Y-m-d');
    //         $totalBookings = isset($existingBookings[$dateStr]) ? $existingBookings[$dateStr] : 0;

    //         // Only include dates with available slots
    //         if ($totalBookings < $limit) {
    //             $suggestions[] = [
    //                 'appointment_date' => $dateStr,
    //                 'total' => $totalBookings,
    //                 'day_of_week' => $nextDate->format('l'),
    //                 'limit' => $limit,
    //             ];
    //             $count++;
    //         }
    //     }

    //     return $suggestions;
    // }


    // private function getTotalActiveBookingsForDate($specialization, $date)
    // {
    //     return BkAppointments::where('specialization', BkSpecializations::where('name', $specialization)->value('id'))
    //         ->where('appointment_date', $date)
    //         ->where('appointment_status', '!=', 'rescheduled')
    //         ->count();
    // }

    public function submitInternalAppointments(Request $request)
    {
        $consents = $request->input('consent', []);

        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'patient_number' => 'required|string|max:50',
                'email' => 'nullable|email|string',
                'appointment_date' => 'required|date|after_or_equal:today',
                'appointment_time' => 'nullable|string',
                'specialization' => 'required|string|exists:bk_specializations,name',
                'doctor_name' => 'nullable|string|max:100',
                'booking_type' => 'required|in:new,review,post_op',
                'phone' => 'required|string|max:100',
                'hospital_branch' => 'required|in:kijabe,naivasha,westlands,marira',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        }

        $specialization = BkSpecializations::where('name', $validated['specialization'])
            ->where('hospital_branch', $validated['hospital_branch'])
            ->firstOrFail();
        $appointmentDate = Carbon::parse($validated['appointment_date'])->toDateString();
        $selectedDay = strtolower(Carbon::parse($appointmentDate)->format('l'));

        // Check if the selected date is valid
        $allowedDays = $specialization->days_of_week ?? ['daily'];
        $dateLimit = BkSpecializationDateLimits::where('specialization_id', $specialization->id)
            ->where('date', $appointmentDate)
            ->first();
        $currentBookings = $this->getActiveBookingsForDate($specialization->name, $appointmentDate);
        $defaultLimit = $this->getDefaultLimit();
        $effectiveLimit = $dateLimit ? $dateLimit->daily_limit : ($specialization->limits ?? $defaultLimit);

        if (!in_array('daily', $allowedDays) && !in_array($selectedDay, $allowedDays)) {
            return $this->returnWithSuggestedDates(
                $request,
                "Booking is not allowed on this day for {$specialization->name}.",
                $specialization->id,
                $appointmentDate,
                $validated,
                'booking'
            );
        }

        if ($dateLimit && $dateLimit->is_closed) {
            return $this->returnWithSuggestedDates(
                $request,
                "The {$specialization->name} clinic is closed on this date.",
                $specialization->id,
                $appointmentDate,
                $validated,
                'booking'
            );
        }

        if ($currentBookings >= $effectiveLimit) {
            return $this->returnWithSuggestedDates(
                $request,
                "The appointment limit has been reached for {$specialization->name} on this date.",
                $specialization->id,
                $appointmentDate,
                $validated,
                'booking'
            );
        }

        // Proceed with appointment creation
        try {
            do {
                $appointmentNumber = 'APPT-' . date('Y') . '-' . Str::upper(Str::random(8));
            } while (BkAppointments::where('appointment_number', $appointmentNumber)->exists());

            $appointmentData = [
                'appointment_number' => $appointmentNumber,
                'full_name' => $validated['full_name'],
                'patient_number' => $validated['patient_number'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'appointment_date' => $appointmentDate,
                'appointment_time' => $validated['appointment_time'] ? $validated['appointment_time'] . ':00' : null,
                'specialization' => $specialization->id,
                'doctor_name' => $validated['doctor_name'],
                'hospital_branch' => $validated['hospital_branch'],
                'booking_type' => $validated['booking_type'],
                'appointment_status' => 'pending',
                'consent' => implode(',', $consents),
                'sms_check' => in_array('sms', $consents) ? 1 : 0,
                'checkbox_check' => in_array('whatsapp', $consents) ? 1 : 0,
                'email_check' => in_array('email', $consents) ? 1 : 0,
            ];

            $appointment = BkAppointments::create($appointmentData);

            Log::info('Internal appointment booked', [
                'specialization' => $specialization->name,
                'date' => $appointmentDate,
                'appointment_number' => $appointment->appointment_number,
                'hospital_branch' => $validated['hospital_branch'],
                'bookings_after_creation' => $currentBookings + 1,
                'limit' => $effectiveLimit,
            ]);

            $request->session()->forget(['suggested_dates', 'error', 'modal_context', 'modal_target']);

            return redirect()->back()->with('success', 'Appointment booked successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to book appointment', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Failed to book appointment: ' . $e->getMessage());
        }
    }
    // External booking submit
    public function submitExternalAppointment(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:100',
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
        Log::info('Attempting to approve appointment', ['appointment_number' => $appointmentNumber, 'input' => $request->all()]);

        $validated = $request->validate([
            'patient_number' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
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

        $pendingAppointment = ExternalPendingApproval::where('appointment_number', $appointmentNumber)->first();
        if (!$pendingAppointment) {
            Log::error('Pending appointment not found', ['appointment_number' => $appointmentNumber]);
            return redirect()->route('booking.dashboard.status', 'external_pending')->with('error', 'Pending appointment not found.');
        }

        $specialization = BkSpecializations::where('name', $validated['specialization'])
            ->where('hospital_branch', $validated['hospital_branch'])
            ->first();
        if (!$specialization) {
            Log::error('Specialization not found', ['specialization' => $validated['specialization']]);
            return back()->withInput()->with('error', "Specialization '{$validated['specialization']}' not found.");
        }

        $appointmentDate = Carbon::parse($validated['appointment_date'])->toDateString();
        $selectedDay = strtolower(Carbon::parse($appointmentDate)->format('l'));

        $allowedDays = $specialization->days_of_week ?? ['daily'];
        $dateLimit = BkSpecializationDateLimits::where('specialization_id', $specialization->id)
            ->where('date', $appointmentDate)
            ->first();
        $currentBookings = $this->getActiveBookingsForDate($specialization->name, $appointmentDate);
        $defaultLimit = $this->getDefaultLimit();
        $effectiveLimit = $dateLimit ? $dateLimit->daily_limit : ($specialization->limits ?? $defaultLimit);

        if (!in_array('daily', $allowedDays) && !in_array($selectedDay, $allowedDays)) {
            return $this->returnWithSuggestedDates(
                $request,
                "Booking is not allowed on this day for {$specialization->name}.",
                $specialization->id,
                $appointmentDate,
                $validated,
                'approve',
                ['appointment_id' => $appointmentNumber]
            );
        }

        if ($dateLimit && $dateLimit->is_closed) {
            return $this->returnWithSuggestedDates(
                $request,
                "The {$specialization->name} clinic is closed on this date.",
                $specialization->id,
                $appointmentDate,
                $validated,
                'approve',
                ['appointment_id' => $appointmentNumber]
            );
        }

        if ($currentBookings >= $effectiveLimit) {
            return $this->returnWithSuggestedDates(
                $request,
                "The appointment limit has been reached for {$specialization->name} on this date.",
                $specialization->id,
                $appointmentDate,
                $validated,
                'approve',
                ['appointment_id' => $appointmentNumber]
            );
        }

        DB::beginTransaction();
        try {
            $approvedData = [
                'appointment_status' => 'approved',
                'patient_number' => $validated['patient_number'],
                'booking_id' => $pendingAppointment->appointment_number,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'specialization' => $specialization->name,
                'doctor_name' => $validated['doctor_name'],
                'appointment_date' => $appointmentDate,
                'appointment_time' => $validated['appointment_time'] ? $validated['appointment_time'] . ':00' : null,
                'hospital_branch' => $validated['hospital_branch'],
                'patient_notified' => $validated['patient_notified'] ?? 0,
                'booking_type' => $validated['booking_type'],
                'notes' => $validated['notes'],
            ];

            $approvedAppointment = ExternalApproved::create($approvedData);
            Log::info('Created approved appointment', ['booking_id' => $approvedAppointment->booking_id, 'id' => $approvedAppointment->id]);

            $tracingStatus = ($validated['patient_notified'] ?? false) ? 'contacted' : 'no response';
            BkTracing::create([
                'appointment_id' => $approvedAppointment->id,
                'tracing_date' => now(),
                'status' => $tracingStatus,
                'message' => $tracingStatus === 'contacted' ? 'Patient notified during appointment approval.' : 'Patient not notified during appointment approval.',
            ]);

            $pendingAppointment->delete();
            Log::info('Deleted pending appointment', ['appointment_number' => $appointmentNumber]);

            DB::commit();

            // Send email outside transaction
            if ($approvedAppointment->email && $approvedAppointment->email !== 'N/A') {
                try {
                    $emailData = $approvedData;
                    $emailData['message'] = 'Your appointment has been approved. Please note that the appointment is subject to change based on availability or unforeseen circumstances. You will be notified in case of any updates or adjustments.';
                    $emailData['subject'] = 'Appointment Approval';
                    Mail::to($approvedAppointment->email)->send(new ContactFormMail($emailData));
                    Log::info("Approval email sent to {$approvedAppointment->email}", ['booking_id' => $approvedAppointment->booking_id]);
                } catch (\Exception $e) {
                    Log::warning('Failed to send approval email', [
                        'email' => $approvedAppointment->email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    // Optionally notify admin or log for manual follow-up
                }
            }

            $request->session()->forget(['suggested_dates', 'error', 'modal_context', 'modal_target', 'appointment_id']);
            return redirect()->route('booking.dashboard.status', 'external_approved')->with('success', 'Appointment approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve appointment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'appointment_number' => $appointmentNumber,
            ]);
            return back()->withInput()->with('error', 'Failed to approve appointment: ' . $e->getMessage());
        }
    }
    public function saveBookingTracing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:bk_appointments,id',
            'tracing_date' => 'required|date',
            'status' => 'required|in:contacted,no response,other',
            'message' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        BkTracing::create([
            'appointment_id' => $request->appointment_id,
            'tracing_date' => $request->tracing_date,
            'status' => $request->status,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Tracing info saved successfully!'
        ], 200);
    }
    public function view($id, $status)
    {
        $user = Auth::guard('booking')->user();
        $isSuperadmin = $user && $user->role === 'superadmin';
        $isAdmin = $user && $user->role === 'admin';
        $userBranch = $user ? $user->hospital_branch : null;

        // $specializations = BkSpecializations::where('hospital_branch', $userBranch)->orderBy('name')->get();
        $specializations = $isSuperadmin
            ? BkSpecializations::orderBy('name')->get()
            : BkSpecializations::where('hospital_branch', $userBranch)->orderBy('name')->get();

        $appointment = null;
        $source_table = request()->input('source_table', $status);

        // Validate and map status for external_pending_approvals
        if ($status === 'all' || $status === 'external_pending_approvals') {
            if ($source_table === 'external_pending_approvals') {
                $status = 'external_pending'; // Map to external_pending for querying
            }
        }

        switch ($status) {
            case 'new':
            case 'review':
            case 'postop':
                $appointment = BkAppointments::query()
                    ->select([
                        'bk_appointments.*',
                        'bk_specializations.name as specialization',
                    ])
                    ->join('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                    ->where('bk_appointments.id', $id)
                    ->first();
                break;
            case 'external_pending':
                $appointment = ExternalPendingApproval::where('id', $id)->where('status', 'pending')->first();
                break;
            case 'external_approved':
                $appointment = ExternalApproved::query()
                    ->select([
                        'external_approveds.*',
                        'bk_specializations.name as specialization',
                    ])
                    ->leftJoin('bk_specializations', 'external_approveds.specialization', '=', 'bk_specializations.name')
                    ->where('external_approveds.id', $id)
                    ->first();
                break;
            case 'cancelled':
                $appointment = CancelledAppointment::find($id);
                break;
            default:
                Log::warning("Invalid status for view: {$status}", ['id' => $id, 'source_table' => $source_table]);
                return response()->json(['error' => 'Invalid status provided.'], 400);
        }

        if (!$appointment) {
            Log::error('Appointment not found', ['id' => $id, 'status' => $status, 'source_table' => $source_table]);
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        // Add source_table to appointment for frontend use
        $appointment->source_table = $source_table;

        Log::info("Viewing appointment ID: {$id} with status: {$status}", [
            'email' => $appointment->email ?? 'Not set',
            'doctor_comments' => $appointment->doctor_comments ?? 'Not set',
            'specialization' => $appointment->specialization ?? 'Not set',
        ]);

        // Render the modal body
        $bodyHtml = view('booking.view', compact('appointment', 'status', 'specializations'))->render();

        // Render the footer buttons dynamically
        $footerHtml = '
        <button type="button" class="btn btn-sm" style="background-color: #6c757d;" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
        <button type="submit" form="update-form" class="btn btn-sm" style="background-color: #5bbbe1;"><i class="fas fa-save"></i> Save</button>';

        // Add Reapprove button for cancelled appointments
        if (($status === 'all' ? $appointment->source_table : $status) === 'cancelled') {
            $footerHtml .= '
            <form action="' . route('booking.reapprove', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) . '" method="POST" style="display: inline-block;" class="appointment-form" onsubmit="return confirm(\'Are you sure you want to reapprove this appointment? It will be moved back to its original status.\');">
                ' . csrf_field() . '
                ' . method_field('POST') . '
                <input type="hidden" name="status" value="' . ($status === 'all' ? $appointment->source_table : $status) . '">
                <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-check"></i> Reapprove</button>
            </form>';
        }

        // Add Clear button for reminders (non-cancelled appointments)
        if (Route::is('booking.reminders') && ($status !== 'cancelled' && ($status !== 'all' || $appointment->source_table !== 'cancelled')) && ($appointment->appointment_status ?? '') !== 'cancelled') {
            $footerHtml .= '
            <form action="' . route('booking.clear', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) . '" method="POST" style="display: inline-block;" class="appointment-form" onsubmit="return confirm(\'Are you sure you want to clear this reminder? It will be removed from the reminder list.\');">
                ' . csrf_field() . '
                ' . method_field('POST') . '
                <input type="hidden" name="status" value="' . ($status === 'all' ? $appointment->source_table : $status) . '">
                <button type="submit" class="btn btn-sm" style="background-color: #f0ad4e;"><i class="fas fa-check"></i> Clear</button>
            </form>';
        }

        // Add Delete button
        $footerHtml .= '
        <form action="' . route('booking.delete', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) . '" method="POST" style="display: inline-block;" class="appointment-form" onsubmit="return confirm(\'Are you sure you want to delete this appointment? This action cannot be undone.\');">
            ' . csrf_field() . '
            ' . method_field('DELETE') . '
            <input type="hidden" name="status" value="' . ($status === 'all' ? $appointment->source_table : $status) . '">
            <button type="submit" class="btn btn-sm" style="background-color: #dc3545;"><i class="fas fa-trash-alt"></i> Delete</button>
        </form>';

        // Add Cancel button (non-cancelled appointments)
        if (
            !Route::is('booking.reminders') &&
            ($status !== 'cancelled' && ($status !== 'all' || $appointment->source_table !== 'cancelled')) &&
            ($appointment->appointment_status ?? '') !== 'cancelled' &&
            ($appointment->appointment_status ?? '') !== 'rescheduled'
        ) {
            $footerHtml .= '
        <button type="button" class="btn btn-sm cancel-appointment" style="background-color: #6c757d;" 
                data-id="' . $appointment->id . '" 
                data-source-table="' . ($status === 'all' ? $appointment->source_table : $status) . '" 
                data-full-name="' . ($appointment->full_name ?? 'N/A') . '" 
                data-patient-number="' . ($appointment->patient_number ?? 'N/A') . '">
                <i class="fas fa-ban"></i> Cancel
        </button>';
        }

        // Add Reschedule button (non-cancelled appointments)
        if ($status !== 'cancelled' && ($status !== 'all' || $appointment->source_table !== 'cancelled') && ($appointment->appointment_status ?? '') !== 'cancelled') {
            $footerHtml .= '
            <button type="button" class="btn btn-sm btn-info openRescheduleModal" style="background-color: #6c757d;" data-bs-toggle="modal" data-bs-target="#rescheduleAppointmentModal"
                data-action="' . route('booking.reschedule', [$appointment->id, $status === 'all' ? $appointment->source_table : $status]) . '"
                data-id="' . $appointment->id . '"
                data-full_name="' . ($appointment->full_name ?? '') . '"
                data-patient_number="' . ($appointment->patient_number ?? '') . '"
                data-email="' . ($appointment->email ?? '') . '"
                data-phone="' . ($appointment->phone ?? '') . '"
                data-appointment_date="' . ($appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '') . '"
                data-appointment_time="' . ($appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') : '') . '"
                data-specialization="' . ($appointment->specialization ?? '') . '"
                data-doctor_name="' . ($appointment->doctor_name ?? $appointment->doctor ?? '') . '"
                data-booking_type="' . ($appointment->booking_type ?? '') . '">
                <i class="fas fa-calendar-alt"></i> Reschedule
            </button>';
        }

        if (request()->ajax()) {
            return response()->json([
                'html' => $bodyHtml,
                'footer' => $footerHtml,
                'appointment' => $appointment,
                'status' => $status,
            ]);
        }

        return view('booking.view', compact('appointment', 'status', 'specializations'));
    }
    public function edit($id, $status)
    {
        Log::info("Edit accessed", ['id' => $id, 'status' => $status]);
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
                Log::warning("Invalid status for edit: {$status}", ['id' => $id]);
                return redirect()->route('booking.dashboard')->with('error', 'Invalid status.');
        }

        return view('booking.view', compact('appointment', 'specializations', 'status'));
    }
    public function update(Request $request, $id)
    {
        $source_table = $request->input('source_table');
        $status = $request->input('status', $source_table); // Fallback to source_table if status is not provided
        $form_type = $request->input('form_type');
        $hospitalBranches = $this->getHospitalBranchEnumValues(); // Ensure this method exists
        // Log request data for debugging
        Log::info('Update request data:', [
            'id' => $id,
            'source_table' => $source_table,
            'status' => $status,
            'form_type' => $form_type,
            'all' => $request->all()
        ]);
        // Validation rules
        $rules = [
            'full_name' => 'required|string|max:100',
            'patient_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'phone' => 'required|string|max:100',
            'appointment_date' => 'nullable|date', // Nullable for rescheduled appointments
            'appointment_time' => 'nullable|date_format:H:i',
            'specialization' => 'required|string|max:100',
            'doctor_name' => 'nullable|string|max:100',
            'appointment_status' => 'required|in:pending,honoured,missed,late,cancelled',
            'booking_type' => 'nullable|in:new,review,post_op' . (in_array($source_table, ['new', 'review', 'postop']) && $source_table !== 'external_approved' ? '|required' : ''),
            'doctor_comments' => 'nullable|string|max:5000',
            'cancellation_reason' => 'nullable|string|max:5000' . ($request->input('appointment_status') === 'cancelled' ? '|required' : ''),
            'notes' => 'nullable|string|max:5000',
            'status_field' => 'nullable|in:pending,approved',
            'patient_notified' => 'nullable|in:0,1',
            'booking_id' => 'nullable|string|max:100',
            'hospital_branch' => 'nullable|in:' . implode(',', $hospitalBranches),
            'remarks' => 'nullable|string|max:5000',
            'source_table' => 'nullable|in:new,review,postop,external_pending,external_approved,cancelled,rescheduled',
            'form_type' => 'nullable|in:all_details',
            'visit_date' => 'nullable|date',
            'previous_date' => 'nullable|date',
            'previous_time' => 'nullable|date_format:H:i',
            'current_date' => 'nullable|date',
            'current_time' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string|max:5000',
        ];

        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for update:', [
                'id' => $id,
                'source_table' => $source_table,
                'errors' => $e->errors()
            ]);
            throw $e; // Let Laravel handle the 422 response
        }

        // Determine the model based on source_table
        $modelClass = match ($source_table) {
            'new', 'review', 'postop' => BkAppointments::class,
            'external_pending' => ExternalPendingApproval::class,
            'external_approved' => ExternalApproved::class,
            'cancelled' => CancelledAppointment::class,
            'rescheduled' => BkAppointments::class, // Adjust if you have a RescheduledAppointment model
            default => null,
        };

        if (!$modelClass) {
            \Log::error('Invalid source table', ['id' => $id, 'status' => $status, 'source_table' => $source_table]);
            return redirect()->back()->with('error', 'Invalid appointment source.');
        }

        // Find the appointment
        $appointment = $modelClass::find($id);
        if (!$appointment) {
            \Log::error('Appointment not found', ['id' => $id, 'status' => $status, 'source_table' => $source_table, 'model' => $modelClass]);
            return redirect()->back()->with('error', 'Appointment not found.');
        }

        // Check daily limits for specialization
        $specialization = BkSpecializations::where('name', $validated['specialization'])->first();
        $oldStatus = $source_table === 'external_pending' ? ($appointment->status ?? 'pending') : ($appointment->appointment_status ?? 'pending');
        $newStatus = $validated['appointment_status'];

        if ($specialization && $source_table !== 'rescheduled' && $oldStatus !== $newStatus) {
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

        // Prepare update data
        $updateData = [
            'full_name' => $validated['full_name'],
            'patient_number' => $validated['patient_number'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'specialization' => $specialization ? $specialization->id : $validated['specialization'],
            'doctor_name' => $validated['doctor_name'],
            'appointment_status' => $newStatus,
            'booking_type' => in_array($source_table, ['new', 'review', 'postop']) ? $validated['booking_type'] : ($appointment->booking_type ?? null),
            'notes' => $validated['notes'] ?? ($appointment->notes ?? null),
            'hospital_branch' => $validated['hospital_branch'] ?? ($appointment->hospital_branch ?? null),
            'visit_date' => $validated['visit_date'] ?? null,
        ];

        if ($source_table === 'rescheduled') {
            $updateData['previous_date'] = $validated['previous_date'] ?? ($appointment->previous_date ?? null);
            $updateData['previous_time'] = $validated['previous_time'] ? $validated['previous_time'] . ':00' : ($appointment->previous_time ?? null);
            $updateData['current_date'] = $validated['current_date'] ?? ($appointment->current_date ?? null);
            $updateData['current_time'] = $validated['current_time'] ? $validated['current_time'] . ':00' : ($appointment->current_time ?? null);
            $updateData['reason'] = $validated['reason'] ?? ($appointment->reason ?? null);
            $updateData['booking_type'] = 'rescheduled';
            unset($updateData['appointment_date']);
            unset($updateData['appointment_time']);
        } else {
            $updateData['appointment_date'] = $validated['appointment_date'];
            $updateData['appointment_time'] = $validated['appointment_time'] ? $validated['appointment_time'] . ':00' : null;
        }

        if (in_array($source_table, ['new', 'review', 'postop', 'external_approved'])) {
            $updateData['doctor_comments'] = $validated['doctor_comments'] ?? ($appointment->doctor_comments ?? null);
        }

        if ($newStatus === 'cancelled') {
            $updateData['cancellation_reason'] = $validated['cancellation_reason'] ?? ($appointment->cancellation_reason ?? null);
            $updateData['remarks'] = $validated['remarks'] ?? ($appointment->remarks ?? null);
        }

        if ($source_table === 'external_pending') {
            $updateData['status'] = $validated['status_field'] ?? ($appointment->status ?? 'pending');
            unset($updateData['appointment_status']);
        }

        if ($source_table === 'external_approved') {
            $updateData['patient_notified'] = $validated['patient_notified'] ?? ($appointment->patient_notified ?? 0);
            $updateData['booking_id'] = $validated['booking_id'] ?? ($appointment->booking_id ?? null);
        }

        DB::beginTransaction();
        try {
            if ($newStatus === 'cancelled' && $source_table !== 'cancelled') {
                $newAppointment = CancelledAppointment::create($updateData + [
                    'appointment_number' => $appointment->appointment_number ?? ($appointment->booking_id ?? null),
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'remarks' => $validated['remarks'] ?? null,
                ]);
                $appointment->delete();
                $newTableStatus = 'cancelled';
            } elseif ($source_table === 'external_pending' && $validated['status_field'] === 'approved') {
                $newAppointment = ExternalApproved::create($updateData + [
                    'booking_id' => $validated['booking_id'] ?? ($appointment->appointment_number ?? null),
                    'hospital_branch' => $validated['hospital_branch'] ?? ($appointment->hospital_branch ?? null),
                    'patient_notified' => $validated['patient_notified'] ?? 0,
                    'appointment_status' => $newStatus,
                ]);
                $appointment->delete();
                $newTableStatus = 'external_approved';
            } else {
                $appointment->update($updateData);
                $newTableStatus = $source_table;
            }

            \Log::info("Appointment updated/moved", [
                'id' => $id,
                'original_status' => $status,
                'source_table' => $source_table,
                'new_table_status' => $newTableStatus,
                'appointment_status' => $newStatus,
                'booking_type' => $updateData['booking_type'] ?? null,
                'form_type' => $form_type,
            ]);

            DB::commit();
            return redirect()->route('booking.dashboard.status', $newTableStatus)
                ->with('success', 'Appointment updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update appointment', [
                'id' => $id,
                'status' => $status,
                'source_table' => $source_table,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Failed to update appointment: ' . $e->getMessage());
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
            Log::error('Invalid status for cancellation', ['status' => $status]);
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
    //Mark selected appointments as 'missed'
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
    // Find an appointment by ID across all models
    protected function findAppointmentById($id)
    {
        $models = [
            'new' => BkAppointments::class,
            'review' => BkAppointments::class,
            'postop' => BkAppointments::class,
            'external_pending' => ExternalPendingApproval::class,
            'external_approved' => ExternalApproved::class,
            'cancelled' => CancelledAppointment::class,
            'rescheduled' => BkRescheduledAppointments::class,
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
    //Get the status from the model class
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
            case BkRescheduledAppointments::class:
                return 'rescheduled';
            default:
                Log::error('Unknown model class for status determination', ['class' => get_class($appointment)]);
                return 'all';
        }
    }
    public function reschedule(Request $request, $id)
    {
        Log::info('Attempting to reschedule appointment.', [
            'appointment_id' => $id,
            'request_data' => $request->except(['_token', '_method'])
        ]);

        $rules = [
            'full_name' => 'required|string|max:100',
            'patient_number' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:100',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'nullable|date_format:H:i',
            'specialization' => [
                'required',
                'string',
                Rule::exists('bk_specializations', 'name')->where(function ($query) use ($request) {
                    $hospital_branch = $request->input('hospital_branch', Auth::guard('booking')->user()->hospital_branch ?? 'kijabe');
                    return $query->where('hospital_branch', $hospital_branch);
                }),
            ],
            'doctor_name' => 'nullable|string|max:100',
            'booking_type' => 'required|in:new,review,post_op',
            'reason' => 'required|string|max:512',
            'hospital_branch' => 'nullable|in:kijabe,naivasha,westlands,marira',
        ];

        try {
            $validated = $request->validate($rules);
        } catch (ValidationException $e) {
            Log::warning('Validation failed during appointment reschedule.', [
                'appointment_id' => $id,
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        }

        $appointment = BkAppointments::findOrFail($id);

        $specialization = BkSpecializations::where('name', $validated['specialization'])
            ->where('hospital_branch', $validated['hospital_branch'] ?? Auth::guard('booking')->user()->hospital_branch ?? 'kijabe')
            ->first();

        if (!$specialization) {
            Log::warning('Specialization not found for reschedule.', [
                'appointment_id' => $id,
                'specialization_name' => $validated['specialization'],
                'hospital_branch' => $validated['hospital_branch']
            ]);
            return back()->withInput()->with('error', 'The selected specialization for the chosen hospital branch does not exist.');
        }

        $newDate = Carbon::parse($validated['appointment_date'])->toDateString();
        $selectedDay = strtolower(Carbon::parse($newDate)->format('l'));

        $allowedDays = $specialization->days_of_week ?? ['daily'];
        if (!is_array($allowedDays)) {
            $decodedDays = json_decode($allowedDays, true);
            $allowedDays = is_array($decodedDays) ? $decodedDays : ['daily'];
        }

        $dateLimit = BkSpecializationDateLimits::where('specialization_id', $specialization->id)
            ->where('date', $newDate)
            ->first();

        if (!in_array($selectedDay, $allowedDays) && !in_array('daily', $allowedDays)) {
            return $this->returnWithSuggestedDates(
                $request,
                "Booking is not allowed on " . Carbon::parse($newDate)->format('l, F jS, Y') . " for {$specialization->name}.",
                $specialization->id,
                $newDate,
                $validated,
                'reschedule',
                ['appointment_id' => $id]
            );
        }

        if ($dateLimit && $dateLimit->is_closed) {
            return $this->returnWithSuggestedDates(
                $request,
                "The {$specialization->name} clinic is closed on " . Carbon::parse($newDate)->format('F jS, Y') . ".",
                $specialization->id,
                $newDate,
                $validated,
                'reschedule',
                ['appointment_id' => $id]
            );
        }

        $currentBookings = $this->getActiveBookingsForDate($specialization->name, $newDate);
        $defaultLimit = $this->getDefaultLimit();
        $effectiveLimit = $dateLimit ? $dateLimit->daily_limit : ($specialization->limits ?? $defaultLimit);

        if ($currentBookings >= $effectiveLimit) {
            return $this->returnWithSuggestedDates(
                $request,
                "The daily limit has been reached for {$specialization->name} on " . Carbon::parse($newDate)->format('F jS, Y') . ".",
                $specialization->id,
                $newDate,
                $validated,
                'reschedule',
                ['appointment_id' => $id]
            );
        }

        do {
            $appointmentNumber = 'APPT-' . date('Y') . '-' . Str::upper(Str::random(8));
        } while (BkAppointments::where('appointment_number', $appointmentNumber)->exists());

        $newAppointmentData = [
            'appointment_number' => $appointmentNumber,
            'full_name' => $validated['full_name'],
            'patient_number' => $validated['patient_number'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'appointment_date' => $newDate,
            'appointment_time' => $validated['appointment_time'] ? $validated['appointment_time'] . ':00' : null,
            'specialization' => $specialization->id,
            'doctor_name' => $validated['doctor_name'],
            'booking_type' => $validated['booking_type'],
            'hospital_branch' => $validated['hospital_branch'],
            'appointment_status' => 'pending',
        ];

        DB::beginTransaction();
        try {
            $appointment->appointment_status = 'rescheduled';
            $appointment->save();

            $newAppointment = BkAppointments::create($newAppointmentData);
            BkRescheduledAppointments::create([
                'appointment_id' => $appointment->id,
                're_appointment_id' => $newAppointment->id,
                'reason' => $validated['reason'],
            ]);

            DB::commit();
            $request->session()->forget(['suggested_dates', 'error', 'modal_context', 'modal_target', 'appointment_id']);

            $status = $newAppointment->booking_type === 'post_op' ? 'postop' : $newAppointment->booking_type;

            return redirect()->route('booking.dashboard.status', $status)->with('success', 'Appointment rescheduled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reschedule appointment', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'new_appointment_data_attempted' => $newAppointmentData
            ]);
            return back()->withInput()->with('error', 'Failed to reschedule appointment: ' . $e->getMessage());
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
    public function specializationLimits(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $search = $request->input('search');
        $user = Auth::guard('booking')->user();
        $userBranch = $user ? $user->hospital_branch : null;

        $query = BkSpecializations::query()
            ->join('bk_specialization_group', 'bk_specializations.group_id', '=', 'bk_specialization_group.id')
            ->where('bk_specializations.hospital_branch', $userBranch)
            ->select([
                'bk_specializations.*',
                'bk_specialization_group.group_name as group_name',
            ]);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $specializations = $query->get();
        $specializations_group = BkSpecializationsGroup::all();

        $dateLimits = BkSpecializationDateLimits::whereIn('specialization_id', $specializations->pluck('id'))
            ->where('date', $date)
            ->get()
            ->keyBy('specialization_id');

        $activeBookings = [];
        foreach ($specializations as $specialization) {
            $activeBookings[$specialization->id] = $this->getActiveBookingsForDate($specialization->name, $date);
        }

        $default_limit = $this->getDefaultLimit();

        $data = [
            'title' => 'Specialization Schedule - ' . Carbon::parse($date)->format('F d, Y'),
            'specializations' => $specializations,
            'date' => Carbon::parse($date),
            'activeBookings' => $activeBookings,
            'specializations_group' => $specializations_group,
            'dateLimits' => $dateLimits,
            'default_limit' => $default_limit,
        ];

        return view('booking.specialization_limits', $data);
    }

    public function setDefaultLimit(Request $request)
    {
        $validated = $request->validate([
            'specialization_id' => 'nullable|exists:bk_specializations,id',
            'default_limit' => 'required|integer|min:0',
        ]);

        if ($validated['specialization_id']) {
            $specialization = BkSpecializations::findOrFail($validated['specialization_id']);
            $specialization->limits = $validated['default_limit'];
            $specialization->save();
            $message = "Default limit for {$specialization->name} set successfully.";
        } else {
            cache()->forever('default_specialization_limit', $validated['default_limit']);
            $message = 'Global default limit set successfully.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function updateSpecializationLimit(Request $request)
    {
        $validated = $request->validate([
            'specialization_id' => 'required|exists:bk_specializations,id',
            'daily_limit' => 'nullable|integer|min:0',
            'group_id' => 'required|exists:bk_specialization_group,id',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday,daily',
            'date' => 'required|date',
            'is_closed' => 'boolean',
        ]);

        $specialization = BkSpecializations::findOrFail($validated['specialization_id']);
        $specialization->group_id = $validated['group_id'];
        $days_of_week = $validated['days_of_week'] ?? ['daily'];
        $specialization->days_of_week = array_unique($days_of_week);
        $specialization->save();

        $selectedDate = Carbon::parse($validated['date']);
        $selectedDay = strtolower($selectedDate->format('l'));
        $isClosed = $request->has('is_closed') ? $validated['is_closed'] : false;

        if (isset($validated['daily_limit']) || $isClosed) {
            if (!$isClosed && !in_array('daily', $days_of_week) && !in_array($selectedDay, $days_of_week)) {
                return $this->returnWithSuggestedDates(
                    $request,
                    "Cannot set limit for {$specialization->name} on this date as it is not an allowed day.",
                    $specialization->id,
                    $selectedDate->toDateString(),
                    $validated,
                    'limit',
                    ['specialization_id' => $specialization->id]
                );
            }

            BkSpecializationDateLimits::updateOrCreate(
                [
                    'specialization_id' => $validated['specialization_id'],
                    'date' => $validated['date'],
                ],
                [
                    'daily_limit' => $isClosed ? 0 : ($validated['daily_limit'] ?? ($specialization->limits ?? $this->getDefaultLimit())),
                    'is_closed' => $isClosed,
                ]
            );
        }

        $request->session()->forget(['suggested_dates', 'error', 'modal_context', 'modal_target', 'specialization_id']);
        return redirect()->back()->with('success', 'Specialization settings updated successfully.');
    }

    public function toggleClinicClosure(Request $request)
    {
        $validated = $request->validate([
            'specialization_id' => 'required|exists:bk_specializations,id',
            'date' => 'required|date',
            'is_closed' => 'required|boolean',
        ]);

        $specialization = BkSpecializations::findOrFail($validated['specialization_id']);
        $defaultLimit = $this->getDefaultLimit();

        BkSpecializationDateLimits::updateOrCreate(
            [
                'specialization_id' => $validated['specialization_id'],
                'date' => $validated['date'],
            ],
            [
                'daily_limit' => $validated['is_closed'] ? 0 : ($specialization->limits ?? $defaultLimit),
                'is_closed' => $validated['is_closed'],
            ]
        );

        $message = $validated['is_closed'] ? 'Clinic closed successfully.' : 'Clinic reopened successfully.';
        return redirect()->back()->with('success', $message);
    }

    public function getSuggestedDatesForSpecialization(Request $request)
    {
        $validated = $request->validate([
            'specialization_id' => 'required|exists:bk_specializations,id',
            'from_date' => 'required|date',
        ]);

        $suggestedDates = $this->getSuggestedDates($validated['specialization_id'], $validated['from_date']);
        return response()->json(['suggested_dates' => $suggestedDates]);
    }

    private function getActiveBookingsForDate($specializationName, $date)
    {
        return BkAppointments::whereHas('specialization', function ($query) use ($specializationName) {
            $query->where('name', $specializationName);
        })->whereDate('appointment_date', $date)
            ->whereIn('appointment_status', ['pending', 'approved', 'rescheduled'])
            ->count();
    }

    public function clearSuggestedDates(Request $request)
    {
        $request->session()->forget(['suggested_dates', 'modal_target', 'error', 'modal_context', 'appointment_id', 'specialization_id']);
        return response()->json(['status' => 'success']);
    }

    private function getSuggestedDates($specializationId, $fromDate)
    {
        $specialization = BkSpecializations::findOrFail($specializationId);
        $allowedDays = $specialization->days_of_week ?? ['daily'];
        $startDate = Carbon::parse($fromDate)->addDay();
        $endDate = $startDate->copy()->addDays(30);
        $suggestedDates = [];
        $defaultLimit = $this->getDefaultLimit();

        for ($date = $startDate; $date <= $endDate && count($suggestedDates) < 10; $date->addDay()) {
            $dayLower = strtolower($date->format('l'));

            if (in_array('daily', $allowedDays) || in_array($dayLower, $allowedDays)) {
                $dateLimit = BkSpecializationDateLimits::where('specialization_id', $specializationId)
                    ->where('date', $date->toDateString())
                    ->first();
                if ($dateLimit && $dateLimit->is_closed) {
                    continue;
                }

                $currentBookings = $this->getActiveBookingsForDate($specialization->name, $date->toDateString());
                $effectiveLimit = $dateLimit ? $dateLimit->daily_limit : ($specialization->limits ?? $defaultLimit);

                if ($currentBookings < $effectiveLimit) {
                    $suggestedDates[] = [
                        'date' => $date->toDateString(),
                        'day' => $dayLower,
                        'limit' => $effectiveLimit,
                        'bookings' => $currentBookings
                    ];
                }
            }
        }

        return array_slice($suggestedDates, 0, 10);
    }
    public function selectSuggestedDate(Request $request)
    {
        $validated = $request->validate([
            'selected_date' => 'required|date|after_or_equal:today',
            'context' => 'required|in:booking,approve,reschedule,limit',
            'appointment_id' => 'nullable|string',
            'specialization_id' => 'nullable|exists:bk_specializations,id',
            'form_data' => 'required|array',
        ]);

        $context = $validated['context'];
        $formData = $validated['form_data'];
        $selectedDate = $validated['selected_date'];

        // Update the appointment_date in form_data
        $formData['appointment_date'] = $selectedDate;

        // Clear session data
        $request->session()->forget(['suggested_dates', 'error', 'modal_context', 'modal_target', 'appointment_id', 'specialization_id']);

        // Redirect based on context
        if ($context === 'booking') {
            return redirect()->route('booking.add')->withInput($formData);
        } elseif ($context === 'approve') {
            return redirect()->route('booking.dashboard.status', 'external_pending')
                ->withInput($formData)
                ->with([
                    'modal_target' => 'approveModal' . $validated['appointment_id'],
                    'modal_context' => 'approve',
                    'appointment_id' => $validated['appointment_id'],
                ]);
        } elseif ($context === 'reschedule') {
            // Merge form data into the request
            $request->merge($formData);
            $request->merge(['appointment_id' => $validated['appointment_id']]);

            // Call the reschedule method directly
            return $this->reschedule($request, $validated['appointment_id']);
        } elseif ($context === 'limit') {
            return redirect()->route('booking.specialization.limits')->withInput($formData);
        }

        return redirect()->back()->with('error', 'Invalid context provided.');
    }
    private function returnWithSuggestedDates(Request $request, $errorMessage, $specializationId, $appointmentDate, $formData, $context, $additionalData = [])
    {
        $suggestedDates = $this->getSuggestedDates($specializationId, $appointmentDate);

        // Update form data with suggested date if available
        $oldInputData = $formData;
        $oldInputData['appointment_date'] = !empty($suggestedDates) ? $suggestedDates[0]['date'] : null;

        // Store data in session
        $request->session()->flashInput($oldInputData);
        $request->session()->flash('error', $errorMessage);
        $request->session()->flash('suggested_dates', $suggestedDates);
        $request->session()->flash('modal_context', $context);
        $request->session()->flash('modal_target', 'suggestedDatesModal');
        foreach ($additionalData as $key => $value) {
            $request->session()->flash($key, $value);
        }

        // Return the modal view
        return view('booking.suggested_dates_modal', [
            'error' => $errorMessage,
            'suggested_dates' => $suggestedDates,
            'context' => $context,
            'appointment_id' => $additionalData['appointment_id'] ?? null,
            'specialization_id' => $additionalData['specialization_id'] ?? null,
        ]);
    }
    private function getDefaultLimit()
    {
        return cache()->get('default_specialization_limit', 10);
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
                    Log::info("Updated past appointment status", [
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
        $doctorId = $request->input('doctor_id');
        $doctorName = $request->input('doctor_name');
        $userBranch = Auth::guard('booking')->user()->hospital_branch;
        $isSuperadmin = Auth::guard('booking')->user()->is_superadmin ?? false;
        $selectedBranch = $isSuperadmin ? $request->input('branch', $userBranch) : $userBranch;
        $action = $request->input('action');

        \Log::info('Calendar Request', [
            'viewMode' => $viewMode,
            'date' => $date,
            'search' => $search,
            'doctorId' => $doctorId,
            'doctorName' => $doctorName,
            'userBranch' => $userBranch,
            'selectedBranch' => $selectedBranch,
            'isSuperadmin' => $isSuperadmin,
            'request_branch' => $request->input('branch'),
            'action' => $action,
            'all_params' => $request->all(), // Log all request parameters
        ]);

        // Handle AJAX request for doctors
        if ($request->ajax() && $action === 'get_doctors') {
            $doctors = $this->getDoctorsByBranch($selectedBranch);
            \Log::debug('Returning doctors for AJAX request', [
                'success' => true,
                'doctors_count' => count($doctors),
                'doctors_sample' => array_slice($doctors, 0, 3),
                'branch' => $selectedBranch,
            ]);
            return response()->json([
                'success' => true,
                'doctors' => $doctors
            ]);
        }

        // Handle AJAX request for calendar data
        if ($request->ajax()) {
            $appointments = $this->getAllAppointmentsForCalendar($date, $viewMode, $search, $selectedBranch, $doctorId, $doctorName);
            $bookingsData = $this->calculateBookingsData($appointments, $viewMode, $date);
            \Log::debug('Returning calendar data for AJAX request', [
                'calendar_data_count' => count($bookingsData),
                'appointments_count' => count($appointments),
                'date' => $date,
                'viewMode' => $viewMode,
            ]);
            return response()->json([
                'success' => true,
                'calendar_data' => $bookingsData,
                'appointments_by_doctor' => $this->groupAppointmentsByDoctor($appointments),
                'date' => $date,
                'month' => Carbon::parse($date)->format('Y-m'),
                'doctor_id' => $doctorId,
                'doctor_name' => $doctorName,
            ]);
        }

        $doctors = $this->getDoctorsByBranch($selectedBranch);
        $appointments = $this->getAllAppointmentsForCalendar($date, $viewMode, $search, $selectedBranch, $doctorId, $doctorName);
        $bookingsData = $this->calculateBookingsData($appointments, $viewMode, $date);

        $holidays = Holiday::when(Schema::hasColumn('holidays', 'hospital_branch'), function ($query) use ($selectedBranch) {
            return $query->where('hospital_branch', $selectedBranch);
        })->get()->map(function ($holiday) {
            return [
                'title' => $holiday->name,
                'start' => $holiday->date,
                'backgroundColor' => '#808080',
                'borderColor' => '#808080',
                'editable' => false,
            ];
        })->toArray();

        $upcomingAppointments = $this->getUpcomingAppointments($selectedBranch);
        $this->sendAppointmentReminders($selectedBranch);

        return view('booking.calendar', [
            'title' => 'Appointment Calendar',
            'bookingsData' => json_encode($bookingsData),
            'holidays' => json_encode($holidays),
            'upcomingAppointments' => $upcomingAppointments,
            'viewMode' => $viewMode,
            'currentDate' => $date,
            'currentMonth' => Carbon::parse($date)->format('Y-m'),
            'appointments' => $appointments,
            'userBranch' => $userBranch,
            'doctors' => $doctors,
            'selectedBranch' => $selectedBranch,
            'isSuperadmin' => $isSuperadmin,
            'hospitalBranches' => $this->getHospitalBranches(),
        ]);
    }
    protected function getDoctorsByBranch($branch)
    {
        \Log::debug('getDoctorsByBranch called', [
            'branch' => $branch,
            'is_empty' => empty($branch),
            'is_null' => is_null($branch),
        ]);

        $branch = $branch ? trim(strtolower($branch)) : null;
        $query = BkDoctor::select('id', 'doctor_name', 'department')
            ->orderBy('doctor_name');

        if ($branch) {
            $query->whereRaw('LOWER(TRIM(hospital_branch)) = ?', [$branch]);
            \Log::debug('Applying hospital_branch filter', ['branch' => $branch]);
        } else {
            \Log::warning('No branch provided, fetching all doctors');
        }

        $doctors = $query->get()
            ->map(function ($doctor) {
                return (object)[
                    'id' => $doctor->id,
                    'doctor_name' => trim($doctor->doctor_name),
                    'department' => $doctor->department ?? 'General',
                ];
            })
            ->unique('doctor_name')
            ->sortBy('doctor_name')
            ->values()
            ->toArray();

        // Fallback: Fetch doctor names from appointments if no doctors found
        if (empty($doctors) && $branch) {
            \Log::warning('No doctors found in bk_doctor, checking appointments', ['branch' => $branch]);
            $appointmentDoctors = BkAppointments::select('doctor_name')
                ->whereNotNull('doctor_name')
                ->whereRaw('LOWER(TRIM(hospital_branch)) = ?', [$branch])
                ->distinct()
                ->pluck('doctor_name')
                ->map(function ($name, $index) {
                    return (object)[
                        'id' => 'temp_' . $index, // Temporary ID
                        'doctor_name' => trim($name),
                        'department' => 'General',
                    ];
                })->toArray();

            $externalDoctors = ExternalApproved::select('doctor_name')
                ->whereNotNull('doctor_name')
                ->whereRaw('LOWER(TRIM(hospital_branch)) = ?', [$branch])
                ->distinct()
                ->pluck('doctor_name')
                ->map(function ($name, $index) use ($appointmentDoctors) {
                    return (object)[
                        'id' => 'temp_' . (count($appointmentDoctors) + $index),
                        'doctor_name' => trim($name),
                        'department' => 'General',
                    ];
                })->toArray();

            $doctors = collect(array_merge($appointmentDoctors, $externalDoctors))
                ->unique('doctor_name')
                ->sortBy('doctor_name')
                ->values()
                ->toArray();
        }

        \Log::info('Doctors fetched for branch', [
            'branch' => $branch,
            'count' => count($doctors),
            'sample' => array_slice($doctors, 0, 3),
            'query' => $query->toSql(),
            'bindings' => $query->getBindings(),
        ]);

        return $doctors;
    }
    protected function getAllAppointmentsForCalendar($date, $viewMode, $search = null, $branch = null, $doctorId = null, $doctorName = null)
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

        \Log::info('Fetching appointments for calendar', [
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'branch' => $branch,
            'doctorId' => $doctorId,
            'doctorName' => $doctorName,
            'viewMode' => $viewMode,
            'search' => $search,
        ]);

        $queries = [
            'new' => BkAppointments::query()
                ->select([
                    'bk_appointments.id',
                    'bk_appointments.full_name',
                    'bk_appointments.patient_number',
                    'bk_appointments.phone',
                    'bk_appointments.appointment_date',
                    'bk_appointments.appointment_time',
                    'bk_specializations.name as specialization',
                    DB::raw('COALESCE(bk_doctor.doctor_name, bk_appointments.doctor_name, "-") as doctor'),
                    'bk_appointments.booking_type',
                    'bk_appointments.appointment_status',
                ])
                ->leftJoin('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                ->leftJoin('bk_doctor', function ($join) {
                    $join->on('bk_appointments.doctor_id', '=', 'bk_doctor.id')
                        ->orOn(DB::raw('LOWER(bk_appointments.doctor_name)'), '=', DB::raw('LOWER(bk_doctor.doctor_name)'));
                })
                ->whereBetween('bk_appointments.appointment_date', [$startDate, $endDate])
                ->when($branch, function ($query) use ($branch) {
                    return $query->whereRaw('LOWER(TRIM(bk_appointments.hospital_branch)) = ?', [trim(strtolower($branch))]);
                })
                ->when($doctorId && !str_starts_with($doctorId, 'temp_'), function ($query) use ($doctorId) {
                    return $query->where('bk_appointments.doctor_id', $doctorId);
                })
                ->when($doctorName || ($doctorId && str_starts_with($doctorId, 'temp_')), function ($query) use ($doctorName, $doctorId) {
                    $name = $doctorName ?: ($doctorId ? BkDoctor::find($doctorId)->doctor_name ?? null : null);
                    if ($name) {
                        return $query->whereRaw('LOWER(TRIM(bk_appointments.doctor_name)) = ?', [trim(strtolower($name))]);
                    }
                }),
            'external_approved' => ExternalApproved::query()
                ->select([
                    'external_approveds.id',
                    'external_approveds.booking_id as appointment_number',
                    'external_approveds.full_name',
                    'external_approveds.patient_number',
                    'external_approveds.phone',
                    'external_approveds.appointment_date',
                    'external_approveds.appointment_time',
                    'bk_specializations.name as specialization',
                    DB::raw('COALESCE(bk_doctor.doctor_name, external_approveds.doctor_name, "-") as doctor'),
                    DB::raw("'external_approved' as booking_type"),
                    'external_approveds.appointment_status',
                ])
                ->leftJoin('bk_specializations', 'external_approveds.specialization', '=', 'bk_specializations.name')
                ->leftJoin('bk_doctor', function ($join) {
                    $join->on(DB::raw('LOWER(external_approveds.doctor_name)'), '=', DB::raw('LOWER(bk_doctor.doctor_name)'));
                })
                ->whereBetween('external_approveds.appointment_date', [$startDate, $endDate])
                ->when($branch, function ($query) use ($branch) {
                    return $query->whereRaw('LOWER(TRIM(external_approveds.hospital_branch)) = ?', [trim(strtolower($branch))]);
                })
                ->when($doctorName || ($doctorId && str_starts_with($doctorId, 'temp_')), function ($query) use ($doctorName, $doctorId) {
                    $name = $doctorName ?: ($doctorId ? BkDoctor::find($doctorId)->doctor_name ?? null : null);
                    if ($name) {
                        return $query->whereRaw('LOWER(TRIM(external_approveds.doctor_name)) = ?', [trim(strtolower($name))]);
                    }
                }),
        ];

        if ($search) {
            foreach ($queries as $type => $query) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('patient_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('specialization', 'like', "%{$search}%")
                        ->orWhereRaw('LOWER(doctor_name) LIKE ?', ["%{$search}%"]);
                });
            }
        }

        $allAppointments = [];
        $colorMap = [
            'new' => '#28a745',
            'external_approved' => '#17a2b8',
        ];

        foreach ($queries as $type => $query) {
            $appointments = $query->get()->map(function ($appt) use ($type, $colorMap) {
                $startDate = Carbon::parse($appt->appointment_date)->startOfDay();
                $start = $appt->appointment_time
                    ? $startDate->setTimeFromTimeString($appt->appointment_time)->toIso8601String()
                    : $startDate->toDateString();

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

            \Log::info("Appointments for type {$type}", [
                'count' => count($appointments),
                'sample' => array_slice($appointments, 0, 3),
            ]);

            $allAppointments = array_merge($allAppointments, $appointments);
        }

        return $allAppointments;
    }
    protected function groupAppointmentsByDoctor($appointments)
    {
        $grouped = [];
        foreach ($appointments as $appt) {
            $doctor = $appt['extendedProps']['doctor'] ?? 'Unknown';
            $specialization = $appt['extendedProps']['specialization'] ?? 'General';
            if (!isset($grouped[$doctor])) {
                $grouped[$doctor] = [
                    'doctor_name' => $doctor,
                    'specialization' => $specialization,
                    'total' => 0,
                    'reminder_status' => 'Not Sent', // Adjust based on your logic
                    'appointments' => [],
                ];
            }
            $grouped[$doctor]['appointments'][] = [
                'time' => $appt['extendedProps']['appointment_time'] ?? 'N/A',
                'patient' => $appt['title'],
                'details' => $appt['extendedProps']['patient_number'] . ' - ' . $appt['extendedProps']['specialization'],
                'status' => $appt['extendedProps']['appointment_status'] ?? 'Unknown',
            ];
            $grouped[$doctor]['total']++;
        }
        return array_values($grouped);
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
        $dateString = $date instanceof Carbon ? $date->toDateString() : $date;
        return collect($appointments)->filter(function ($appt) use ($dateString) {
            $apptDate = Carbon::parse($appt['start'])->toDateString();
            return $apptDate === $dateString;
        })->count();
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
        Log::info("Patient search accessed", $request->all());

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

            Log::info("Search applied with term: {$search} for branch: {$userBranch}");

            try {
                $appointments = $query->orderBy('bk_appointments.appointment_date', 'desc')->get();
                Log::info("Patient search results fetched: " . $appointments->count() . " for branch: {$userBranch}");
            } catch (\Exception $e) {
                Log::error("Patient search query failed: " . $e->getMessage(), [
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
            Log::info("No search term provided, returning empty results");
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
                Log::error("AJAX rendering failed for patient search: " . $e->getMessage());
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

        Log::info("User details:", [
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

        Log::info("Actual branches found in bk_appointments table:", $actualBranches);

        // Get total records per branch for debugging
        $branchCounts = DB::table('bk_appointments')
            ->select('hospital_branch', DB::raw('COUNT(*) as count'))
            ->groupBy('hospital_branch')
            ->get()
            ->pluck('count', 'hospital_branch')
            ->toArray();

        Log::info("Records per branch:", $branchCounts);

        // Define branch mapping (URL-friendly to database values)
        $branchMapping = [
            'kijabe' => 'kijabe',
            'naivasha' => 'naivasha',
            'westlands' => 'westlands',
            'marira' => 'marira'
        ];

        $urlBranch = strtolower($branch);
        Log::info("URL branch (lowercase): {$urlBranch}");

        // Validate branch parameter
        if (!array_key_exists($urlBranch, $branchMapping)) {
            Log::warning("Invalid branch provided: {$branch}. Valid options: " . implode(', ', array_keys($branchMapping)));
            return redirect()->route('booking.dashboard')
                ->with('error', 'Invalid hospital branch. Available: ' . implode(', ', array_keys($branchMapping)));
        }

        $branchName = $branchMapping[$urlBranch];
        Log::info("Mapped database branch name: {$branchName}");

        // Check if this branch actually has data
        if (!in_array($branchName, $actualBranches)) {
            Log::warning("No data found for branch: {$branchName}. Available branches with data: " . implode(', ', $actualBranches));
            // Continue anyway - might be a valid branch with no appointments yet
        }

        // Access control
        if ($userRole === 'superadmin') {
            Log::info("Admin access granted for branch: {$branchName}");
        } else {
            // Non-admin users can only see their own branch
            if ($userBranch !== $branchName) {
                Log::warning("Access denied: User from {$userBranch} trying to access {$branchName}");
                return redirect()->route('booking.dashboard')
                    ->with('error', 'Access denied. You can only view your branch data.');
            }
            Log::info("User access granted for own branch: {$branchName}");
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

            Log::info("SQL Query: " . $query->toSql());
            Log::info("Query Bindings: ", $query->getBindings());

            // Execute the query
            $appointments = $query->get();

            Log::info("Appointments fetched for branch {$branchName}: " . $appointments->count());

            // Debug the results
            if ($appointments->isNotEmpty()) {
                // Log booking type distribution
                $bookingTypes = $appointments->groupBy('booking_type')->map->count();
                Log::info("Booking type distribution:", $bookingTypes->toArray());

                // Log appointment status distribution  
                $statusDistribution = $appointments->groupBy('appointment_status')->map->count();
                Log::info("Appointment status distribution:", $statusDistribution->toArray());

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
                Log::info("Sample appointments:", $sampleAppointments->toArray());
            } else {
                Log::warning("No appointments found for branch: {$branchName}");

                // Check if any appointments exist at all
                $totalAppointments = DB::table('bk_appointments')->count();
                Log::info("Total appointments in database: {$totalAppointments}");

                if ($totalAppointments > 0) {
                    Log::info("Appointments exist but none for branch: {$branchName}");
                    Log::info("This might be normal if this branch has no appointments yet.");
                } else {
                    Log::warning("No appointments found in entire bk_appointments table!");
                }
            }
        } catch (\Exception $e) {
            Log::error("Query failed for branch {$branchName}: " . $e->getMessage(), [
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
            Log::info("Fetched specializations from bk_specialization table: " . $specializations->count());
        } catch (\Exception $e) {
            Log::warning("Could not fetch specializations from bk_specialization table: " . $e->getMessage());

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

            Log::info("Fetched specializations from bk_appointments table: " . $specializations->count());
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

        Log::info("=== BRANCH DASHBOARD DEBUG END ===");
        Log::info("Final data being passed to view:", [
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
                Log::warning('Could not fetch hospital_branch enum values from bk_appointments table');
                return ['kijabe', 'naivasha', 'westlands', 'marira'];
            }

            preg_match("/^enum\((.*)\)$/", $result[0]->Type, $matches);
            if (empty($matches[1])) {
                Log::warning('Invalid enum format for hospital_branch', ['type' => $result[0]->Type]);
                return ['kijabe', 'naivasha', 'westlands', 'marira'];
            }

            $enumValues = array_map(
                fn($value) => trim($value, "'"),
                explode(',', $matches[1])
            );

            Log::info('Fetched hospital_branch enum values from bk_appointments:', ['values' => $enumValues]);
            return $enumValues;
        } catch (\Exception $e) {
            Log::error('Error fetching enum values: ' . $e->getMessage());
            return ['kijabe', 'naivasha', 'westlands', 'marira'];
        }
    }
}
