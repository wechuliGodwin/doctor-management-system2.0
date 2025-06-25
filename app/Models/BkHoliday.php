<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkHoliday extends Model
{
    protected $fillable = ['holiday_date', 'name', 'type', 'hospital_branch'];
}