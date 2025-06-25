<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Day 2025 Posters</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; }
        .gallery-item { position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .gallery-item:hover { transform: scale(1.05); }
        .gallery-item img { width: 100%; height: auto; display: block; }
        .overlay { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); color: white; padding: 10px; text-align: center; opacity: 0; transition: opacity 0.3s; }
        .gallery-item:hover .overlay { opacity: 1; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold">Research Day 2025 in Pictures</h1>
            <p class="mt-2">Catch the moments from Research Day 2025</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="gallery-grid">
            @php
                $imageDir = public_path('images/research2025');
                $images = array_diff(scandir($imageDir), ['.', '..']);
            @endphp
            @foreach ($images as $image)
                <div class="gallery-item">
                    <img src="{{ asset('images/research2025/' . $image) }}" alt="Research Poster">
                    <div class="overlay">{{ pathinfo($image, PATHINFO_FILENAME) }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>