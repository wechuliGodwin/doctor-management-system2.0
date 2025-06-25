@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Paediatric Intensive Care</h1>

    <p class="text-lg text-gray-700 mb-4">
        Kijabe Hospital provides specialized intensive care services for infants and children facing critical medical conditions. We have two dedicated units equipped to handle a wide range of pediatric emergencies:
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8"> 

        <div>
            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">PICU (Paediatric Intensive Care Unit)</h2>
            <p class="text-lg text-gray-700 mb-4">
                Our PICU cares for infants, children, and teenagers who require close monitoring and advanced life support. This unit is equipped to handle a variety of serious conditions, including:
            </p>
            <ul class="list-disc list-inside text-gray-700 mb-4">
                <li>Severe infections (e.g., sepsis, meningitis)</li>
                <li>Respiratory problems (e.g., pneumonia, respiratory failure)</li>
                <li>Trauma (e.g., accidents, burns)</li>
                <li>Post-surgical complications</li>
                <li>Serious chronic illnesses</li>
            </ul>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">NICU (Neonatal Intensive Care Unit)</h2>
            <p class="text-lg text-gray-700 mb-4">
                Our NICU is a specialized unit dedicated to the care of newborn babies, especially those born prematurely or with health problems. We provide advanced life support and specialized care for conditions such as:
            </p>
            <ul class="list-disc list-inside text-gray-700 mb-4">
                <li>Prematurity (born before 37 weeks)</li>
                <li>Low birth weight</li>
                <li>Breathing difficulties</li>
                <li>Congenital birth defects</li>
                <li>Infections</li>
            </ul>
        </div>

    </div> <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Pediatric Intensive Care Units are staffed by a highly skilled team of paediatricians, neonatologists, nurses, and respiratory therapists who are dedicated to providing the best possible care for critically ill children. 
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        To learn more about our Pediatric Intensive Care services, please contact us:
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