@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Outpatient Services</h1>

    <p class="text-lg text-gray-700 mb-4">
        The Department of Internal Medicine at Kijabe Hospital is dedicated to providing top-notch patient-centered healthcare across various sub-specialties. Our team of highly skilled physicians, nurses, and support staff work together to ensure the highest standards of patient care, education, and research.
    </p>

    <p class="text-lg text-gray-700 mb-4">
        Whether you are a patient seeking care, a prospective medical student, or a resident, we welcome you to learn more about our department and the comprehensive services we offer. Our commitment to patient safety, satisfaction, and excellence in healthcare drives everything we do.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Sub-specialties</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Cardiology</li>
        <li>Critical Care</li>
        <li>Dermatology</li>
        <li>Endocrinology and Diabetes</li>
        <li>Gastroenterology</li>
        <li>Infectious Diseases / HIV Care / Travel Medicine</li>
        <li>Internal Medicine</li>
        <li>Pulmonology</li>
        <li>Nephrology</li>
        <li>Neurology</li>
        <li>Psychiatry / Mental Health</li>
        <li>Rheumatology</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Nephrology Services</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our nephrology team is committed to providing exceptional care for patients with kidney-related conditions. Our services include:
    </p>
    <ul class="list-disc list-inside text-gray-700 mb-4">
        <li>Management of acute and chronic dialysis, including peritoneal dialysis and continuous renal replacement therapy.</li>
        <li>A multidisciplinary approach to kidney care, ensuring excellent patient outcomes and safety.</li>
        <li>Outpatient clinics for the management of various kidney disorders, such as glomerulonephritis, chronic kidney disease, and pre- and post-kidney transplant care.</li>
        <li>Management of resistant hypertension and kidney complications resulting from diabetes.</li>
        <li>An interventional nephrology unit equipped for kidney biopsies and insertion of tunneled permanent dialysis catheters.</li>
        <li>Advanced therapies such as plasmapheresis and slow low efficient daily dialysis (SLEDD).</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
        To book an appointment with our Internal Medicine specialists, please contact us:
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
