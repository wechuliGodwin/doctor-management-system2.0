@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Family Clinic Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Family Clinic at Kijabe Hospital is committed to providing comprehensive healthcare services for families. Our clinic focuses on preventive care, diagnosis, and treatment of a wide range of health conditions for patients of all ages, from children to the elderly.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        We believe in a holistic approach to healthcare, ensuring that each patient receives personalized care tailored to their specific needs. Our dedicated team of family medicine specialists, nurses, and support staff work together to provide compassionate care in a friendly and welcoming environment.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Routine check-ups and health screenings</li>
        <li>Management of chronic illnesses such as diabetes, hypertension, and asthma</li>
        <li>Preventive care including vaccinations and immunizations</li>
        <li>Women's health services including prenatal and postnatal care</li>
        <li>Children’s health and pediatric care</li>
        <li>Geriatric care and management of age-related conditions</li>
        <li>Health education and counseling services</li>
        <li>Referrals to specialists when necessary</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Meet Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Family Clinic is staffed by experienced family medicine physicians who are dedicated to providing the highest quality care. They are supported by a team of skilled nurses and administrative staff who work together to ensure your visit is as comfortable and efficient as possible.
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
