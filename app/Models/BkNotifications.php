<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkNotifications extends Model
{
    protected $table = 'bk_notifications';
    
    protected $fillable = [
        'appointment_id',
        'notification_date',
        'appointment_time',
        'hospital_branch',
        'status',
        'message',
        'status_code',
        'status_desc'
    ];

    public function appointment()
    {
        return $this->belongsTo(BkAppointments::class, 'appointment_id');
    }
}