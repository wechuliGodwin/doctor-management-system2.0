<!-- resources/views/marira-branch.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marira Branch - Kijabe Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Include Header (Navigation) -->
    @include('layouts.navigation')

    <!-- Marira Branch Section -->
    <div class="container mx-auto my-10 px-6">
        <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Kijabe Hospital Marira Branch</h1>

        <!-- Introduction Section -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Our Marira Branch</h2>
            <p class="text-gray-700 mb-6">
                The Marira branch of Kijabe Hospital extends our commitment to providing accessible, high-quality healthcare to communities beyond our main campus. Located in a serene environment, the Marira branch is designed to offer a range of medical services including general health check-ups, specialized consultations, and diagnostic services. Our team at Marira is dedicated to delivering care that is compassionate and aligned with our Christian values.
            </p>
            <p class="text-gray-700 mb-6">
                As part of the Kijabe Hospital network, the Marira branch adheres to the same standards of medical excellence and spiritual support, ensuring every patient receives comprehensive and holistic care.
            </p>
        </div>

        <!-- Image Gallery Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Image 1 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/2020.jpg" alt="Marira Branch Exterior" class="w-full h-64 object-cover">
            </div>

            <!-- Image 2 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/2014.jpg" alt="Inside the Marira Branch" class="w-full h-64 object-cover">
            </div>

            <!-- Image 3 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/1980s.jpg" alt="Patient Care at Marira Branch" class="w-full h-64 object-cover">
            </div>
        </div>

        <!-- Map Section -->
        <div class="bg-white p-8 rounded-lg shadow-lg mt-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Location</h2>
            <p class="text-gray-700 mb-6">
                The Marira branch is conveniently located to serve the local community with easy access to quality healthcare. Use the map below to find directions to our Marira branch and learn more about our services.
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
