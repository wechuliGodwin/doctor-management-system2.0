<?
namespace App\Http\Controllers;

use App\Models\Schedule; // Make sure to import the Schedule model
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'hmis_patient_number' => 'required|string',
            'doctor_id' => 'required|integer',
            'time_slot' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Create a new schedule record
        $schedule = new Schedule();
        $schedule->hmis_patient_number = $request->hmis_patient_number;
        $schedule->patient_name = $request->patient_name; // You can pass this from the frontend if needed
        $schedule->appointment_date = now(); // Set the current date for the appointment
        $schedule->payment_status = 'Pending'; // Default status, adjust as necessary
        $schedule->notes = $request->notes;
        $schedule->doctor_id = $request->doctor_id;
        $schedule->time_slot = $request->time_slot;

        // Generate a unique meeting ID
        $schedule->meeting_id = (string) Str::uuid(); // Generates a unique UUID

        // Save the schedule record
        $schedule->save();

        return response()->json(['success' => true, 'message' => 'Appointment saved successfully!']);
    }
}
