<!-- resources/views/main-branch.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Kijabe Branch</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Include Header (Navigation) -->
    @include('layouts.navigation')

    <!-- Main Branch Section -->
    <div class="container mx-auto my-10 px-6">
        <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Kijabe Hospital Main Branch</h1>

        <!-- Introduction Section -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Kijabe Hospital HQ</h2>
            <p class="text-gray-700 mb-6">
                The main branch of Kijabe Hospital is located in the serene hills of Kijabe, providing a tranquil environment for healing and recovery. As the headquarters, this branch serves as the central hub for all administrative and operational activities, ensuring that the hospital’s mission and vision are upheld across all branches and services.
            </p>
            <p class="text-gray-700 mb-6">
                Our main branch is equipped with state-of-the-art medical facilities, highly skilled healthcare professionals, and a commitment to providing compassionate and holistic care. From specialized medical services to spiritual support, Kijabe Hospital HQ is dedicated to serving our community with excellence and integrity.
            </p>
        </div>

        <!-- Image Gallery Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Image 1 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/2020.jpg" alt="Kijabe Hospital Main Branch" class="w-full h-64 object-cover">
            </div>

            <!-- Image 2 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/2014.jpg" alt="Inside the Main Branch" class="w-full h-64 object-cover">
            </div>

            <!-- Image 3 -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="https://kijabehospital.org/images/history/1980s.jpg" alt="Aerial View of Kijabe Hospital" class="w-full h-64 object-cover">
            </div>
        </div>

        <!-- Map Section -->
        <div class="bg-white p-8 rounded-lg shadow-lg mt-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Location</h2>
            <p class="text-gray-700 mb-6">
                Kijabe Hospital is located in the Kijabe area, approximately 60 kilometers northwest of Nairobi, Kenya. The hospital is accessible by road and offers a peaceful setting that supports recovery and well-being.
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
