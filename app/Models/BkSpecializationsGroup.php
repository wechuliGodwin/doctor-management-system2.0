<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BkSpecializationsGroup extends Model
{
    protected $table = 'bk_specialization_group';

    protected $fillable = [
        'group_name',
    ];

    // Optional: Define relationship to specializations
    public function specializations()
    {
        return $this->hasMany(BkSpecializations::class, 'group_id');
    }
}
