@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Surgical Care Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Surgical Care Department at Kijabe Hospital is dedicated to providing high-quality surgical services to patients. Our team of skilled surgeons, anesthesiologists, nurses, and support staff work together to ensure patient safety and the best possible outcomes. We offer a range of surgical procedures, from routine operations to complex surgeries, using state-of-the-art technology and advanced surgical techniques.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        Our patient-centered approach focuses on individualized care, ensuring each patient receives the attention and support needed throughout their surgical journey, from preoperative assessment to postoperative recovery.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Surgical Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>General surgery</li>
        <li>Orthopedic surgery</li>
        <li>Cardiothoracic surgery</li>
        <li>Neurosurgery</li>
        <li>Ophthalmic surgery</li>
        <li>Plastic and reconstructive surgery</li>
        <li>Obstetrics and gynecological surgery</li>
        <li>Pediatric surgery</li>
        <li>Minimally invasive and laparoscopic surgery</li>
        <li>Emergency surgery services</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        The Surgical Care team at Kijabe Hospital consists of highly trained surgeons with expertise in various specialties, experienced anesthesiologists, and dedicated nursing and support staff. Our multidisciplinary team is committed to providing compassionate care and ensuring the highest standards of surgical practice.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        For more information about our surgical services or to schedule a consultation, please contact us:
    </p>
     <p class="text-lg text-gray-700 mb-2">
                Phone: <a href="tel:+254709728200" class="text-[#159ed5] hover:underline">+254 (0) 709 728 200</a> 
            </p>
    <p class="text-lg text-gray-700 mb-2">
        Email: customercare@kijabehospital.org
    </p>
    <p class="text-lg text-gray-700">
        To book an appointment or consultation online, please click <a href="{{ route('booking.show') }}" class="text-[#159ed5] hover:underline">here</a>.
    </p>
</div>
@endsection
