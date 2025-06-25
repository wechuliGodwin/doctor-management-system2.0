@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Intensive Care Unit (ICU) Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Intensive Care Unit (ICU) at Kijabe Hospital is committed to providing critical care to patients with life-threatening conditions. Our state-of-the-art ICU is equipped with advanced medical technology and staffed by highly trained critical care specialists, nurses, and support staff who work together to ensure the highest level of care.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        Our ICU team is dedicated to managing and treating critically ill patients who require intensive monitoring and support. We focus on providing compassionate care while ensuring patient safety and comfort.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Advanced life support and monitoring</li>
        <li>Mechanical ventilation and respiratory care</li>
        <li>Continuous renal replacement therapy (CRRT)</li>
        <li>Hemodynamic monitoring and management</li>
        <li>Postoperative care for high-risk surgeries</li>
        <li>Care for patients with severe infections, sepsis, and multi-organ failure</li>
        <li>Management of patients with cardiac and respiratory arrest</li>
        <li>Pain management and sedation</li>
        <li>Family support and counseling</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our ICU is staffed by a multidisciplinary team of critical care physicians, anesthesiologists, specialized ICU nurses, respiratory therapists, and support staff. Our team is dedicated to providing expert care and ensuring the best possible outcomes for our patients.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        If you have questions about our ICU services or need to arrange care for a critically ill patient, please contact us:
    </p>
     <p class="text-lg text-gray-700 mb-2">
                Phone: <a href="tel:+254709728200" class="text-[#159ed5] hover:underline">+254 (0) 709 728 200</a> 
            </p>
    <p class="text-lg text-gray-700 mb-2">
        Email: customercare@kijabehospital.org
    </p>
    <p class="text-lg text-gray-700">
        For emergency referrals, please click <a href="{{ route('booking.show') }}" class="text-[#159ed5] hover:underline">here</a> to contact our emergency services.
    </p>
</div>
@endsection
