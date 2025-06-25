@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-[#159ed5]">Frequently Asked Questions</h1>
        <p class="text-lg text-gray-600">Find answers to common questions about AIC Kijabe Hospital, our services, history, and impact. Use the search bar to quickly locate specific information.</p>
    </div>

    <div class="flex justify-center mb-6">
        <div class="w-full md:w-2/3 lg:w-1/2">
            <input type="text" id="faqSearch" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-[#159ed5]" placeholder="Search FAQs...">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- What is AIC Kijabe Hospital Known For? -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseKnownFor">
                    What is AIC Kijabe Hospital known for?
                </h3>
                <div id="collapseKnownFor" class="mt-2 hidden">
                    <p class="text-gray-700">
                        AIC Kijabe Hospital is renowned for its outstanding medical care, its commitment to holistic healing, and its influence on healthcare across Kenya and Sub-Saharan Africa. The hospital has become well known for several distinguishing factors, including:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mt-2">
                        <li><strong>Prayer and Spiritual Care:</strong> AIC Kijabe Hospital integrates spiritual care with healthcare, believing in the importance of prayer for complete healing. Patients often choose Kijabe for its compassionate approach to medical care and the hope provided by spiritual support.</li>
                        <li><strong>Orthopaedic and General Surgery:</strong> Kijabe is a leading center for orthopaedic and trauma surgeries, stemming from expertise built since the hospital's early years.</li>
                        <li><strong>Maternity and Neonatal Care:</strong> Kijabe is known for managing high-risk pregnancies and multiple births, supported by state-of-the-art NICUs that ensure the best possible care for mothers and infants.</li>
                        <li><strong>Cancer Care:</strong> The hospital offers comprehensive cancer treatment, including diagnostics, surgery, chemotherapy, and palliative care. Construction of a new Cancer Center is underway, with support from Friends of Kijabe.</li>
                        <li><strong>Legacy and History:</strong> Founded over 110 years ago, AIC Kijabe Hospital has grown to be a major teaching and referral hospital, providing care and hope to communities across East Africa.</li>
                    </ul>
                    <p class="text-gray-700 mt-2">
                        To learn more or support our Cancer Center initiative, visit <a href="https://friendsofkijabe.org/cancercenter" class="text-[#159ed5]" target="_blank">Friends of Kijabe</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Languages Spoken -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseLanguages">
                    What languages are spoken at AIC Kijabe Hospital?
                </h3>
                <div id="collapseLanguages" class="mt-2 hidden">
                    <p class="text-gray-700">
                        English and Swahili are the official languages of Kenya, and both are widely spoken at AIC Kijabe Hospital. To ensure effective communication:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mt-2">
                        <li><strong>English Fluency:</strong> All medical staff are fluent in English, ensuring precise medical care and proper documentation.</li>
                        <li><strong>Swahili Translation:</strong> Translation is provided by our nursing staff to ensure patients are comfortable and fully understand their treatment options.</li>
                       
                    </ul>
                </div>
            </div>
        </div>

        <!-- Impact on Healthcare in Kenya -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseImpact">
                    What is the impact of AIC Kijabe Hospital on healthcare in Kenya?
                </h3>
                <div id="collapseImpact" class="mt-2 hidden">
                    <p class="text-gray-700">
                        AIC Kijabe Hospital has made a significant impact on healthcare in Kenya and beyond, playing a critical role in medical services, education, and community outreach:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mt-2">
                        <li><strong>Life-Changing Surgeries:</strong> Performing over 3,000 surgeries annually, Kijabe has transformed the lives of thousands of patients.</li>
                        <li><strong>Healthcare Training:</strong> Kijabe has trained over 2,400 healthcare professionals through residency programs, nursing education, and medical internships.</li>
                        <li><strong>Community Clinics:</strong> Our satellite clinics extend services to rural areas, providing essential care and outreach programs focusing on maternal health, HIV, and nutrition.</li>
                        <li><strong>Facility Development:</strong> Infrastructure improvements, such as the installation of a new oxygen plant and the construction of medical education buildings, ensure we maintain high standards of care.</li>
                        <li><strong>Partnerships:</strong> Collaborations with Pan-African Academy of Christian Surgeons (PAACS), African Mission Healthcare, and others have enhanced our service capabilities and training initiatives.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Visiting Hours -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseVisitingHours">
                    What are the visiting hours at AIC Kijabe Hospital?
                </h3>
                <div id="collapseVisitingHours" class="mt-2 hidden">
                    <p class="text-gray-700">
                        Visiting hours at AIC Kijabe Hospital are from 12 pm to 1 pm daily. We encourage visitors to adhere to these times to ensure that patients receive adequate rest and medical attention. Special arrangements can be made for critical care patients or in unique circumstances. Please contact the nursing station of the respective ward for more information.
                    </p>
                </div>
            </div>
        </div>

        <!-- How to Make an Appointment -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseAppointment">
                    How can I make an appointment with a specialist?
                </h3>
                <div id="collapseAppointment" class="mt-2 hidden">
                    <p class="text-gray-700">
                        Appointments with specialists at AIC Kijabe Hospital can be made through multiple channels:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mt-2">
                        <li><strong>Online Booking:</strong> Visit our official website and navigate to the 'Appointments' section. Fill in your details and select the preferred specialist and available time slot.</li>
                        <li><strong>Phone Booking:</strong> Call our appointment line at <strong>0709 728 200</strong> between 8 AM and 5 PM on weekdays. Our customer service team will assist you in booking an appointment.</li>
                        <li><strong>Walk-in Booking:</strong> You can also visit our outpatient department during working hours to book an appointment in person.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Payment Options -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapsePaymentOptions">
                    What are the payment options available for services at AIC Kijabe Hospital?
                </h3>
                <div id="collapsePaymentOptions" class="mt-2 hidden">
                    <p class="text-gray-700">
                        AIC Kijabe Hospital offers a variety of payment options to accommodate different needs:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mt-2">
                        <li><strong>Cashless Payments:</strong> We are primarily cashless, but M-PESA points are available if needed.</li>
                        <li><strong>Credit/Debit Card Payments:</strong> We accept major credit and debit cards, including Visa, MasterCard, and American Express.</li>
                        <li><strong>Mobile Money:</strong> Payments can be made via M-Pesa using Paybill number <strong>512900</strong>, with your patient number as the reference.</li>
                        <li><strong>Insurance Billing:</strong> We offer direct billing for patients with accepted insurance plans.</li>
                        <li><strong>Bank Transfers:</strong> For larger payments, bank transfer details can be provided at the billing office.</li>
                    </ul>
                    <p class="text-gray-700 mt-2">For any billing-related inquiries, please contact our billing department at <strong>0709 728 200</strong> ext. 8236.</p>
                </div>
            </div>
        </div>

        <!-- Accepted Insurance Plans -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseInsurancePlans">
                    What insurance plans are accepted at AIC Kijabe Hospital?
                </h3>
                <div id="collapseInsurancePlans" class="mt-2 hidden">
                    <p class="text-gray-700">
                        AIC Kijabe Hospital accepts a wide range of insurance plans to ensure accessibility to our services. Accepted insurance providers include:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 mt-2">
                        <li>Social Health Insurance Fund (SHIF)</li>
                        <li>AAR Healthcare</li>
                        <li>Jubilee Insurance</li>
                        <li>Britam Insurance</li>
                        <li>CIC Insurance</li>
                        <li>UAP Old Mutual</li>
                        <li>Avenue Healthcare</li>
                    </ul>
                    <p class="text-gray-700 mt-2">If your insurance provider is not listed, please contact our billing department at <a href="mailto:finmgr@kijabehospital.org" class="text-[#159ed5]">finmgr@kijabehospital.org</a> for confirmation or to discuss payment options.</p>
                    <div class="mt-4">
                        <button id="openModal" class="bg-[#159ed5] text-white py-2 px-4 rounded-lg hover:bg-[#127cb8]">
                            Click Here for More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Insurance Plans Image -->
        <div id="insuranceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="text-lg font-bold text-[#159ed5]">Accepted Insurance Plans</h3>
                    <button id="closeModal" class="text-gray-600 hover:text-gray-900 text-2xl">&times;</button>
                </div>
                <div class="mt-4 flex justify-center">
                    <img src="{{ asset('images/ins.jpeg') }}" alt="Accepted Insurances" class="rounded-lg shadow-md w-full h-auto">
                </div>
            </div>
        </div>

        <!-- EXPAND HERE: Add additional FAQs as needed below -->
        <!-- For example, add sections on outpatient services, partnerships, new programs, etc. -->

	<!-- Emergency Services -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseEmergencyServices">
            What should I do in case of a medical emergency?
        </h3>
        <div id="collapseEmergencyServices" class="mt-2 hidden">
            <p class="text-gray-700">
                In case of a medical emergency, you should:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li>Call our emergency hotline at <strong>0791-333-000</strong>. Our emergency response team is available 24/7 to provide immediate assistance.</li>
                <li>Visit the AIC Kijabe Hospital Emergency Department directly. The department is equipped to handle all types of emergencies, including trauma, cardiac events, and severe injuries.</li>
                <li>Provide as much information as possible to the emergency response team, including the patient's condition, any known medical history, and location details if assistance is needed on-site.</li>
            </ul>
            <p class="text-gray-700 mt-2">Our emergency services are supported by state-of-the-art facilities and a team of experienced healthcare professionals ready to provide urgent care.</p>
        </div>
    </div>
</div>

<!-- How to Obtain Medical Records -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseMedicalRecords">
            How can I obtain a copy of my medical records?
        </h3>
        <div id="collapseMedicalRecords" class="mt-2 hidden">
            <p class="text-gray-700">
                To obtain a copy of your medical records, you need to submit a written request to our Medical Records Department. You can do this by:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li>Visiting the hospital in person and filling out a records request form at the Medical Records Office.</li>
                <li>Emailing your request to <a href="mailto:cad@kijabehospital.org" class="text-[#159ed5]">cad@kijabehospital.org</a>. Please include your full name, date of birth, identification number, and specific details about the records you need.</li>
                <li>Mailing a request letter to the Medical Records Department, AIC Kijabe Hospital, P.O. Box 20, Kijabe, Kenya.</li>
            </ul>
            <p class="text-gray-700 mt-2">Please allow 5-7 working days for the processing of your request. There may be a nominal fee associated with the release of records.</p>
        </div>
    </div>
</div>

<!-- COVID-19 Protocols -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseCovidProtocols">
            What COVID-19 safety protocols are in place at AIC Kijabe Hospital?
        </h3>
        <div id="collapseCovidProtocols" class="mt-2 hidden">
            <p class="text-gray-700">
                AIC Kijabe Hospital follows strict COVID-19 protocols to ensure the safety of patients, visitors, and staff:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li><strong>Mandatory Mask Wearing:</strong> All individuals must wear masks while inside the hospital premises.</li>
                <li><strong>Temperature Screening:</strong> All patients and visitors are screened for symptoms upon entry.</li>
                <li><strong>Social Distancing:</strong> Waiting areas and common areas are arranged to ensure a safe distance between individuals.</li>
                <li><strong>Sanitization Stations:</strong> Hand sanitizing stations are available throughout the hospital.</li>
            </ul>
            <p class="text-gray-700 mt-2">For more details on visiting policies during the pandemic, contact our information desk at <strong>0709 728 200</strong>.</p>
        </div>
    </div>
</div>

<!-- Maternity and Childbirth Services -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseMaternityServices">
            What maternity and childbirth services are available?
        </h3>
        <div id="collapseMaternityServices" class="mt-2 hidden">
            <p class="text-gray-700">
                AIC Kijabe Hospital provides a full range of maternity services, ensuring that mothers and babies receive the best possible care:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li><strong>Prenatal Care:</strong> Comprehensive antenatal check-ups to monitor the health of both mother and baby.</li>
                <li><strong>High-Risk Pregnancy Care:</strong> Specialized services for managing high-risk pregnancies, including those expecting twins, triplets, or more.</li>
                <li><strong>Labor and Delivery:</strong> Safe and comfortable labor and delivery services, with 24/7 obstetrician support.</li>
                <li><strong>Postnatal Support:</strong> Postnatal services, including breastfeeding support and baby wellness check-ups.</li>
            </ul>
            <p class="text-gray-700 mt-2">For more information, contact our Maternity Unit at <strong>0709 728 250</strong>.</p>
        </div>
    </div>
</div>

<!-- Child and Adolescent Health Services -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseChildHealth">
            What services are available for children and adolescents?
        </h3>
        <div id="collapseChildHealth" class="mt-2 hidden">
            <p class="text-gray-700">
                AIC Kijabe Hospital is committed to providing quality healthcare to children and adolescents, including:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li><strong>Well-Baby Clinic:</strong> Routine check-ups and immunizations to ensure healthy growth and development.</li>
                <li><strong>Pediatric Specialty Clinics:</strong> Clinics for children with chronic conditions such as asthma, diabetes, and neurological disorders.</li>
                <li><strong>Nutrition Counseling:</strong> Nutritional advice for children and adolescents to ensure healthy diets and address malnutrition concerns.</li>
                <li><strong>Adolescent Health Services:</strong> Special focus on adolescent health issues, including reproductive health education and mental health counseling.</li>
            </ul>
        </div>
    </div>
</div>

<!-- Family Medicine Services -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseFamilyMedicine">
            What family medicine services are offered at AIC Kijabe Hospital?
        </h3>
        <div id="collapseFamilyMedicine" class="mt-2 hidden">
            <p class="text-gray-700">
                Our Family Medicine department provides comprehensive healthcare for the entire family, focusing on:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li><strong>Preventive Care:</strong> Regular health check-ups, vaccinations, and screenings for early detection of diseases.</li>
                <li><strong>Chronic Disease Management:</strong> Management and treatment of chronic illnesses such as hypertension, diabetes, and asthma.</li>
                <li><strong>Acute Care:</strong> Treatment of common acute illnesses and injuries, ensuring fast recovery and optimal health.</li>
                <li><strong>Women's Health:</strong> Services related to women's health, including Pap smears, breast exams, and menopausal care.</li>
            </ul>
            <p class="text-gray-700 mt-2">Our family medicine team is committed to offering continuous, personalized care to each member of the family.</p>
        </div>
    </div>
</div>

<!-- Partnerships and Collaborations -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapsePartnerships">
            What partnerships does AIC Kijabe Hospital have to improve healthcare?
        </h3>
        <div id="collapsePartnerships" class="mt-2 hidden">
            <p class="text-gray-700">
                AIC Kijabe Hospital works with several partners to enhance healthcare services:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li><strong>Pan-African Academy of Christian Surgeons (PAACS):</strong> Provides surgical training to healthcare workers from across Africa.</li>
                <li><strong>African Mission Healthcare (AMH):</strong> Supports infrastructure development, medical education, and healthcare delivery improvements.</li>
                <li><strong>AIC Cure International:</strong> Focuses on providing pediatric orthopaedic services, including corrective surgeries and rehabilitation.</li>
                <li><strong>Friends of Kijabe:</strong> An international community supporting the hospital's mission through fundraising and project development, including the Kijabe Cancer Center.</li>
            </ul>
            <p class="text-gray-700 mt-2">These partnerships enable AIC Kijabe Hospital to deliver high-quality care and build healthcare capacity throughout the region.</p>
        </div>
    </div>
</div>

<!-- Support Services for Patients -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4">
        <h3 class="font-semibold text-xl text-[#159ed5] cursor-pointer" data-toggle="collapse" data-target="#collapseSupportServices">
            What support services are available for patients and their families?
        </h3>
        <div id="collapseSupportServices" class="mt-2 hidden">
            <p class="text-gray-700">
                AIC Kijabe Hospital offers various support services to ensure patient comfort and well-being:
            </p>
            <ul class="list-disc list-inside text-gray-700">
                <li><strong>Pastoral Care and Counseling:</strong> Spiritual support from chaplains who are available to pray with patients and provide counseling.</li>
                <li><strong>Social Work Services:</strong> Social workers assist patients and families in navigating healthcare, providing information about available resources.</li>
                <li><strong>Patient Support Groups:</strong> Support groups for individuals facing similar medical challenges, such as cancer or chronic diseases, providing emotional support and community connection.</li>
                <li><strong>Financial Counseling:</strong> Assistance with understanding billing, payment plans, and exploring financial aid options for those in need.</li>
            </ul>
            <p class="text-gray-700 mt-2">Our goal is to ensure patients and their families feel supported throughout their care journey.</p>
        </div>
    </div>
</div>



    </div>
</div>

<!-- JavaScript for In-Page Search, Collapse Toggle, and Modal -->
<script>
    // In-page search functionality
    document.getElementById('faqSearch').addEventListener('input', function() {
        var searchQuery = this.value.toLowerCase();
        var faqItems = document.querySelectorAll('.grid .bg-white');

        faqItems.forEach(function(item) {
            var questionText = item.querySelector('h3').innerText.toLowerCase();
            var answerText = item.querySelector('div').innerText.toLowerCase();
            if (questionText.includes(searchQuery) || answerText.includes(searchQuery)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Collapse functionality for FAQs
    document.querySelectorAll('[data-toggle="collapse"]').forEach(function(button) {
        button.addEventListener('click', function() {
            var target = document.querySelector(this.getAttribute('data-target'));
            target.classList.toggle('hidden');
        });
    });

    // Modal functionality for insurance plans
    document.getElementById('openModal').addEventListener('click', function () {
        document.getElementById('insuranceModal').classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('insuranceModal').classList.add('hidden');
    });

    // Close modal on clicking outside
    window.addEventListener('click', function (e) {
        if (e.target === document.getElementById('insuranceModal')) {
            document.getElementById('insuranceModal').classList.add('hidden');
        }
    });
</script>
@endsection
