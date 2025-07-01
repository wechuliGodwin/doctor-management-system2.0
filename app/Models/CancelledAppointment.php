<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_number',
        'booking_id',
        'full_name',
        'email',
        'phone',
        'specialization',
        'doctor_name',
        'appointment_date',
        'appointment_time',
        'hospital_branch',
        'remarks',
        'notes',
        'cancellation_reason',
        'cancelled_at',
        'appointment_number',
        'appointment_status',
        'booking_type'
    ];
    protected $casts = [
        'appointment_date' => 'date',
        'cancelled_at' => 'datetime'
    ];
    public function approvedAppointment()
    {
        return $this->belongsTo(ExternalApproved::class, 'booking_id', 'booking_id');
    }
}
