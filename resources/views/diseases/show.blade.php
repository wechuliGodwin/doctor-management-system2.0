@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="text-sm mb-6" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('diseases.index') }}" class="text-[#159ed5] hover:underline">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">{{ $disease->name }}</li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row">
        <aside class="w-full lg:w-1/4 lg:pr-8 mb-8 lg:mb-0">
            <div class="bg-white p-4 rounded-lg shadow-md sticky top-20">
                <h2 class="text-xl font-semibold mb-4">Quick Links</h2>
                <ul class="space-y-2">
                    <li><a href="#description" class="text-[#159ed5] hover:underline">Description</a></li>
                    @if ($disease->causes)
                        <li><a href="#causes" class="text-[#159ed5] hover:underline">Causes</a></li>
                    @endif
                    @if ($disease->cure)
                        <li><a href="#cure" class="text-[#159ed5] hover:underline">Cure</a></li>
                    @endif
                    @if ($disease->treatment)
                        <li><a href="#treatment" class="text-[#159ed5] hover:underline">Treatment</a></li>
                    @endif
                    @if ($disease->symptoms)
                        <li><a href="#symptoms" class="text-[#159ed5] hover:underline">Symptoms</a></li>
                    @endif
                    @if ($disease->risk_factors)
                        <li><a href="#risk-factors" class="text-[#159ed5] hover:underline">Risk Factors</a></li>
                    @endif
                    @if ($disease->when_to_see_doctor)
                        <li><a href="#when-to-see-doctor" class="text-[#159ed5] hover:underline">When to See a Doctor</a></li>
                    @endif
                </ul>
            </div>
        </aside>

        <main class="w-full lg:w-3/4">
            <h1 class="text-4xl font-bold text-[#159ed5] mb-4">{{ $disease->name }}</h1>

            @if ($disease->image)
                <img src="{{ asset($disease->image) }}" alt="{{ $disease->name }}" class="w-full h-auto mb-6 rounded-lg shadow-md object-cover" loading="lazy">
            @endif

            <section id="description" class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Description</h2>
                {!! $disease->description !!}
            </section>

            @if ($disease->causes)
                <section id="causes" class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b-2 border-[#159ed5] pb-2">
                        <i class="fas fa-search-plus mr-2"></i>Causes & Pathogenesis
                    </h2>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        {!! $disease->causes !!}
                        {{-- Add additional medical content elements --}}
                        @if($disease->genetic_factors || $disease->environmental_factors)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                @if($disease->genetic_factors)
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-[#159ed5] mb-2">
                                            <i class="fas fa-dna mr-2"></i>Genetic Factors
                                        </h4>
                                        <p class="text-gray-700">{{ $disease->genetic_factors }}</p>
                                    </div>
                                @endif
                                @if($disease->environmental_factors)
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <h4 class="font-semibold text-[#159ed5] mb-2">
                                            <i class="fas fa-leaf mr-2"></i>Environmental Factors
                                        </h4>
                                        <p class="text-gray-700">{{ $disease->environmental_factors }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </section>
            @endif

            @if ($disease->cure)
                <section id="cure" class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Cure</h2>
                    {!! $disease->cure !!}
                </section>
            @endif

            @if ($disease->treatment)
                <section id="treatment" class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Treatment</h2>
                    {!! $disease->treatment !!}
                </section>
            @endif

            @if ($disease->symptoms)
                <section id="symptoms" class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Symptoms</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        @foreach(explode(',', $disease->symptoms) as $symptom)
                            <li>{{ trim($symptom) }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($disease->risk_factors)
                <section id="risk-factors" class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Risk Factors</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        @foreach(explode(',', $disease->risk_factors) as $risk)
                            <li>{{ trim($risk) }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($disease->when_to_see_doctor)
                <section id="when-to-see-doctor" class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">When to See a Doctor</h2>
                    {!! $disease->when_to_see_doctor !!}
                </section>
            @endif

            <div class="mb-8">
                <a href="{{ route('booking.show') }}" class="inline-block bg-[#159ed5] hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                    Book an Appointment
                </a>
            </div>

            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Share This Page</h3>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener noreferrer" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($disease->name) }}" target="_blank" rel="noopener noreferrer" class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded">
                        Twitter
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}&title={{ urlencode($disease->name) }}" target="_blank" rel="noopener noreferrer" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                        LinkedIn
                    </a>
                </div>
            </div>

            @if($relatedDiseases->count())
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Related Diseases</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($relatedDiseases as $related)
                            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                @if($related->image)
                                    <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="w-full h-40 object-cover rounded-md mb-2" loading="lazy">
                                @endif
                                <h3 class="text-xl font-bold text-[#159ed5] mb-2">
                                    <a href="{{ route('diseases.show', $related->id) }}" class="hover:underline">
                                        {{ $related->name }}
                                    </a>
                                </h3>
                                <p class="text-gray-700">{{ Str::limit($related->overview, 100) }}</p>
                                <a href="{{ route('diseases.show', $related->id) }}" class="text-[#159ed5] hover:underline font-semibold mt-2 inline-block">
                                    Read More
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>

    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" class="fixed bottom-6 right-6 bg-[#159ed5] hover:bg-blue-700 text-white p-3 rounded-full shadow-lg focus:outline-none">
        ?
    </button>
</div>
@endsection