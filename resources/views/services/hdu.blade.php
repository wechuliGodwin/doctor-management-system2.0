@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">High Dependency Unit (HDU) Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The High Dependency Unit (HDU) at Kijabe Hospital provides specialized care for patients who require close monitoring and support but are not critically ill enough to need intensive care. Our HDU is equipped with advanced medical technology to monitor and support patients with complex medical conditions.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        Our HDU team consists of experienced doctors, nurses, and other healthcare professionals who are dedicated to delivering high-quality care. We focus on stabilizing and supporting patients during their recovery and ensuring their safety and comfort.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Continuous monitoring of vital signs</li>
        <li>Oxygen therapy and respiratory support</li>
        <li>Intravenous fluid and medication administration</li>
        <li>Care for patients recovering from major surgery</li>
        <li>Management of patients with cardiac, respiratory, or renal conditions</li>
        <li>Wound care and management</li>
        <li>Pain management and sedation</li>
        <li>Coordination of care with other hospital departments</li>
        <li>Support for families and caregivers</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        The HDU team at Kijabe Hospital includes skilled doctors, nurses, respiratory therapists, and support staff who are trained to provide specialized care to patients with a variety of medical needs. Our multidisciplinary approach ensures comprehensive care and the best possible outcomes for our patients.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        If you need more information about our HDU services or to discuss a patient's care, please contact us:
    </p>
    <p class="text-lg text-gray-700 mb-2">
                Phone: <a href="tel:+254709728200" class="text-[#159ed5] hover:underline">+254 (0) 709 728 200</a> 
            </p>

    <p class="text-lg text-gray-700 mb-2">
        Email: customercare@kijabehospital.org
    </p>
    <p class="text-lg text-gray-700">
        For more details or to arrange a consultation, please click <a href="{{ route('booking.show') }}" class="text-[#159ed5] hover:underline">here</a> to contact our team.
    </p>
</div>
@endsection
