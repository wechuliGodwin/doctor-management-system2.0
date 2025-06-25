<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkCancellledAppointments extends Model
{
    protected $fillable = [
        'appointment_id',
        'cancellation_reason',
        'cancelled_at',
    ];

}
