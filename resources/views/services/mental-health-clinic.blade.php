@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Mental Health Clinic Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Mental Health Clinic at Kijabe Hospital provides compassionate and comprehensive care for individuals of all ages experiencing mental health challenges. Our team of experienced mental health professionals is dedicated to helping patients improve their mental well-being, cope with life's stressors, and achieve a fulfilling life. We offer a safe and supportive environment where individuals can receive personalized care tailored to their specific needs.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        We understand the importance of addressing mental health concerns with sensitivity and respect. Our clinic promotes hope and recovery, empowering individuals to take control of their mental health journey. 
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Conditions We Treat:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Depression</li>
        <li>Anxiety disorders</li>
        <li>Bipolar disorder</li>
        <li>Post-traumatic stress disorder (PTSD)</li>
        <li>Schizophrenia</li>
        <li>Obsessive-compulsive disorder (OCD)</li>
        <li>Eating disorders</li>
        <li>Substance abuse disorders</li>
        <li>Grief and loss</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Psychiatric evaluation and diagnosis</li>
        <li>Individual therapy</li>
        <li>Group therapy</li>
        <li>Family therapy</li>
        <li>Medication management</li>
        <li>Crisis intervention</li>
        <li>Psychoeducation</li>
        <li>Referral to support groups and community resources</li>
        <li>**Child psychology services** </li> 
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Mental Health Clinic is staffed by a multidisciplinary team of psychiatrists, psychologists, counselors, and social workers who are dedicated to providing high-quality mental health care. Our team members have extensive experience in treating a wide range of mental health conditions and use evidence-based approaches to help patients achieve their recovery goals.  
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        To schedule an appointment with our Mental Health Clinic or to learn more about our services, please contact us:
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