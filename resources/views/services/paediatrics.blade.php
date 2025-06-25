@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Paediatric Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        At Kijabe Hospital, we are committed to providing exceptional care for children of all ages. Our Pediatrics Department offers a wide range of services to promote the health and well-being of infants, children, and adolescents. We have a dedicated team of pediatricians, nurses, and support staff who are passionate about providing compassionate, family-centered care.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        We work in close partnership with Bethany Kids to provide specialized pediatric surgical services, ensuring that children with complex medical needs receive the highest quality of care.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Well-child visits and immunizations</li>
        <li>Diagnosis and treatment of childhood illnesses</li>
        <li>Management of chronic conditions</li>
        <li>Developmental screenings</li>
        <li>Nutritional counseling</li>
        <li>Adolescent health services</li>
        <li><strong>Paediatric surgery (in partnership with Bethany Kids)</strong></li> 
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Areas of Focus in Partnership with Bethany Kids:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Orthopedic surgery (e.g., clubfoot, limb deformities)</li>
        <li>Neurosurgery (e.g., hydrocephalus, spina bifida)</li>
        <li>Plastic and reconstructive surgery (e.g., cleft lip and palate, burn care)</li>
        <li>General surgery</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Paediatrics Department is staffed by experienced and compassionate pediatricians who are dedicated to providing personalized care for every child. We work closely with families to ensure that they are involved in their child's care and that their questions and concerns are addressed.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        To schedule an appointment with our Paediatrics Department or to learn more about our services, please contact us:
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