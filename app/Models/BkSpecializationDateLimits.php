<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkSpecializationDateLimits extends Model
{
    protected $table = 'bk_specialization_date_limits';

    protected $fillable = [
        'specialization_id',
        'date',
        'daily_limit',
        'is_closed',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];

    public function specialization()
    {
        return $this->belongsTo(BkSpecializations::class, 'specialization_id');
    }
}
