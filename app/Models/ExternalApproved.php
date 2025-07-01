<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalApproved extends Model
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
        'notes',
        'patient_notified',
        'appointment_status',
        'doctor_comments',
        'booking_type',
    ];

    protected $table = 'external_approveds';
    protected $guarded = [];

    // Ensure created_at is treated as a timestamp
    protected $dates = ['created_at', 'updated_at', 'appointment_date'];
    protected $attributes = [
        'appointment_status' => 'pending', // Default value
    ];
    public function pendingApproval()
    {
        return $this->belongsTo(ExternalPendingApproval::class, 'booking_id', 'appointment_number');
    }

    public function cancelledAppointment()
    {
        return $this->hasOne(CancelledAppointment::class, 'booking_id', 'booking_id');
    }
}
