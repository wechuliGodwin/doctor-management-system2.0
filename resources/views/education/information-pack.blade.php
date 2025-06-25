@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8 font-roboto">AIC Kijabe Hospital Research Information Pack</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 font-roboto">

        <!-- Communication with KH ISERC -->
        <div class="bg-white rounded-lg p-6 shadow-[0_4px_6px_-1px_rgba(21,158,213,0.1),0_2px_4px_-2px_rgba(21,158,213,0.1)]">
            <h2 class="text-xl font-semibold mb-4 text-[#159ed5]">Communication with KH ISERC</h2>
            <p>The Research Coordinator (<a href="mailto:researchcoord@kijabehospital.org" class="text-[#159ed5] hover:underline">researchcoord@kijabehospital.org</a>) is the only official channel for protocol submission matters. Please <strong>DO NOT</strong> communicate directly with any other KH ISERC member.</p>
        </div>

        <!-- Scope of Review -->
        <div class="bg-white rounded-lg p-6 shadow-[0_4px_6px_-1px_rgba(21,158,213,0.1),0_2px_4px_-2px_rgba(21,158,213,0.1)]">
            <h2 class="text-xl font-semibold mb-4 text-[#159ed5]">Scope of Review</h2>
            <p>KH ISERC reviews clinical studies involving human or animal participants. Other research fields are not evaluated by this committee.</p>
        </div>

        <!-- Review Schedule -->
        <div class="bg-white rounded-lg p-6 shadow-[0_4px_6px_-1px_rgba(21,158,213,0.1),0_2px_4px_-2px_rgba(21,158,213,0.1)] col-span-1 md:col-span-2">
            <h2 class="text-xl font-semibold mb-4 text-[#159ed5]">Review Schedule</h2>
            <p>Protocols are reviewed monthly, except in December. Submissions must be made in the first week of the month for that month's review. No emergency reviews are conducted except in cases of medical emergencies like disasters or pandemics.</p>
        </div>

        <!-- Summary of Review Process -->
        <div class="bg-white rounded-lg p-6 shadow-[0_4px_6px_-1px_rgba(21,158,213,0.1),0_2px_4px_-2px_rgba(21,158,213,0.1)] col-span-1 md:col-span-2">
            <h2 class="text-xl font-semibold mb-4 text-[#159ed5]">Summary of Review Process</h2>
            <ol class="list-decimal ml-6 space-y-2">
                <li><strong>Submission:</strong> Proposals are submitted via <a href="https://kijabehospital.org/education/iserc" class="text-[#159ed5] hover:underline">our portal</a> with all necessary documents.</li>
                <li><strong>Pre-review:</strong> Initial review by the coordinator to check for completeness.</li>
                <li><strong>Review Type Determination:</strong> Based on risk and nature of the study.</li>
                <li><strong>Full Committee Review:</strong> If applicable, discussed in a meeting.</li>
                <li><strong>Decision:</strong> Approval, modification request, or rejection communicated within one week.</li>
                <li><strong>Post-Approval Monitoring:</strong> Compliance with additional requirements.</li>
                <li><strong>Renewal & Amendments:</strong> Changes must be submitted for re-evaluation. Annual renewals required for multi-year studies.</li>
            </ol>
        </div>

        <!-- Channels of Communication -->
        <div class="bg-white rounded-lg p-6 shadow-[0_4px_6px_-1px_rgba(21,158,213,0.1),0_2px_4px_-2px_rgba(21,158,213,0.1)]">
            <h2 class="text-xl font-semibold mb-4 text-[#159ed5]">Channels of Communication</h2>
            <p>Use formal channels for all inquiries. Avoid direct, informal communication with ISERC members to maintain process integrity. Such actions could lead to proposal rejection.</p>
        </div>

        <!-- Resubmission Timelines -->
        <div class="bg-white rounded-lg p-6 shadow-[0_4px_6px_-1px_rgba(21,158,213,0.1),0_2px_4px_-2px_rgba(21,158,213,0.1)]">
            <h2 class="text-xl font-semibold mb-4 text-[#159ed5]">Resubmission Timelines</h2>
            <p>Feedback must be addressed, and resubmission should occur within one month, max 90 days. Beyond this, proposals are treated as new submissions.</p>
        </div>

    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('research-home') }}" class="inline-block px-6 py-3 bg-[#159ed5] text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
            Back to Research Home
        </a>
    </div>
</div>
@endsection