<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'slot_time', 'is_booked'];

    // Define relationship with Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
