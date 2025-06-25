<!-- resources/views/strategy.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strategic Plan 2024-2029</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Include Header (Navigation) -->
    @include('layouts.navigation')

    <!-- Strategy Section -->
    <div class="container mx-auto my-10 px-6">
        <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Strategic Plan 2024-2029</h1>

        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Introduction</h2>
            <p class="text-gray-700 mb-6">
                Kijabe Hospital, with its century-long legacy, has emerged as a cornerstone in Kenya's healthcare
                landscape, known for its high-quality, compassionate care, and a commitment to Christian values.
                Kijabe Hospital embarks on an ambitious journey with its Strategic Plan for 2024-2029, anchored by
                the vision to impact a million lives annually in Africa through exemplary healthcare and education. This
                plan outlines our path forward, addressing critical goals and overcoming challenges to emerge
                stronger and more impactful.
            </p>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Strategic Goals</h2>
            <ul class="list-disc list-inside text-gray-700 mb-6">
                <li>Enhance healthcare services to meet the needs of a growing population.</li>
                <li>Expand medical training programs to produce highly skilled healthcare professionals.</li>
                <li>Foster partnerships to enhance research and innovation in healthcare.</li>
                <li>Increase community outreach initiatives to promote holistic wellness and prevention.</li>
                <li>Strengthen the financial sustainability of the hospital.</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Commitment to Values</h2>
            <p class="text-gray-700">
                Rooted in Christian values, Kijabe Hospital remains committed to providing compassionate health care,
                excellent medical training, and spiritual ministry. Our mission to glorify God through service is
                unwavering, and we strive to be a transformative leader in healthcare and education across Africa.
            </p>
        </div>
    </div>

    <!-- Include Footer -->
    @include('layouts.footer')

</body>
</html>
