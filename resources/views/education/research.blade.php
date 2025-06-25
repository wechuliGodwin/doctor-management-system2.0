@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 p-6">
    <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Research at AIC Kijabe Hospital</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Information Pack -->
        <a href="{{ route('information-pack') }}" class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 block">
            <h2 class="text-2xl font-medium mb-4 text-[#159ed5]">Information Pack</h2>
            <p class="text-gray-700 mb-4 font-normal">Get all the information you need about protocol review at AIC Kijabe Hospital.</p>
            <span class="text-[#159ed5] underline font-medium">See more</span>
        </a>

        <!-- Research Pathways -->
        <a href="{{ route('pathways') }}" class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 block">
            <h2 class="text-2xl font-medium mb-4 text-[#159ed5]">Research Pathways</h2>
            <p class="text-gray-700 mb-4 font-normal">Learn more about research pathways at AIC Kijabe Hospital and engage in meaningful healthcare research.</p>
            <span class="text-[#159ed5] underline font-medium">See more</span>
        </a>

        <!-- REDCap Account Request -->
        <a href="https://redcap.kijabehospital.org/surveys/?s=MC4PPTWD4RCKXXP8" class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 block">
            <h2 class="text-2xl font-medium mb-4 text-[#159ed5]">REDCap Account Request</h2>
            <p class="text-gray-700 mb-4 font-normal">If you need a REDCap account, please submit your request through the following form.</p>
            <span class="text-[#159ed5] underline font-medium">See more</span>
        </a>

        <!-- Research Support Request -->
        <a href="https://redcap.kijabehospital.org/surveys/?s=7YTTR4R9TC8CWLCN" class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 block">
            <h2 class="text-2xl font-medium mb-4 text-[#159ed5]">Research Support Request</h2>
            <p class="text-gray-700 mb-4 font-normal">If you need assistance with your research, please submit a support request through the following form.</p>
            <span class="text-[#159ed5] underline font-medium">See more</span>
        </a>

        <!-- ISERC Research Request Form -->
        <a href="{{ route('iserc') }}" class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 block">
            <h2 class="text-2xl font-medium mb-4 text-[#159ed5]">ISERC Research Request Form</h2>
            <p class="text-gray-700 mb-4 font-normal">Submit your research proposal to the Institutional Scientific and Ethical Review Committee for approval.</p>
            <span class="text-[#159ed5] underline font-medium">See more</span>
        </a>

        <!-- Published Research Posters and Papers -->
        <a href="{{ route('research-papers') }}" class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition-shadow duration-300 block">
            <h2 class="text-2xl font-medium mb-4 text-[#159ed5]">Published Research - Papers and Posters</h2>
            <p class="text-gray-700 mb-4 font-normal">Explore our collection of published research posters showcasing the impactful work of our healthcare professionals.</p>
            <span class="text-[#159ed5] underline font-medium">See more</span>
        </a>
    </div>

    <!-- FAQ Button -->
    <div class="text-center mt-8">
        <button id="openModal" class="px-4 py-2 bg-[#159ed5] text-white rounded hover:bg-blue-700 transition-colors duration-300">
            <i class="fas fa-question-circle mr-2"></i> Have questions? Read our FAQs
        </button>
    </div>

    <!-- FAQ Modal -->
    <div id="faqModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="modal-content bg-white w-11/12 md:max-w-3xl mx-auto rounded shadow-lg z-50 overflow-y-auto max-h-full">
            <div class="modal-header p-5 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-[#159ed5] mb-2">Frequently Asked Questions About REDCap, Data Sharing, and Data Protection</h2>
                <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-5">
                <!-- FAQ Content -->
                <div class="space-y-4">
                    <!-- FAQ Item -->
                    <div class="faq-item border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                            <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> Does everyone who submits a proposal to the ISERC need to view the e-learning module on Kijabe Hospital Data Protection?
                        </h3>
                        <div class="answer hidden text-gray-600 mt-2">
                            <p>ANS: <strong>YES</strong>, You may register for the course in the link below<br>
                            <a href="https://amhlearn.com/kijabe-registration" class="text-[#159ed5] hover:text-blue-700 transition-colors duration-300">https://amhlearn.com/kijabe-registration</a></p>
                        </div>
                    </div>

                    <!-- FAQ Item -->
                    <div class="faq-item border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                            <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> I have a Research or Quality Improvement Project where I want to collect data. How do I get access on Kijabe’s REDCap platform?
                        </h3>
                        <div class="answer hidden text-gray-600 mt-2">
                            <p>ANS: Use the form to request Kijabe REDCAP access <a href="https://redcap.kijabehospital.org/surveys/?s=MC4PPTWD4RCKXXP8" class="text-[#159ed5] hover:text-blue-700 transition-colors duration-300">here</a></p>
                        </div>
                    </div>

                    <!-- FAQ Item -->
                    <div class="faq-item border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                            <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> I am a visiting consultant or a visiting student. I am invited to work in a Kijabe department, but at the end of my time I will need to do a presentation at my home institution. I am doing QI work and not research. Do I need to get ISERC approval?
                        </h3>
                        <div class="answer hidden text-gray-600 mt-2">
                            <p>ANS: All visitors who collect data for any purpose, research or QI, and who will present that data outside Kijabe Hospital, must submit their proposal to the ISERC Chair. Any data from Kijabe work, presented for credit or otherwise, that is used outside the Kijabe Hospital domain must have ISERC approval AND a Kijabe Hospital Principal Investigator.</p>
                        </div>
                    </div>

                    <!-- FAQ Item -->
                    <div class="faq-item border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                            <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> Do all projects (research or quality improvement) with data that goes outside our borders (Kijabe domain) need a formal Data Sharing Agreement?
                        </h3>
                        <div class="answer hidden text-gray-600 mt-2">
                            <p>ANS: <strong>NO</strong>, but all projects with data that goes outside our borders need a data protection plan clearly outlined in their research proposal and approval of the ISERC. This data protection plan must clearly stipulate that data will be stored at Kijabe Hospital. Any copy of the data that moves outside the Kijabe Domain must be de-identified data only. That data can only be utilized for the stipulated purpose described in the ISERC and only with the express permission of the Kijabe Hospital Principal Investigator.</p>
                        </div>
                    </div>

                <!-- FAQ Item -->
                <div class="faq-item border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                        <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> When do I need a formal Data Sharing Agreement?
                    </h3>
                    <div class="answer hidden text-gray-600 mt-2">
                        <p>ANS: When the work is part of a formal contractual relationship, i.e., funded grant, and especially where multiple stakeholders are involved, a formal data sharing agreement would be expected. That said, only fully de-identified data can move outside the Kijabe Domain. This is to comply with Kenya’s Data Protection Act.</p>
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                        <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> What is the difference between anonymized data and de-identified data?
                    </h3>
                    <div class="answer hidden text-gray-600 mt-2">
                        <p>ANS: 
                        <ul class="list-disc list-inside">
                            <li>Anonymized data is data you can never trace back to the original patient.</li>
                            <li>De-identified data can be un-masked and a researcher could go back to link it to an individual patient. De-identified data can be traced to a patient but only in certain circumstances. The only way to trace data back to the original patient is IF you contact the Kijabe PI so they can access the internal linking document. This can be done only with permission of the PI who is following the stipulated procedure per protocol.</li>
                        </ul>
                        </p>
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                        <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> What is linking data and why do you need a linking?
                    </h3>
                    <div class="answer hidden text-gray-600 mt-2">
                        <p>Linking data contains specific patient identifying data. Identifying data includes the Kijabe Hospital ID number and other data (i.e., phone, name/Date of birth, etc). 
                        In research to protect an individual’s patient information, study data should not be stored with identifying information. A unique study identifier should be utilized. In order to link data to a patient, there must be a linking document. This link would connect the patient ID number or other identifying data (phone, name/Date of birth, etc) to a unique study identifier number. The link data are accessible only to Kijabe PI, or specified study staff. This link data should NEVER leave the Kijabe Domain. Kijabe ID linking to study ID are linking documents. All linking data are Kijabe only data and stay at Kijabe only.</p>
                        <p>Kenya Data Protection Law states that Kijabe Hospital must maintain the original data and only a copy of the de-identified data can leave the local Domain.</p>
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                        <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> Who needs to sign a non-disclosure agreement?
                    </h3>
                    <div class="answer hidden text-gray-600 mt-2">
                        <p>ANS: Anyone working with patient data at Kijabe Hospital. For Kijabe staff, this is done during their on-boarding or contracting process. For visitors, visiting consultants, and students, this should be done prior to seeing patients, as part of their on-boarding process.</p>
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                        <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> I am a visiting consultant or visiting student. When I leave Kijabe do I lose my access to the Kijabe REDCap database I have been working on?
                    </h3>
                    <div class="answer hidden text-gray-600 mt-2">
                        <p>ANS: Yes, however, if you have an approved ISERC protocol, you may continue to do data analysis using a copy of de-identified data, at the request of the Kijabe Hospital Principal Investigator. Your access to a copy of the data should be surrendered at the close of the study as part of final reporting and clearance procedures.</p>
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item">
                    <h3 class="text-lg font-semibold text-gray-800 cursor-pointer" onclick="toggleAnswer(this)">
                        <i class="fas fa-chevron-right mr-2 inline-block transition-transform"></i> I am a Kijabe staff. When I leave Kijabe do I lose my access to the Kijabe REDCap database I have been working on?
                    </h3>
                    <div class="answer hidden text-gray-600 mt-2">
                        <p>ANS: Yes, however, if you have an approved ISERC protocol, you may continue to do data analysis using a copy of de-identified data, at the request of the Kijabe Hospital Principal Investigator. Your access to a copy of the data should be surrendered at the close of the study as part of final reporting and clearance procedures.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.getElementById('openModal').addEventListener('click', function() {
        document.getElementById('faqModal').classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('faqModal').classList.add('hidden');
    });

    // Close modal if clicked outside modal content
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('faqModal')) {
            document.getElementById('faqModal').classList.add('hidden');
        }
    });

    function toggleAnswer(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('i');
        if (answer.classList.contains('hidden')) {
            answer.classList.remove('hidden');
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        } else {
            answer.classList.add('hidden');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        }
    }
</script>

@endsection