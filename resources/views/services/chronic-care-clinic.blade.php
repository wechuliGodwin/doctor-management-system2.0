@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Chronic Care Clinic Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Chronic Care Clinic at Kijabe Hospital provides comprehensive care for patients with long-term health conditions. Our multidisciplinary team is dedicated to helping patients manage their conditions effectively, improve their quality of life, and maintain their independence. We offer a patient-centered approach, working closely with individuals to develop personalized care plans that address their unique needs.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        We understand the challenges of living with a chronic illness. Our clinic provides ongoing support, education, and resources to empower patients to take an active role in their care.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Conditions We Manage:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Diabetes</li>
        <li>Hypertension</li>
        <li>Heart disease</li>
        <li>Asthma</li>
        <li>Chronic obstructive pulmonary disease (COPD)</li>
        <li>Arthritis</li>
        <li>Kidney disease</li>
        <li>HIV/AIDS</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Regular check-ups and monitoring</li>
        <li>Medication management</li>
        <li>Lifestyle counseling (diet, exercise, stress management)</li>
        <li>Patient education and self-management support</li>
        <li>Coordination of care with other specialists</li>
        <li>Support groups and resources</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Chronic Care Clinic is staffed by a team of experienced healthcare professionals, including doctors, nurses, dietitians, and counselors. We work collaboratively to provide holistic care that addresses the physical, emotional, and social needs of our patients.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        To schedule an appointment with our Chronic Care Clinic or to learn more about our services, please contact us:
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