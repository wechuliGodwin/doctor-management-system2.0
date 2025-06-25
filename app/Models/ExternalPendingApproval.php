<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalPendingApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number',
        'full_name',
        'patient_number',
        'email',
        'phone',
        'appointment_date',
        'specialization',
        'notes',
        'status'
    ];

    public function approvedAppointment()
    {
        return $this->hasOne(ExternalApproved::class, 'booking_id', 'appointment_number');
    }
}