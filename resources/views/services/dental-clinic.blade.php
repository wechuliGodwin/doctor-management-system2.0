@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Dental Clinic Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Dental Clinic at Kijabe Hospital is dedicated to providing comprehensive dental care for patients of all ages. Our clinic offers a wide range of services, from routine dental check-ups and cleanings to more specialized treatments. Our team of experienced dentists, dental hygienists, and support staff are committed to ensuring the best possible care in a comfortable and welcoming environment.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        Whether you are in need of preventive care, restorative treatments, or cosmetic procedures, our Dental Clinic is equipped to handle all your dental health needs.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Routine dental check-ups and cleanings</li>
        <li>Dental fillings and restorative care</li>
        <li>Tooth extractions and minor oral surgery</li>
        <li>Root canal treatment</li>
        <li>Orthodontic evaluations and referrals</li>
        <li>Pediatric dental care</li>
        <li>Gum disease treatment and management</li>
        <li>Teeth whitening and other cosmetic dental procedures</li>
        <li>Dental implants and prosthetics</li>
        <li>Emergency dental care</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Dental Clinic team is composed of highly skilled and compassionate professionals who are dedicated to providing exceptional dental care. Our dentists have extensive experience in various fields of dentistry, and they work closely with our dental hygienists and support staff to ensure a positive patient experience.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        To book an appointment or to learn more about our services, please contact us:
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
