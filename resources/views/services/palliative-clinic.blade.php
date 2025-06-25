@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Palliative Clinic Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Palliative Clinic at Kijabe Hospital is dedicated to providing compassionate care and support for patients facing life-limiting illnesses. Our goal is to improve the quality of life for our patients and their families by managing pain, symptoms, and emotional distress. We work closely with each patient to develop personalized care plans that meet their physical, emotional, and spiritual needs.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        Our team includes experienced palliative care specialists, nurses, counselors, and support staff who are committed to offering comprehensive palliative care in a respectful and dignified manner. We focus on patient comfort, quality of life, and family support.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Pain management and symptom control</li>
        <li>Emotional and psychological support</li>
        <li>Advanced care planning and decision-making support</li>
        <li>Coordination of care with other healthcare providers</li>
        <li>Support for families and caregivers</li>
        <li>Bereavement support and counseling</li>
        <li>Spiritual care and guidance</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Approach</h2>
    <p class="text-lg text-gray-700 mb-4">
        At Kijabe Hospital's Palliative Clinic, we take a holistic approach to palliative care, addressing not only the physical symptoms of illness but also the emotional, social, and spiritual challenges that patients and their families may face. We believe in treating each patient with respect and compassion, ensuring their dignity and comfort throughout their care journey.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        If you or a loved one needs palliative care services, please reach out to us for support:
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
