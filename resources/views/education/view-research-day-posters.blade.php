@extends('layouts.app')

@section('title', 'Research Day Posters - Kiddie Steps School')

@section('content')
<!-- Main Container -->
<div class="container mx-auto my-8 px-4">
    <!-- Header Section -->
    <header class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-bold text-kiddie-teal animate__animated animate__fadeInDown" style="text-shadow: 2px 2px 4px rgba(255,255,255,0.8);">
            Research Day 2025 Posters
        </h1>
        <p class="text-lg md:text-xl font-serif text-gray-700 mt-4 animate__animated animate__fadeInUp">
            Discover innovative projects and breakthrough research at Kijabe Hospital. Click on a title to view poster.
        </p>
    </header>

    <!-- Search Bar -->
    <div class="search-container flex justify-center mb-8">
        <form method="GET" action="{{ route('view-research-day-posters') }}" class="w-full max-w-lg">
            <input type="text" name="search" placeholder="Search posters..." value="{{ request('search') }}"
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-kiddie-teal shadow-sm transition duration-300">
        </form>
    </div>

    <div class="flex flex-col md:flex-row-reverse gap-8">
        <!-- Sidebar -->
        <aside class="w-full md:w-1/4">
            <!-- Categories -->
            <div class="mb-8 bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-kiddie-teal mb-4">Categories</h3>
                <ul class="space-y-2">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('view-research-day-posters', ['category' => $cat]) }}"
                               class="block text-gray-700 hover:text-kiddie-teal transition">
                                {{ ucwords(str_replace('_', ' ', $cat)) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- Top 10 Presenters -->
<div class="bg-white p-4 rounded-lg shadow-md">
    <h3 class="text-xl font-bold text-kiddie-teal mb-4">Presenters</h3>
    <ul class="space-y-2">
        @foreach($topPresenters as $presenter)
            <li>
                <a href="{{ route('view-research-day-posters', ['presenter' => $presenter['name']]) }}"
                   class="block text-gray-700 hover:text-kiddie-teal transition">
                    {{ ucwords($presenter['name']) }} ({{ $presenter['count'] }})
                </a>
            </li>
        @endforeach
    </ul>
</div>
        </aside>

        <!-- Posters List -->
        <main class="w-full md:w-3/4">
            @if($registeredPosters->isEmpty())
                <p class="text-center text-gray-500 italic py-8">No posters available at this time.</p>
            @else
                <table class="poster-table w-full">
                    <thead>
                        <tr>
                            <th class="title-column px-4 py-3">Title</th>
                            <th class="authors-column px-4 py-3">Authors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group posters by category for display
                            $groupedPosters = [];
                            foreach ($registeredPosters as $poster) {
                                // Use the first category if available, otherwise "Uncategorized"
                                $category = (is_array($poster->categories) && count($poster->categories) > 0) ? $poster->categories[0] : 'Uncategorized';
                                                                $groupedPosters[$category][] = $poster;
                            }
                        @endphp

                        @foreach($groupedPosters as $category => $posters)
                            <tr class="category-row bg-kiddie-teal text-white">
                                <td colspan="2" class="px-4 py-3">{{ ucwords(str_replace('_', ' ', $category)) }}</td>
                            </tr>
                            @foreach($posters as $poster)
                                <tr class="data-row hover:bg-gray-100 transition-all duration-300">
                                    <td class="title-column px-4 py-3">
                                        <a href="{{ route('view-poster', ['id' => $poster->id]) }}" class="poster-title block text-kiddie-teal hover:text-kiddie-pink transition-colors">
                                            {{ ucwords(strtolower($poster->title ?? 'Untitled')) }}
                                        </a>
                                    </td>
                                    <td class="authors-column px-4 py-3">
                                        @php
                                            $allAuthors = array_unique(array_merge(
                                                array_map('trim', explode(',', $poster->names ?? '')),
                                                array_map('trim', explode(',', $poster->co_investigators ?? ''))
                                            ));
                                        @endphp
                                        <span class="authors text-gray-600 italic text-sm">
                                            {{ implode(', ', $allAuthors) ?: 'No authors specified' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
        </main>
    </div>
</div>


<!-- JavaScript for Poster Link Hover & Modal Handling -->
<script>
    // Make the poster title clickable (opens poster in new tab)
    document.querySelectorAll('.poster-title').forEach(link => {
        link.addEventListener('click', function(e) {
            // Optional: You can add custom animation or confirmation here
        });
    });

    // Gallery Modal: In case you want to show enlarged poster images
    document.querySelectorAll('.gallery-item img').forEach(img => {
        img.addEventListener('click', function() {
            const modalImg = document.querySelector('#galleryModal img');
            modalImg.src = this.dataset.bsSrc;
        });
    });
</script>

<!-- Custom CSS -->
<style>
    :root {
        --kiddie-pink: #ff6b6b;
        --kiddie-teal: #159ed5; /* Updated to the specified theme color */
        --kiddie-yellow: #fff3e6;
    }
    .poster-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2rem;
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }
    .poster-table th {
        background: linear-gradient(90deg, var(--kiddie-teal) 0%, var(--kiddie-pink) 100%);
        color: white;
        font-weight: 700;
        padding: 1rem;
        text-transform: uppercase;
    }
    .poster-table td {
        padding: 1rem;
        border-bottom: 1px solid #edf2f7;
        font-size: 1rem;
        color: #2d3748;
    }
    .category-row {
        background: #e0f7fa; /* Light background for category rows */
        font-weight: 600;
        font-size: 1.25rem;
        color: var(--kiddie-teal);
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .category-row:hover {
        background: #b2ebf2; /* Slightly darker on hover */
    }
    .poster-title {
        text-decoration: none;
    }
    .poster-title:hover {
        text-decoration: underline;
    }
</style>
@endsection