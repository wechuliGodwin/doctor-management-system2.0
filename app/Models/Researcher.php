<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Researcher extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'institution',
        'unique_number', // Make sure this is fillable
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($researcher) {
            $researcher->unique_number = 'KHR' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        });
    }
}
