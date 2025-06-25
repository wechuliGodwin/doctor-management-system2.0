<?php

// In app/Models/ResearchDayRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchDayRegistration extends Model
{
    protected $table = 'research_day_registrations';

    protected $fillable = [
        'names',
        'phone_numbers',
        'emails',
        'co_investigators',
        'categories',
        'poster_path',
        'resubmission', // Added the new field
    ];

    // Convert the categories field to an array when retrieving
    protected $casts = [
        'categories' => 'array',
        'resubmission' => 'boolean', // Cast the resubmission to boolean for easier handling in PHP
    ];
}