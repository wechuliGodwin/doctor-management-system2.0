<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIrecApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'researcher_unique_number' => 'required|string|max:255',
            'date_of_approval' => 'required|date',
            'date_of_renewal' => 'nullable|date',
            'reference_number_given' => 'required|string|max:255',
            'reference_number_2024' => 'required|string|max:255',
            'approval_number_2024' => 'required|string|max:255',
            'proposal_title' => 'required|string|max:255',
            'principal_investigator' => 'required|string|max:255',
            'new_resubmission' => 'nullable|string|max:255',
            'payment' => 'nullable|string|max:255',
            'end_of_study_data' => 'nullable|string|max:255',
            'approval_letter' => 'nullable|string|max:255',
            'kh_iserc_form' => 'nullable|string|max:255',
            'evaluation_1' => 'nullable|string|max:255',
            'evaluation_2' => 'nullable|string|max:255',
            'cvs_pi_co_pis' => 'nullable|string|max:255',
            'cv_co_pi' => 'nullable|string|max:255',
            'human_subjects_data_protection' => 'nullable|string|max:255',
            'annual_report' => 'nullable|file|max:2048', // Changed to file
        ];
    }
}
