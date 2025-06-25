<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Objective extends Model
{
    protected $fillable = ['objective', 'department', 'other_department', 'contact', 'name', 'email', 'phone'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($objective) {
            Log::info('Objective Model Data Before Creating:', $objective->toArray());
        });

        static::created(function ($objective) {
            Log::info('Objective Model Data After Creating:', $objective->toArray());
        });
    }
}