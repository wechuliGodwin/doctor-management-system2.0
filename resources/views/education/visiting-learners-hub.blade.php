@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-12 p-6">
        <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Visiting Learners Hub</h1>

        <div class="relative mb-8">
            <img src="https://images.pexels.com/photos/356040/pexels-photo-356040.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                alt="Visiting Learners at Kijabe Hospital" class="w-full h-96 object-cover rounded-lg shadow-md">
            <!-- <a href="https://kijabehospital.or.ke/visitinglearnersform.php" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-[#159ed5] text-white py-2 px-4 rounded-lg font-semibold hover:bg-[#107ba3] transition-colors duration-300">Submit Your Application</a> -->

            <a href="{{ route('visiting-learners.create') }}"
                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-[#159ed5] text-white py-2 px-4 rounded-lg font-semibold hover:bg-[#107ba3] transition-colors duration-300">Submit
                Your Application</a>


        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Welcome to Kijabe Hospital</h2>
                <p class="text-gray-700 mb-4">
                    Thank you for your interest in a learning rotation at AIC Kijabe Hospital! We offer a transformative
                    experience for medical students, residents, and fellows, allowing you to learn from our highly skilled
                    clinicians and nurses while providing care to our diverse patient population.
                </p>
                <p class="text-gray-700">
                    Kijabe provides a unique opportunity to experience clinical care and education at a high level in an
                    international setting. We encourage full clinical participation during your rotation, with a minimum
                    stay of three weeks recommended for optimal integration and contribution to our team.
                </p>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Rotations</h2>
                <ul class="list-disc list-inside text-gray-700">
                    <li>Pediatrics (PICU, NICU, hospital floor, outpatient clinics)</li>
                    <li>Internal Medicine (diagnosis, treatment, subspecialty consultation, ICU)</li>
                    <li>Family Medicine (preventive care, outpatient, subspecialty clinics)</li>
                    <li>General Surgery</li>
                    <li>Orthopedic Surgery</li>
                    <li>Anesthesia (operating theatre, preoperative assessments)</li>
                    <li>Obstetrics/Gynecology (prenatal care, childbirth, gynecological services)</li>
                    <li>Emergency Care (10-bed Casualty, ECCCOs, PECCCOs)</li>
                    <li>Plastic Surgery</li>
                    <li>Pediatric Surgery</li>
                </ul>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mt-8">
            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">How to Apply</h2>
            <p class="text-gray-700 mb-4">
                To apply, please submit your application through the provided link. You will hear from our team within 10
                working days regarding availability, potential dates, and any follow-up questions.
            </p>
            <p class="text-gray-700 mb-4">
                If you have questions before applying, contact our Visiting Learning Coordinator at <a
                    href="mailto:visitorgmecoord@kijabehospital.org"
                    class="text-[#159ed5]">visitorgmecoord@kijabehospital.org</a> or call us at 0709728249.
            </p>
        </div>

        <div class="bg-[#159ed5] text-white p-6 rounded-lg text-center mt-8">
            <h2 class="text-2xl font-bold">About Us</h2>
            <p class="text-lg mt-2">We exist to glorify God through compassionate health care provision, training, and
                service.</p>
        </div>
    </div>
@endsection
