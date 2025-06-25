<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkMessaging extends Model
{
    protected $table = 'bk_messaging';
    protected $fillable = [
        'appointment_id',
        'is_new_patient',
        'patient_name',
        'patient_number',
        'phone',
        'messaging_date',
        'urgent_message',
        'sender_name',
        'sender_department',
        'hospital_branch',
        'feedback',
        'recipient',
        'feedback_date',
        'active',
    ];

    public function appointment()
    {
        return $this->belongsTo(BkAppointments::class, 'appointment_id', 'id');
    }
}