<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Appointment extends Model
{

	protected $fillable = [
        'doctor_id',
        'patient_id',
        'service_id',
        'appointment_date',
        'description',
        'mpesa_code',
        'payment_status',
        'status',
        'platform', // Add the platform field here
        'path',
	'hmis_patient_number',
	'prescribe',
	'dosage',
	'quantity',
	'duration'
    ];

    // Define the relationship with the Doctor model
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Define the relationship with the Patient (User) model
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Define the relationship with the Service model
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    //define a one-to-many relation that points back to users model
    public function  User() {
        return $this->belongsTo(User::class);
    }
}
