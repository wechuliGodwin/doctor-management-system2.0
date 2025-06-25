@extends('layouts.app')

@section('content')

<div class="container mx-auto my-8 p-4">
    <div class="flex flex-col md:flex-row-reverse items-center">  
        <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8"> 
            <img src="images/13.png" alt="Daycase Surgery at Kijabe Hospital" class="w-full rounded-lg shadow-md border border-gray-300 transition-transform duration-300 transform hover:scale-105">
        </div>
        <div class="md:w-1/2">
            <h1 class="text-3xl font-bold text-center md:text-left mb-4 text-gray-800">Daycase Surgery at Kijabe Hospital</h1> 
            <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                Kijabe Hospital offers a wide range of daycase surgical procedures, allowing you to return home the same day.  Our efficient and convenient services minimize disruption to your daily life while reducing the risk of hospital-acquired infections.
            </p>
        </div>
    </div>

    <div class="bg-gray-100 p-4 rounded-lg shadow-inner mb-6"> 
        <p class="text-lg text-gray-700 leading-relaxed">
            Our state-of-the-art surgical facilities and experienced team ensure you receive the highest quality care in a comfortable and safe environment. We are committed to providing personalized care and support throughout your entire surgical journey, from pre-operative assessment to post-operative recovery.
        </p>
    </div>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Benefits of Daycase Surgery</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <ul class="list-disc list-inside text-gray-700 mb-4">
            <li>Faster recovery time</li>
            <li>Reduced risk of infection</li>
        </ul>
        <ul class="list-disc list-inside text-gray-700 mb-4">
            <li>Minimal disruption to your daily routine</li> 
            <li>Increased comfort and convenience</li>
            <li>Cost-effective treatment option</li>
        </ul>
    </div>


    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Common Daycase Procedures</h2>
    <p class="text-lg text-gray-700 mb-4 leading-relaxed">
        We offer a variety of daycase procedures across many specialties, including:
    </p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> 
        <ul class="list-disc list-inside text-gray-700 mb-4">
            <li>General surgery (e.g., hernia repair, minor biopsies)</li>
            <li>Orthopedic surgery (e.g., arthroscopy, carpal tunnel release)</li>
            <li>ENT surgery (e.g., tonsillectomy, adenoidectomy)</li>
        </ul>
        <ul class="list-disc list-inside text-gray-700 mb-4"> 
            <li>Gynecological surgery (e.g., hysteroscopy, D&C)</li>
            <li>Urological surgery (e.g., circumcision, cystoscopy)</li>
            <li>Ophthalmology (e.g., cataract surgery)</li>
            <li>Plastic surgery (e.g., mole removal, scar revision)</li>
        </ul>
    </div>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Experienced Team</h2> 
    <div class="bg-gray-100 p-4 rounded-lg shadow-inner mb-6"> 
        <p class="text-lg text-gray-700 mb-4 leading-relaxed">
            Our Daycase Surgery unit is staffed by a highly skilled and compassionate team of surgeons, anesthetists, nurses, and support staff. They are dedicated to providing you with exceptional care and ensuring a smooth and comfortable surgical experience.
        </p>
    </div>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us to Learn More</h2>
    <p class="text-lg text-gray-700 mb-4 leading-relaxed">
        To learn more about our Daycase Surgery services or to schedule an appointment, please contact us using the information below:
    </p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-lg text-gray-700 mb-2">
                Phone: <a href="tel:+254709728200" class="text-[#159ed5] hover:underline">+254 (0) 709 728 200</a> 
            </p>
            <p class="text-lg text-gray-700 mb-2">
                Email: <a href="mailto:customercare@kijabehospital.org" class="text-[#159ed5] hover:underline">customercare@kijabehospital.org</a>
            </p>
        </div>
        <div>
            <p class="text-lg text-gray-700">
                To book online, click <a href="{{ route('booking.show') }}" class="text-[#159ed5] hover:underline">here</a>. 
            </p>
        </div>
    </div>
</div>

@endsection