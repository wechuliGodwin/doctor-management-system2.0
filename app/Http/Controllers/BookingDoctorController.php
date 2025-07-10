<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\BkAppointments;
use App\Models\BkDoctor;
use App\Models\BkSpecializations;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingDoctorController extends Controller
{
    public function bookingDiary(Request $request)
    {
        try {
            $user = Auth::guard('booking')->user();

            // Check authentication
            if (!$user) {
                if ($request->ajax() || $request->has('ajax')) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return redirect()->route('login');
            }

            $isSuperadmin = $user && $user->role === 'superadmin';
            $isAdmin = $user && $user->role === 'admin';
            $userBranch = $user ? $user->hospital_branch : null;
            $selectedBranch = null;

            // Validate request inputs
            $validationRules = [
                'ajax' => 'nullable|boolean',
                'doctor_id' => 'nullable|exists:bk_doctor,id',
                'date' => 'nullable|date',
                'month' => 'nullable|date_format:Y-m',
            ];

            if ($isSuperadmin) {
                $validationRules['branch'] = 'nullable|in:kijabe,westlands,naivasha,marira';
                $request->validate($validationRules);
                $selectedBranch = $request->input('branch') ?? $userBranch;
            } else {
                $request->validate($validationRules);
                $selectedBranch = $userBranch;
            }

            $hospitalBranches = $this->getHospitalBranchEnumValues();
            $doctors = BkDoctor::when($selectedBranch, function ($query) use ($selectedBranch) {
                return $query->where('hospital_branch', $selectedBranch);
            })->orderBy('doctor_name')->get();

            // Handle AJAX request for calendar data or daily schedule
            if ($request->ajax() || $request->has('ajax')) {
                try {
                    $doctorId = $request->input('doctor_id');
                    $date = $request->input('date');
                    $month = $request->input('month', Carbon::now()->format('Y-m'));

                    if ($date) {
                        // Fetch appointments for a specific date

                        $query = BkAppointments::query()
                            ->join('bk_specializations', 'bk_appointments.specialization', '=', 'bk_specializations.id')
                            ->select([
                                'bk_appointments.id',
                                'bk_appointments.appointment_number',
                                'bk_appointments.full_name',
                                'bk_appointments.appointment_date',
                                'bk_appointments.appointment_time',
                                'bk_appointments.booking_type',
                                'bk_appointments.appointment_status',
                                'bk_appointments.specialization',
                                'bk_specializations.name as specialization_name',
                                'bk_appointments.doctor_name as doctor',
                                'bk_appointments.hospital_branch',
                            ])
                            ->where('bk_appointments.appointment_date', $date)
                            ->where('bk_appointments.appointment_status', '!=', 'rescheduled');

                        if ($doctorId) {
                            $doctorName = BkDoctor::findOrFail($doctorId)->doctor_name;
                            $query->where('doctor_name', $doctorName);
                        }

                        if ($selectedBranch) {
                            $query->where('bk_appointments.hospital_branch', $selectedBranch);
                        }

                        $appointments = $query->orderBy('appointment_time')->get();

                        // Group appointments by doctor for daily view
                        $appointmentsByDoctor = $appointments->groupBy('doctor')->map(function ($apps, $doctor) {
                            $doctorRecord = BkDoctor::where('doctor_name', $doctor)->first();

                            return [
                                'doctor_name' => $doctor,
                                'specialization' => $doctorRecord ? $doctorRecord->department ?? 'General' : 'General',
                                'total' => $apps->count(),
                                'appointments' => $apps->map(function ($app) {
                                    return [
                                        'time' => $app->appointment_time,
                                        'patient' => $app->full_name,
                                        'details' => $app->booking_type . ' - ' . ' (' . $app->specialization_name . ')',
                                        'status' => ucfirst($app->appointment_status),
                                    ];
                                })->toArray(),
                                'reminder_status' => 'Sent (' . Carbon::yesterday()->format('d/m/Y h:i A') . ' EAT)',
                            ];
                        })->values();

                        return response()->json([
                            'success' => true,
                            'appointments_by_doctor' => $appointmentsByDoctor,
                            'date' => $date,
                            'doctor_id' => $doctorId,
                        ])->header('Content-Type', 'application/json');

                    } else {
                        // Fetch appointment counts for the calendar month
                        $startOfMonth = Carbon::parse($month)->startOfMonth();
                        $endOfMonth = Carbon::parse($month)->endOfMonth();

                        $query = BkAppointments::query()
                            ->select([
                                'appointment_date',
                                DB::raw('COUNT(*) as appointment_count'),
                            ])
                            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
                            ->where('appointment_status', '!=', 'rescheduled')
                            ->groupBy('appointment_date');

                        if ($doctorId) {
                            $doctorName = BkDoctor::findOrFail($doctorId)->doctor_name;
                            $query->where('doctor_name', $doctorName);
                        }

                        if ($selectedBranch) {
                            $query->where('hospital_branch', $selectedBranch);
                        }

                        $results = $query->get();
                        $calendarData = [];

                        foreach ($results as $result) {
                            $calendarData[$result->appointment_date] = (int) $result->appointment_count;
                        }

                        return response()->json([
                            'success' => true,
                            'calendar_data' => $calendarData,
                            'month' => $month,
                            'doctor_id' => $doctorId,
                        ])->header('Content-Type', 'application/json');
                    }
                } catch (\Exception $e) {
                    Log::error("AJAX request failed: " . $e->getMessage(), [
                        'request_data' => $request->all(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'error' => 'Error fetching data: ' . $e->getMessage()
                    ], 500)->header('Content-Type', 'application/json');
                }
            }

            // Default view data for non-AJAX requests
            $data = [
                'doctors' => $doctors,
                'hospitalBranches' => $hospitalBranches,
                'selectedBranch' => $selectedBranch,
                'isSuperadmin' => $isSuperadmin,
                'isAdmin' => $isAdmin,
                'currentMonth' => Carbon::now()->format('Y-m'),
            ];

            return view('booking.doctor_diary', $data);

        } catch (\Exception $e) {
            Log::error("Controller error: " . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->has('ajax')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Server error occurred'
                ], 500)->header('Content-Type', 'application/json');
            }

            return back()->with('error', 'An error occurred while loading the diary.');
        }
    }

    private function getHospitalBranchEnumValues()
    {
        return ['kijabe', 'westlands', 'naivasha', 'marira'];
    }
}