@extends('layouts.app')

@section('content')
<meta name="description" content="Explore our short courses for healthcare professionals in 2025, including BLS, ACLS, PALS, and more. Enhance your skills with AIC Kijabe Hospital's certified programs.">
<meta name="keywords" content="short courses 2025, healthcare training, AIC Kijabe Hospital, BLS, ACLS, PALS, critical care, medical education, Kenya">
<meta name="author" content="AIC Kijabe Hospital">

<div class="container mx-auto my-12 p-6">
    <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">All Courses for 2025</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden table-responsive">
            <thead>
                <tr class="bg-[#159ed5] text-white">
                    <th class="w-1/6 py-3 px-4 text-left">Course Name</th>
                    <th class="w-1/6 py-3 px-4 text-left">Duration</th>
                    <th class="w-1/6 py-3 px-4 text-left">Eligible Cadres</th>
                    <th class="w-1/6 py-3 px-4 text-left">Cost</th>
                    <th class="w-1/6 py-3 px-4 text-left">Training Dates</th>
                    <th class="w-1/6 py-3 px-4 text-left">Apply</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <!-- Basic Life Support (BLS) -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Basic Life Support (BLS)</span><br>
                        <small>Skills for handling cardiac arrest and airway obstruction.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">1 day</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">All medical and non-medical personnel</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 6,500</td>
                    <td class="py-4 px-6" data-label="Training Dates">22 Jan, 3 Feb, 3 Mar, 31 Mar, 5 May, 2 Jun, 30 Jun, 4 Aug, 1 Sep, 6 Oct, 3 Nov, 1 Dec</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- Advanced Cardiac Life Support (ACLS) -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Advanced Cardiac Life Support (ACLS)</span><br>
                        <small>Advanced CPR skills for healthcare professionals.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">2 days</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Nurses, RCO, Medical Officers</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 12,500</td>
                    <td class="py-4 px-6" data-label="Training Dates">23-24 Jan, 4-5 Feb, 4-5 Mar, 1-2 Apr, 6-7 May, 3-4 Jun, 1-2 Jul, 5-6 Aug, 2-3 Sep, 7-8 Oct, 4-5 Nov, 2-3 Dec</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- Pediatrics Advanced Life Support (PALS) -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Pediatrics Advanced Life Support (PALS)</span><br>
                        <small>Skills for treating pediatric cardiopulmonary emergencies.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">3 days</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Nurses, RCO, Medical Officers</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 18,500</td>
                    <td class="py-4 px-6" data-label="Training Dates">10-12 Feb, 7-9 Apr, 9-11 Jun, 11-13 Aug, 13-15 Oct, 24-26 Nov</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- Heart Saver First Aid -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Heart Saver First Aid</span><br>
                        <small>Basics of first aid for common emergencies.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">1 day</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Non-medical personnel</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 10,000</td>
                    <td class="py-4 px-6" data-label="Training Dates">7 Feb, 7 Mar, 4 Apr, 9 May, 6 Jun, 4 Jul, 8 Aug, 5 Sep, 10 Oct, 7 Nov, 5 Dec</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- In-house Critical Care Training -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">In-house Critical Care Training</span><br>
                        <small>Comprehensive care for life-threatening conditions.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">1 month</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Nurses</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 35,000</td>
                    <td class="py-4 px-6" data-label="Training Dates">Offered monthly</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- ICU Rotation for Medical officers -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">ICU Rotation for Medical Officers</span><br>
                        <small>Rotation in ICU for medical officers.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">1 Month</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Medical Officers</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 30,000</td>
                    <td class="py-4 px-6" data-label="Training Dates">Offered monthly</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- Bubble C-PAP -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Bubble C-PAP (Continuous Positive Airway Pressure)</span><br>
                        <small>CPAP for newborns with minimal resources.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">1 day</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Health workers in Maternity and Newborn units</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 4,500</td>
                    <td class="py-4 px-6" data-label="Training Dates">Available on request</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- NCD Update Course -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">NCD Update Course</span><br>
                        <small>Evidence-based approach to non-communicable diseases.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">5 days</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Clinical Officers, Nurses, Medical Officers</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 15,000</td>
                    <td class="py-4 px-6" data-label="Training Dates">27&ndash;31 Jan, 17&ndash;21 Mar, 5&ndash;9 May</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- KAIROS Training -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">KAIROS Training</span><br>
                        <small>Foundational course on world Christian mission.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">5 days</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Open to medical and non-medical personnel</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 4,500</td>
                    <td class="py-4 px-6" data-label="Training Dates">20-24 Jan, 17-21 Mar, 16-20 Jun, 22-26 Sep</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- Writing Workshop -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Writing Workshop</span><br>
                        <small>Transform research projects into peer-reviewed publications.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">6 days (spread over 3 months)</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Open to medical and non-medical personnel</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 16,500</td>
                    <td class="py-4 px-6" data-label="Training Dates">Class A: 3-4 Apr, 8-9 May, 5-6 Jun<br>Class B: 11-12 Sep, 16-17 Oct, 13-14 Nov</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- Qualitative Research Methods and Analysis -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Qualitative Research Methods and Analysis</span><br>
                        <small>Learning in-depth data collection and analysis methods.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">2 days</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Open to medical and non-medical personnel</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 5,000</td>
                    <td class="py-4 px-6" data-label="Training Dates">6-7 Feb, 10-11 Jul</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                                <!-- Medical Education -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">Medical Education</span><br>
                        <small>Equip participants with teaching skills for small groups.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">4 days</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Open to medical personnel</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 15,000</td>
                    <td class="py-4 px-6" data-label="Training Dates">6-10 Jan, 24-28 Feb</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- National HIV Integrated Training Curriculum (NHITC) -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">National HIV Integrated Training Curriculum (NHITC)</span><br>
                        <small>Blended learning for managing HIV with different tracks.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">5-11 weeks online, 1-2 weeks placement</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Medical Officers, Clinical Officers, Nurses (Clinical Track)<br>Social Workers (Psychosocial Track)<br>Nutritionists (Nutrition Track)</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 50,000 (Clinical Track)<br>Ksh. 27,000 (Psychosocial/Nutrition Track)</td>
                    <td class="py-4 px-6" data-label="Training Dates">Monthly enrollment</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>

                <!-- TB in the Era of HIV -->
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6" data-label="Course Name">
                        <span class="font-bold text-[#159ed5]">TB in the Era of HIV</span><br>
                        <small>Skills for diagnosing and managing HIV-related TB.</small>
                    </td>
                    <td class="py-4 px-6" data-label="Duration">5 days</td>
                    <td class="py-4 px-6" data-label="Eligible Cadres">Medical Officers, Clinical Officers, Nurses</td>
                    <td class="py-4 px-6" data-label="Cost">Ksh. 10,000</td>
                    <td class="py-4 px-6" data-label="Training Dates">Class A: 24-28 Feb<br>Class B: 28 Apr - 2 May<br>Class C: 23-27 Jun<br>Class D: 8-12 Dec</td>
                    <td class="py-4 px-6" data-label="Apply">
                        <a href="{{ route('short-courses-application') }}" class="text-[#159ed5] font-semibold hover:underline">Apply</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">Note: The cost excludes accommodation. For more information, please contact us at <a href="mailto:spmgr@kijabehospital.org" class="text-[#159ed5] hover:underline">spmgr@kijabehospital.org</a> or call <a href="tel:0736222022" class="text-[#159ed5] hover:underline">0736-222-022</a> / <a href="tel:0709728039" class="text-[#159ed5] hover:underline">0709-728-039</a>.</p>
    </div>
</div>

<style>
    /* Desktop view */
    .table-responsive {
        display: table;
    }

    /* Mobile view */
    @media (max-width: 767px) {
        .table-responsive, .table-responsive thead, .table-responsive tbody, .table-responsive th, .table-responsive td, .table-responsive tr { 
            display: block; 
        }

        .table-responsive thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .table-responsive tr { 
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .table-responsive td { 
            border: none;
            position: relative;
            padding-left: 50%; 
        }

        .table-responsive td:before { 
            content: attr(data-label);
            position: absolute;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
        }

        .table-responsive td:nth-of-type(1):before { content: "Course Name:"; }
        .table-responsive td:nth-of-type(2):before { content: "Duration:"; }
        .table-responsive td:nth-of-type(3):before { content: "Eligible Cadres:"; }
        .table-responsive td:nth-of-type(4):before { content: "Cost:"; }
        .table-responsive td:nth-of-type(5):before { content: "Training Dates:"; }
        .table-responsive td:nth-of-type(6):before { content: "Apply:"; }
    }
</style>
@endsection