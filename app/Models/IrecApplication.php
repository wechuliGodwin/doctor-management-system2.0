<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrecApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'researcher_unique_number',
        'date_of_approval',
        'date_of_renewal',
        'reference_number_given',
        'reference_number_2024',
        'approval_number_2024',
        'proposal_title',
        'principal_investigator',
        'new_resubmission',
        'payment',
        'end_of_study_data',
        'approval_letter',
        'kh_iserc_form',
        'evaluation_1',
        'evaluation_2',
        'cvs_pi_co_pis',
        'cv_co_pi',
        'human_subjects_data_protection',
        'annual_report',
        'last_reminder_sent_at',
    ];

    protected $casts = [
        'date_of_approval' => 'datetime',
        'date_of_renewal' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
    ];
}
