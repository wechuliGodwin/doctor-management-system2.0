<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkRescheduledAppointments extends Model
{
    protected $fillable = [
        'appointment_id',
        're_appointment_id',
        'reason',
        'created_at',
    ];

}
