<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimApplication extends Model
{
    //fillable fields
    protected $fillable = ['name', 'email', 'phone', 'course'];
}

