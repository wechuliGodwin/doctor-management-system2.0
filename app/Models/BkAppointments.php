<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkAppointments extends Model
{
    protected $table = 'bk_appointments';
    protected $fillable = [
        'appointment_number',
        'full_name',
        'patient_number',
        'email',
        'appointment_date',
        'appointment_time',
        'specialization',
        'doctor_name',
        'doctor_id',
        'booking_type',
        'phone',
        'hospital_branch',
        'appointment_status',
        'doctor_comments',
        'rescheduled',
        'reminder_cleared',
        'visit_status',
        'visit_date',
        'sms_check',
        'whatsapp_check',
        'email_check',
        'hmis_visit_date',
        'hmis_department',
        'hmis_appointment_purpose',
        'hmis_doctor',
        'hmis_county',
        'hmis_visit_status',
    ];
    public function alerts()
    {
        return $this->hasMany(BkMessaging::class, 'appointment_id', 'id');
    }
    public function specialization()
    {
        return $this->belongsTo(BkSpecializations::class, 'specialization', 'id');
    }
}
