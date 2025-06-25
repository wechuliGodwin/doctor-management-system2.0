<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'overview',
        'symptoms',
        'causes',
        'risk_factors',
        'when_to_see_doctor',
        'image', 
    ];
}