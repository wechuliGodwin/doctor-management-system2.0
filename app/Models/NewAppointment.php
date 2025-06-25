<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'patient_number',
        'email',
        'phone',
        'appointment_date',
        'appointment_time',
        'specialization',
        'doctor_name',
        'booking_type',
        'hospital_branch',
        'appointment_status',
        'doctor_comments',
    ];
    protected $attributes = [
        'appointment_status' => 'pending', // Default value
    ];
}