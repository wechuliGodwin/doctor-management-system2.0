@extends('layouts.app')

@section('content')

<head>
    <!-- Meta tags remain the same -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISERC Research Request Form | AIC Kijabe Hospital</title>
    <meta name="description"
        content="Submit your ISERC research request form for ethical review at AIC Kijabe Hospital. We support ethical medical research and assist with submissions for ethical clearance.">
    <meta name="keywords"
        content="ISERC, ethical review, research request form, AIC Kijabe Hospital, research ethics, ethical clearance, medical research, healthcare research, principal investigator, ethical approval, research ethics form">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="ISERC Research Request Form | AIC Kijabe Hospital">
    <meta property="og:description"
        content="Submit your ISERC research request form for ethical review. AIC Kijabe Hospital supports research ethics and provides the platform for ethical research submissions.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.kijabehospital.org/iserc-request-form">
    <meta property="og:image" content="https://www.kijabehospital.org/images/iserc-banner.jpg">
    <meta property="og:site_name" content="AIC Kijabe Hospital">
</head>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 my-12">
    <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">ISERC Research Request Form</h1>
    <!-- CHECKLIST Collapsible Section -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <button id="toggleChecklist" class="w-full text-left flex items-center justify-between focus:outline-none">
            <h2 class="text-xl font-semibold">CHECKLIST</h2>
            <svg id="checklistIcon" class="w-6 h-6 transform transition-transform duration-200" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path id="iconPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                </path>
            </svg>
        </button>
        <div id="checklistContent" class="mt-4 hidden">
            <!-- Checklist content -->
            <div class="text-sm text-gray-700 space-y-4">
                <p>Please make sure that <strong>ALL</strong> of the items below have been fulfilled. The KH ISERC
                    Committee will <strong>NOT</strong> be able to review your proposal until all these items are
                    completed.</p>

                <ol class="list-decimal list-inside space-y-2">
                    <li>
                        <strong>For a research project that is NOT a thesis or an academic requirement:</strong>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li>Research Request Form filled completely</li>
                            <li>Study proposal/protocol including relevant appendices e.g., questionnaires, consent
                                forms, conflict-of-interest, etc.</li>
                            <li>Required CVs attached</li>
                            <li>Materials submitted in soft copy (Word documents)</li>
                            <li>LOCAL (Kijabe Hospital) research supervisor in addition to the external supervisor. This
                                can be any senior person in the respective department who has previous research
                                experience.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>For a research project that is a thesis or an academic requirement:</strong>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li>Research Request Form filled completely</li>
                            <li>Required CVs, questionnaires, consent forms, etc., attached</li>
                            <li>Study proposal/protocol and/or thesis proposal/protocol including relevant appendices
                                submitted in soft copy</li>
                            <li>The proposal <strong>MUST</strong> include the following headings:
                                <ul class="list-disc list-inside ml-4">
                                    <li>Literature review (if needed for academic requirement)</li>
                                    <li>Study aim and specific objectives</li>
                                    <li>Methodology, including design, sampling, and detailed statistical analysis</li>
                                    <li>Ethical considerations, including informed consent, confidentiality, anonymity
                                    </li>
                                    <li>Timeline and expenses</li>
                                    <li>Population</li>
                                    <li>References/Bibliography (if needed for academic requirement)</li>
                                    <li>Any questionnaires used (required!)</li>
                                    <li>Consent form in plain (Grade 5) English and Kiswahili</li>
                                </ul>
                            </li>
                            <li>LOCAL (Kijabe Hospital) research supervisor in addition to the external supervisor. This
                                can be any senior person in the respective department who has previous research
                                experience.</li>
                            <li><strong>TIMELINE:</strong> Please note that the KH ISERC Committee needs on average
                                one-month advance notice before a decision is made. <strong>DO NOT</strong> schedule
                                your project to start less than one month after the completed proposal is submitted!
                            </li>
                        </ul>
                    </li>
                    <li>
                        <strong>All authors/principal investigators MUST send proof of the following trainings before
                            receiving the official approval letter:</strong>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li>Good Clinical Practice</li>
                            <li>Data Protection</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Study proposal/protocol review charges by KH ISERC:</strong>
                        <table class="w-full text-sm text-left text-gray-700 mt-2 border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2">Applicant Category</th>
                                    <th class="py-2">Kijabe Internal Applicant</th>
                                    <th class="py-2">External Applicant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="py-2">Diploma-level student</td>
                                    <td class="py-2">KES 500</td>
                                    <td class="py-2">KES 2,000</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2">Degree-level student</td>
                                    <td class="py-2">KES 1,000</td>
                                    <td class="py-2">KES 5,000</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2">Master-Level student</td>
                                    <td class="py-2">KES 1,500</td>
                                    <td class="py-2">KES 7,500</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2">PhD level student</td>
                                    <td class="py-2">KES 2,000</td>
                                    <td class="py-2">KES 10,000</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2">Researcher/Consultant studies</td>
                                    <td class="py-2">KES 2,000</td>
                                    <td class="py-2">KES 10,000</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2">Collaborative studies</td>
                                    <td class="py-2">KES 10,000</td>
                                    <td class="py-2">KES 20,000</td>
                                </tr>
                                <tr>
                                    <td class="py-2">KH Nursing school</td>
                                    <td class="py-2" colspan="2">Annual institutional review of KES 10,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </li>
                    <li>
                        <strong>Payment Details:</strong>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li><strong>MPESA PAYBILL NUMBER</strong></li>
                            <li>Business Number: <strong>512900</strong></li>
                            <li>Account Number: <strong>013-09</strong></li>
                        </ul>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <!-- Here's where we display the success message -->
    @if (session('success'))
        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    <form action="{{ route('iserc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Principal Investigator Details -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Principal Investigator Details</h2>
            <div class="space-y-4">
                <div>
                    <label for="pi_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="pi_name" id="pi_name" required
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="pi_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="pi_email" id="pi_email" required
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="pi_address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="pi_address" id="pi_address" required
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="pi_mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                    <input type="text" name="pi_mobile" id="pi_mobile" required
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="human_subjects_training" class="block text-sm font-medium text-gray-700">Human Subjects Training Completed?</label>
                    <div class="mt-1">
                        <label class="inline-flex items-center">
                            <input type="radio" name="human_subjects_training" value="yes" required
                                class="form-radio text-indigo-600">
                            <span class="ml-2">Yes</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="human_subjects_training" value="no" required
                                class="form-radio text-indigo-600">
                            <span class="ml-2">No</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="pi_cv" class="block text-sm font-medium text-gray-700">Attach Principal Investigator CV</label>
                    <input type="file" name="pi_cv" id="pi_cv" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0 file:text-sm file:font-semibold
                       file:bg-[#159ed5] file:text-white hover:file:bg-[#117fb3]">
                </div>
            </div>
        </div>

        <!-- Co-Investigators Details -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mt-8 mb-4">Co-Investigators Details</h2>
            <div class="space-y-4">
                <div>
                    <label for="co_investigator_1" class="block text-sm font-medium text-gray-700">Co-Investigator 1 Full Name</label>
                    <input type="text" name="co_investigator_1" id="co_investigator_1"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="co_investigator_2" class="block text-sm font-medium text-gray-700">Co-Investigator 2 Full Name</label>
                    <input type="text" name="co_investigator_2" id="co_investigator_2"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm p-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="co_investigator_3" class="block text-sm font-medium text-gray-700">Co-Investigator 3 Full Name</label>
                    <input type="text" name="co_investigator_3" id="co_investigator_3"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm p-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="co_investigator_cvs" class="block text-sm font-medium text-gray-700">Attach Co-Investigator CV(s)</label>
                    <input type="file" name="co_investigator_cvs[]" id="co_investigator_cvs" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0 file:text-sm file:font-semibold
                       file:bg-[#159ed5] file:text-white hover:file:bg-[#117fb3]">
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-6">
        <!-- Research proposal -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Research Proposal Submission</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Please download the research proposal template, fill it out, sign, and upload the completed document below.</p>
                    <a href="{{ asset('templates/Research_Proposal_Template.docx') }}" download
                        class="inline-flex items-center px-4 py-2 bg-[#159ed5] text-white font-semibold rounded-md hover:bg-[#117fb3]">
                        Download Template
                    </a>
                </div>
                <div>
                    <label for="research_proposal" class="block text-sm font-medium text-gray-700">Upload Completed Research Proposal</label>
                    <input type="file" name="research_proposal" id="research_proposal" required accept=".doc,.docx,.pdf"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0 file:text-sm file:font-semibold
                        file:bg-[#159ed5] file:text-white hover:file:bg-[#117fb3]">
                </div>
            </div>
            <div class="space-y-4 mt-4">
                <h2 class="text-lg font-semibold mb-4">Additional uploads (Ensure you upload the Good Clinical Practice & Data Protection documents/certificates)</h2>
                <div>
                    <label for="research_proposal" class="block text-sm font-medium text-gray-700">Upload Document 1</label>
                    <input type="file" name="additional_documents[]" id="research_proposal" required accept=".doc,.docx,.pdf"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0 file:text-sm file:font-semibold
                        file:bg-[#159ed5] file:text-white hover:file:bg-[#117fb3]">
                </div>
                <div class="mt-4">
                    <label for="research_proposal" class="block text-sm font-medium text-gray-700">Upload Document 2</label>
                    <input type="file" name="additional_documents[]" id="research_proposal" required accept=".doc,.docx,.pdf"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0 file:text-sm file:font-semibold
                        file:bg-[#159ed5] file:text-white hover:file:bg-[#117fb3]">
                </div>
            </div>
        </div>
        <!-- Ethical review -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Ethical Review</h2>
            <div class="space-y-4">
                <div>
                    <label for="ethicalReview" class="block text-lg font-medium text-gray-700">Has this proposal previously been reviewed by any other ethical review committee?</label>
                    <div class="mt-2">
                        <input type="radio" name="ethicalReview" value="yes" class="mr-2" id="yesReview">
                        <label for="yesReview" class="mr-4">Yes</label>
                        <input type="radio" name="ethicalReview" value="no" class="mr-2" id="noReview">
                        <label for="noReview">No</label>
                    </div>
                </div>
                <div>
                    <label for="reviewDetails" class="block text-gray-600">If yes, please provide an explanation.</label>
                    <textarea name="reviewDetails" id="reviewDetails" rows="3"
                        class="mt-2 w-full p-2 border rounded-md" placeholder="Enter details here..."></textarea>
                </div>
            </div>
        </div>
        <!-- Data sharing -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-8">
        <h2 class="text-xl font-semibold mb-4">Data Sharing</h2>
        <div class="mb-6">
            <label for="dataSharing" class="block text-lg font-medium text-gray-700">Will this data be shared with investigators outside of Kijabe Hospital?</label>
            <div class="mt-2">
                <input type="radio" name="dataSharing" value="yes" class="mr-2" id="yesDataSharing">
                <label for="yesDataSharing" class="mr-4">Yes</label>
                <input type="radio" name="dataSharing" value="no" class="mr-2" id="noDataSharing">
                <label for="noDataSharing">No</label>
            </div>
            <div class="mt-2" id="dataSharingAgreementSection">
                <label for="dataSharingAgreement" class="block text-gray-600">If yes, please attach Data Sharing Agreement.</label>
                <input type="file" name="dataSharingAgreement" id="dataSharingAgreement" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0 file:text-sm file:font-semibold
                            file:bg-[#159ed5] file:text-white hover:file:bg-[#117fb3]">
            </div>
        </div>

    <div class="mb-6">
        <label for="identifiableData" class="block text-lg font-medium text-gray-700">Will identifiable data be transferred outside of Kijabe Hospital (outside of Kenya)?</label>
        <div class="mt-2">
            <input type="radio" name="identifiableData" value="yes" class="mr-2" id="yesIdentifiableData">
            <label for="yesIdentifiableData" class="mr-4">Yes</label>
            <input type="radio" name="identifiableData" value="no" class="mr-2" id="noIdentifiableData">
            <label for="noIdentifiableData">No</label>
        </div>
        <div class="mt-2" id="dataTransferAgreementSection">
            <label for="dataTransferAgreement" class="block text-gray-600">If yes, please share Data Transfer Agreement.</label>
            <input type="file" name="dataTransferAgreement" id="dataTransferAgreement" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0 file:text-sm file:font-semibold
                        file:bg-[#159ed5] file:text-white hover:file:bg-[#117fb3]">
        </div>
    </div>
</div>
<!-- Supervisors -->
<div class="bg-white shadow-md rounded-lg p-6 mt-8">
    <h2 class="text-xl font-semibold mb-4">Supervisors</h2>
    <div class="mb-6">
        <label for="supervisors" class="block text-lg font-medium text-gray-700">Supervisor Type</label>
        <div class="mt-2">
            <input type="radio" name="supervisors" value="external" class="mr-2" id="externalSupervisor">
            <label for="externalSupervisor" class="mr-4">External</label>
            <input type="radio" name="supervisors" value="kijabeHospital" class="mr-2" id="kijabeHospitalSupervisor">
            <label for="kijabeHospitalSupervisor">Kijabe Hospital</label>
        </div>
    </div>

    <div class="mb-6">
        <label for="localSupervisor" class="block text-lg font-medium text-gray-700">Local Supervisor (if applicable):</label>
        <input type="text" name="localSupervisor" id="localSupervisor" class="mt-2 w-full p-2 border rounded-md text-sm" placeholder="Enter name of local supervisor if applicable">
    </div>

    <div class="mb-6">
        <label for="department" class="block text-lg font-medium text-gray-700">Department:</label>
        <input type="text" name="department" id="department" class="mt-2 w-full p-2 border rounded-md text-sm" placeholder="Enter department">
    </div>

    <div class="mb-6">
        <label for="supervisorPhone" class="block text-lg font-medium text-gray-700">Supervisor Mobile Number:</label>
        <input type="text" name="supervisorPhone" id="supervisorPhone" class="mt-2 w-full p-2 border rounded-md text-sm" placeholder="Enter supervisor's mobile number">
    </div>

    <div class="mb-6">
        <label for="supervisorEmail" class="block text-lg font-medium text-gray-700">Supervisor Email Address:</label>
        <input type="email" name="supervisorEmail" id="supervisorEmail" class="mt-2 w-full p-2 border rounded-md text-sm" placeholder="Enter supervisor's email address">
    </div>
</div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-6">
    <!-- Conflicts of interest -->
        <div class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-xl font-semibold mb-4">Conflicts of Interest</h2>
            <div class="space-y-4">
                <div>
                    <label for="conflicts" class="block text-lg font-medium text-gray-700">Do any investigators have conflicts of interest (personal or financial)?</label>
                    <div class="mt-2">
                        <input type="radio" name="conflicts" value="yes" class="mr-2" id="yesConflicts">
                        <label for="yesConflicts" class="mr-4">Yes</label>
                        <input type="radio" name="conflicts" value="no" class="mr-2" id="noConflicts">
                        <label for="noConflicts">No</label>
                    </div>
                </div>
                <div>
                    <label for="conflictExplanation" class="block text-gray-600">If Yes, explain any financial or personal relationships.</label>
                    <textarea name="conflictExplanation" id="conflictExplanation" rows="3"
                        class="mt-2 w-full p-2 border rounded-md" placeholder="Enter explanation here..."></textarea>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-xl font-semibold mb-4">Payment information</h2>
            <div>
                <label for="payment_reference" class="block text-sm font-medium text-gray-700">Payment reference (MPESA)</label>
                <input type="text" name="payment_reference" id="payment_reference"placeholder="Enter mpesa reference number"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 shadow-sm sm:text-sm border border-gray-300 rounded-md">
                <p class="text-sm text-gray-600 mt-1">This payment will be validated. If you are exempted, please provide exemption code</p>
            </div>
        </div></script>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end mt-4">
        <button type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#159ed5] hover:bg-[#117fb3] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#159ed5]">
            Submit Research Request
        </button>
    </div>

</form>
</div>

<!-- JavaScript for collapsible checklist -->
<script>
    document.getElementById('toggleChecklist').addEventListener('click', function () {
        var content = document.getElementById('checklistContent');
        var icon = document.getElementById('checklistIcon');
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });
</script>
@endsection

