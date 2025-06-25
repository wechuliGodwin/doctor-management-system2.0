@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Physiotherapy Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Physiotherapy Department at Kijabe Hospital is dedicated to helping patients restore their physical function, mobility, and overall well-being. Our team of experienced physiotherapists provides comprehensive assessment and treatment for a wide range of conditions, using evidence-based practices and a patient-centered approach.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        We aim to help patients manage pain, improve movement, and regain independence. Our therapists work closely with patients to develop individualized treatment plans tailored to their specific needs and goals.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Assessment and diagnosis of musculoskeletal conditions</li>
        <li>Treatment of pain and injuries</li>
        <li>Rehabilitation after surgery or injury</li>
        <li>Exercise prescription and guidance</li>
        <li>Manual therapy techniques</li>
        <li>Electrotherapy modalities</li>
        <li>Patient education and self-management strategies</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Conditions We Treat:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Back pain</li>
        <li>Neck pain</li>
        <li>Joint pain and arthritis</li>
        <li>Sports injuries</li>
        <li>Post-operative rehabilitation</li>
        <li>Stroke rehabilitation</li>
        <li>Neurological conditions</li>
        <li>Pediatric physiotherapy</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Physiotherapy Department is staffed by a team of highly skilled and compassionate physiotherapists who are committed to providing high-quality care. Our therapists have extensive experience in treating a variety of conditions and use the latest techniques and equipment to help patients achieve their rehabilitation goals.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        To schedule an appointment with our Physiotherapy Department or to learn more about our services, please contact us:
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