@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 p-6">
    <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Research Pathways</h1>

    <div class="flex flex-col md:flex-row justify-center items-center gap-8">
        <!-- Pre-Award Pathway -->
        <a href="{{ asset('preaward.pdf') }}" target="_blank" class="bg-white shadow-md rounded-lg p-6 w-full md:w-1/3 relative overflow-hidden group block hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-2xl font-semibold text-[#159ed5] mb-4 text-center">Pre-Award Pathway</h2>
            <p class="text-gray-700 mb-4 font-light text-center">Explore the steps involved before a research award is granted, covering proposal development, budgeting, and submission.</p>
            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-[#159ed5] transform transition-transform duration-300 ease-in-out group-hover:bg-[#159ed5] group-hover:border-transparent"></div>
        </a>

        <!-- Post-Award Pathway -->
        <a href="{{ asset('award.pdf') }}" target="_blank" class="bg-white shadow-md rounded-lg p-6 w-full md:w-1/3 relative overflow-hidden group block hover:shadow-lg transition-shadow duration-300">
            <h2 class="text-2xl font-semibold text-[#159ed5] mb-4 text-center">Post-Award Pathway</h2>
            <p class="text-gray-700 mb-4 font-light text-center">Understand the processes following a research award, including project management, compliance, and reporting.</p>
            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-[#159ed5] transform transition-transform duration-300 ease-in-out group-hover:bg-[#159ed5] group-hover:border-transparent"></div>
        </a>
    </div>
</div>
@endsection
