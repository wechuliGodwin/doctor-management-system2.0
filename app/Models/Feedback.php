<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_date',
        'referral_source',
        'referral_source_other',
        'overall_rating',
        'experience_reason',
        'service_rating', // Make sure this is included
        'case_handling_opinion',
        'statement_agreement', // Make sure this is included
        'improvement_suggestions',
        'future_contact',
        'full_name',
        'mobile_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'service_rating' => 'array',
        'statement_agreement' => 'array',
    ];
}