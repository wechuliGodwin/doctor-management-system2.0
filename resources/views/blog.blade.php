@extends('layouts.app')

@section('content')

<!-- News Section -->
<div class="py-16 px-6">
    <h2 class="text-3xl font-semibold text-center text-[#159ed5] mb-8">Recent Kijabe Hospital News</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @php
            // Fetch blogs directly in Blade (not recommended for production)
            $blogs = DB::table('blogs')->get();
        @endphp

        @foreach($blogs as $blog)
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover rounded-t-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-[#159ed5] mb-4">{{ $blog->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ Str::limit($blog->content, 100) }}</p>
                    <a href="{{ route('blog.show', $blog->id) }}" class="text-[#159ed5] font-semibold hover:underline">Read More</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
