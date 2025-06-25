<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchRequest extends Model
{
    protected $table = 'iserc_research';

    protected $fillable = [
        'pi_name',
        'pi_email',
        'pi_address',
        'pi_mobile',
        'human_subjects_training',
        'pi_cv_path',
        'co_investigator_1',
        'co_investigator_2',
        'co_investigator_3',
        'co_investigator_cv_paths',
        'research_proposal_path',
        'additional_documents_paths',
        'conflicts',
        'conflictExplanation',
        'dataSharing',
        'dataSharingAgreement',
        'identifiableData',
        'dataTransferAgreement',
        'supervisors',
        'localSupervisor',
        'department',
        'supervisorPhone',
        'supervisorEmail',
        'ethicalReview',
        'reviewDetails',
        'payment_reference',
    ];

    protected $casts = [
        'co_investigator_cv_paths'   => 'array',
        'additional_documents_paths' => 'array',
    ];
}