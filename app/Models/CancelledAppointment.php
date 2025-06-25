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
        'name',
        'email',
        'phone',
        'specialization',
        'doctor',
        'appointment_date',
        'appointment_time',
        'hospital_branch',
        'remarks',
        'notes',
        'cancellation_reason'
    ];

    public function approvedAppointment()
    {
        return $this->belongsTo(ExternalApproved::class, 'booking_id', 'booking_id');
    }
}