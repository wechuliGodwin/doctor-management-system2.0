<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $primaryKey = 'specialization_id';
    protected $fillable = ['specialization_name' , 'daily_limit', 'appointments_today'];
}