@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Medical Ward Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Medical Ward at Kijabe Hospital provides comprehensive care for patients with a wide range of medical conditions. Our team of skilled doctors, nurses, and healthcare professionals is dedicated to offering high-quality, compassionate care in a safe and supportive environment.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        We focus on diagnosing, treating, and managing various acute and chronic illnesses. Our multidisciplinary approach ensures that each patient receives personalized care tailored to their specific health needs.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Diagnosis and treatment of acute and chronic illnesses</li>
        <li>Management of infectious diseases</li>
        <li>Cardiac care and monitoring</li>
        <li>Respiratory care and support</li>
        <li>Diabetes management and education</li>
        <li>Neurological care</li>
        <li>Gastroenterology services</li>
        <li>Renal care and management</li>
        <li>Nutrition and dietary counseling</li>
        <li>Wound care and management</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        The Medical Ward team consists of experienced physicians, registered nurses, and allied health professionals who work collaboratively to provide comprehensive care. Our team is dedicated to improving patient outcomes through evidence-based practices and compassionate care.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        For more information about our Medical Ward services or to discuss a patient's care, please contact us:
    </p>
    <p class="text-lg text-gray-700 mb-2">
        Phone: +254 (0) 711 123 456 / +254 (0) 733 123 456
    </p>
    <p class="text-lg text-gray-700 mb-2">
        Email: medicalward@kijabehospital.org
    </p>
    <p class="text-lg text-gray-700">
        To book an appointment or consultation, please click <a href="{{ route('booking.show') }}" class="text-[#159ed5] hover:underline">here</a>.
    </p>
</div>
@endsection
