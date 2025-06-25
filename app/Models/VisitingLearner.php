<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitingLearner extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * This is optional if following Laravel's naming conventions.
     */
    // protected $table = 'visiting_learners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_email',
        'phone_number',
        'current_institution',
        'location',
        'specialty',
        'year_of_training',
        'preferred_start_date',
        'preferred_end_date',
        'gender',
        'traveling_with_family',
        'preferred_specialty_option1',
        'preferred_specialty_option2',
        'preferred_specialty_option3',
        'preferred_specialty_other',
        'coordinating_organization',
        'referee1_name',
        'referee1_email',
        'referee2_name',
        'referee2_email',
        'goals',
        'prior_experience',
        'future_plans',
        'faith_practice',
        'additional_info',
        'passport_biodata_page',
        'academic_professional_certificate',
        'curriculum_vitae',
        'passport_size_photo',
        'md_certificate',
        'current_practising_licence',

    ];
}

