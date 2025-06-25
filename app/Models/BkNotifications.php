<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkNotifications extends Model
{
    protected $fillable = [
        'appointment_id',
        'notification_date',
        'appointment_time',
        'status',
    ];

}
