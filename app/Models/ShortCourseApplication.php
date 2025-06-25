<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortCourseApplication extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'course'];

    // Other model methods can be defined here
}
