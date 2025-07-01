<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkTracing extends Model
{
    protected $table = 'bk_tracing';

    protected $fillable = [
        'appointment_id',
        'source_table',
        'tracing_date',
        'status',
        'message',
    ];

    public function appointment()
    {
        return $this->belongsTo(BkAppointments::class, 'appointment_id', 'id');
    }
}
