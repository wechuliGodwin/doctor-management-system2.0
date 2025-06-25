@component('mail::message')
# New Visiting Learner Application

A new visiting learner has submitted an application. Here are the details:

## Personal Information
- **Name:** {{ $application['name'] }}
- **Email:** {{ $application['contact_email'] }}
- **Phone Number:** {{ $application['phone_number'] ?? 'N/A' }}
- **Current Institution:** {{ $application['current_institution'] ?? 'N/A' }}
- **Location:** {{ $application['location'] ?? 'N/A' }}

## Rotation Information
- **Specialty:** {{ $application['specialty'] ?? 'N/A' }}
- **Year of Training:** {{ $application['year_of_training'] ?? 'N/A' }}
- **Preferred Start Date:**
{{ $application['preferred_start_date'] ? \Carbon\Carbon::parse($application['preferred_start_date'])->format('F j, Y') : 'N/A' }}
- **Preferred End Date:**
{{ $application['preferred_end_date'] ? \Carbon\Carbon::parse($application['preferred_end_date'])->format('F j, Y') : 'N/A' }}
- **Gender:** {{ $application['gender'] ?? 'N/A' }}
- **Traveling with Family:** {{ $application['traveling_with_family'] ? 'Yes' : 'No' }}

## Preferred Specialties
1. **Option 1:** {{ $application['preferred_specialty_option1'] }}
2. **Option 2:** {{ $application['preferred_specialty_option2'] ?? 'N/A' }}
3. **Option 3:** {{ $application['preferred_specialty_option3'] ?? 'N/A' }}
4. **Other:** {{ $application['preferred_specialty_other'] ?? 'N/A' }}

## Coordinating Organization
{{ $application['coordinating_organization'] ?? 'N/A' }}

## Referees
- **Referee 1:** {{ $application['referee1_name'] ?? 'N/A' }} ({{ $application['referee1_email'] ?? 'N/A' }})
- **Referee 2:** {{ $application['referee2_name'] ?? 'N/A' }} ({{ $application['referee2_email'] ?? 'N/A' }})

## Additional Information
- **Goals:** {{ $application['goals'] ?? 'N/A' }}
- **Prior Experience:** {{ $application['prior_experience'] ?? 'N/A' }}
- **Future Plans:** {{ $application['future_plans'] ?? 'N/A' }}
- **Faith Practice:** {{ $application['faith_practice'] ?? 'N/A' }}
- **Additional Info:** {{ $application['additional_info'] ?? 'N/A' }}

## Uploaded Files
@component('mail::panel')
@php
    $fileFields = [
        'passport_biodata_page' => 'Passport Biodata Page',
        'academic_professional_certificate' => 'Academic/Professional Certificate',
        'curriculum_vitae' => 'Curriculum Vitae',
        'passport_size_photo' => 'Passport Size Photo',
        'md_certificate' => 'MD Certificate',
        'current_practising_licence' => 'Current Practising Licence',
    ];
@endphp

@foreach ($fileFields as $field => $label)
    - **{{ $label }}:**
    @if(!empty($application[$field]))
        @php
            $filePath = 'storage/' . $application[$field];
            $fileUrl = asset($filePath);
        @endphp
        [Download ]({{ $fileUrl }})
    @else
        @if(in_array($field, ['md_certificate', 'current_practising_licence']) && empty($application['md_certificate']) && empty($application['current_practising_licence']))
            N/A
        @else
            Not Uploaded
        @endif
    @endif
@endforeach
@endcomponent

@component('mail::button', ['url' => route('visiting-learners.index')])
View All Applications
@endcomponent

Thanks,
**{{ config('app.name') }}**

---

© {{ date('Y') }} **{{ config('app.name') }}**. All rights reserved.
@endcomponent
