<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkSpecializations extends Model
{
    protected $table = 'bk_specializations';
    protected $fillable = [
        'name',
        'group_id',
        'limits',
        'day_of_week',
        'hospital_branch',
    ];
    protected $casts = [
        'days_of_week' => 'array',
    ];

    public function dateLimits()
    {
        return $this->hasMany(BkSpecializationDateLimits::class, 'specialization_id');
    }

    public function specializationGroup()
    {
        return $this->belongsTo(BkSpecializationsGroup::class, 'group_id');
    }
}
