@extends('layouts.app')

@section('content')
<div class="py-16 px-6">
    <div class="container mx-auto flex flex-col lg:flex-row">
        <div class="w-full lg:w-3/4">
            <h1 class="text-4xl font-bold text-[#159ed5] mb-8">{{ $blog->title }}</h1>

            <div class="relative w-full">
                <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-screen object-cover mb-8">
            </div>

            <div class="blog-content text-gray-600 dark:text-gray-600 leading-relaxed mb-8">
                {!! nl2br(e($blog->content)) !!}
            </div>

            <div class="flex justify-center space-x-4 mt-8">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="text-[#159ed5] hover:text-blue-700">
                    <i class="fab fa-facebook-f fa-2x"></i> 
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="text-[#159ed5] hover:text-blue-400">
                    <i class="fab fa-twitter fa-2x"></i> 
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}&title={{ urlencode($blog->title) }}" target="_blank" class="text-[#159ed5] hover:text-blue-600">
                    <i class="fab fa-linkedin-in fa-2x"></i> 
                </a>
                <a href="mailto:?subject={{ urlencode($blog->title) }}&body={{ urlencode(request()->fullUrl()) }}" class="text-[#159ed5] hover:text-red-500">
                    <i class="fas fa-envelope fa-2x"></i> 
                </a>
            </div>
        </div>

        <div class="w-full lg:w-1/4 lg:pl-8 mt-12 lg:mt-0">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Other Blogs</h2>
            @foreach($otherBlogs as $otherBlog)
                <div class="mb-6">
                    <a href="{{ route('blog.show', $otherBlog->id) }}" class="flex items-center space-x-4">
                        <img src="{{ asset($otherBlog->image) }}" alt="{{ $otherBlog->title }}" class="w-20 h-20 object-cover rounded-lg">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $otherBlog->title }}</h3>
                        </div>
                    </a>
                </div>
            @endforeach

            <a href="{{ route('blog') }}" class="text-[#159ed5] hover:underline mt-4 block text-center"> 
                View All Blogs
            </a> 
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .blog-content p {
        margin-bottom: 1.5em; /* Adds space between paragraphs for readability */
    }
    .blog-content br {
        content: " ";
        display: block;
        margin-bottom: 1em;
    }
</style>
@endpush