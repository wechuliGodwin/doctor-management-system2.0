<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkSpecializations extends Model
{
    protected $fillable = [
        'name',
        'group_id',
        'limits',
        'day_of_week',
        'hospital_branch',
    ];

}
