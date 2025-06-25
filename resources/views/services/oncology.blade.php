@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Oncology Clinic Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Oncology Clinic at Kijabe Hospital is dedicated to providing comprehensive cancer care to patients. Our clinic offers a wide range of services, including cancer screening, diagnosis, treatment, and supportive care. Our team of oncologists, nurses, and support staff are committed to offering compassionate care to help patients manage their conditions effectively.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        Our approach is patient-centered, ensuring that each patient receives personalized care tailored to their specific needs. We strive to provide the highest quality of care, using the latest medical advancements and treatment protocols.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Cancer screening and early detection programs</li>
        <li>Comprehensive diagnostic services, including imaging and biopsy</li>
        <li>Medical oncology services, including chemotherapy and immunotherapy</li>
        <li>Radiation oncology in collaboration with partner institutions</li>
        <li>Surgical oncology services for tumor removal</li>
        <li>Pain management and palliative care</li>
        <li>Nutritional support and counseling</li>
        <li>Psychosocial support for patients and their families</li>
        <li>Follow-up care and survivorship programs</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Oncology Clinic is staffed by a team of highly qualified oncologists, specialized nurses, and support staff who are dedicated to providing exceptional cancer care. Our team works closely with each patient to ensure that their treatment plan is effective and that they receive the emotional and psychological support they need.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        If you or a loved one needs oncology services, please reach out to us to schedule an appointment or to learn more about our care options:
    </p>
    <p class="text-lg text-gray-700 mb-2">
        Phone: +254 (0) 709 728 200
    </p>
    <p class="text-lg text-gray-700 mb-2">
        Email: customercare@kijabehospital.org
    </p>
    <p class="text-lg text-gray-700">
        To book an appointment online, please click <a href="{{ route('booking.show') }}" class="text-[#159ed5] hover:underline">here</a>.
    </p>
</div>
@endsection
