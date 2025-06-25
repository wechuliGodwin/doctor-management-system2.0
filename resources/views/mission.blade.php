<!-- resources/views/mission.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Mission and Vision</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Include Header (Navigation) -->
    @include('layouts.navigation')

    <!-- Mission and Vision Section -->
    <div class="container mx-auto my-10 px-6">
        <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Our Mission and Vision</h1>

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

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Vision and Mission</h2>
            <p class="text-gray-700 mb-6">
                <strong>Our mission:</strong> Rooted in Christian values, the hospital’s mission remains steadfast – “To glorify God
                through the provision of compassionate health care, excellent medical training, and spiritual ministry in
                Jesus Christ”. It advocates for a deeper connection with our community, offering not just medical
                interventions but holistic care that nourishes the spirit and fosters hope.
            </p>
            <p class="text-gray-700">
                <strong>Our vision:</strong> To be a God-glorifying and transformative leader in the provision of excellent healthcare and education in Africa. We envision Kijabe Hospital as a beacon of medical excellence and innovation accessible to all, especially the underserved touching lives beyond the confines of traditional healthcare with holistic care that nourishes the spirit and fosters hope. 
            </p>
        </div>
    </div>

    <!-- Include Footer -->
    @include('layouts.footer')

</body>
</html>
