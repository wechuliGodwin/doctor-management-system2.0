<!-- resources/views/westlands-branch.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Westlands Branch - Kijabe Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Include Header (Navigation) -->
    @include('layouts.navigation')

    <!-- Westlands Branch Section -->
    <div class="container mx-auto my-10 px-6">
        <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">AIC Kijabe Hospital Westlands Branch</h1>

        <!-- Introduction Section -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Our Westlands Branch</h2>
            <p class="text-gray-700 mb-6">
                The Westlands branch of Kijabe Hospital offers convenient access to high-quality healthcare services for residents of Nairobi. Located in the bustling Westlands district, this branch is equipped to provide comprehensive medical care, including general consultations, specialist services, and emergency care. Our dedicated team of healthcare professionals is committed to delivering compassionate and personalized care to every patient.
            </p>
            <p class="text-gray-700 mb-6">
                As an extension of the main Kijabe Hospital, the Westlands branch upholds the same standards of excellence and commitment to Christian values, ensuring that every patient receives the best possible care.
            </p>
        </div>

        <!-- Image Gallery Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Image 1 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/2020.jpg" alt="Westlands Branch Exterior" class="w-full h-64 object-cover">
            </div>

            <!-- Image 2 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/2014.jpg" alt="Westlands Branch Interior" class="w-full h-64 object-cover">
            </div>

            <!-- Image 3 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/1980s.jpg" alt="Patient Care at Westlands Branch" class="w-full h-64 object-cover">
            </div>
        </div>

        <!-- Map Section -->
        <div class="bg-white p-8 rounded-lg shadow-lg mt-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Location</h2>
            <p class="text-gray-700 mb-6">
                The Westlands branch is conveniently located in the heart of Nairobi's Westlands district, providing easy access to quality healthcare services. For directions and more information, please use the map below.
            </p>
            <div class="w-full h-64 bg-gray-200 rounded-lg overflow-hidden">
                <!-- Example map placeholder -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127880.2920237744!2d36.47958727408802!3d-0.9534888457680094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f19b2671f8e85%3A0x7467b885ebba2c73!2sKijabe%20Hospital!5e0!3m2!1sen!2ske!4v1693126598451!5m2!1sen!2ske" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    @include('layouts.footer')

</body>
</html>
