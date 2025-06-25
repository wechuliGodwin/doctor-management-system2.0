<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomatedScheduling extends Model
{
    protected $fillable = [
        'full_name',
        'patient_number',
        'phone',
        'email',
        'appointment_date',
        'visited_date',
        'purpose_of_visit',
        'booking_type',
        'appointment_status',
    ];
}
