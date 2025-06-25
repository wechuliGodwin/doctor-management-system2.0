<?
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyAppointment extends Model
{
    use HasFactory;

    protected $fillable = ['hmis_patient_number', 'doctor_id', 'time_slot', 'notes'];
}
