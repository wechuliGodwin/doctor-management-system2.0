@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4 bg-white rounded-lg shadow-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="md:col-span-1">
            <div class="mt-10"> <!-- Added margin-top for perspective -->
                <h1 class="text-4xl font-bold mb-4 text-[#159ed5]">Stay Connected with Kijabe Hospital</h1>
                <p class="text-lg text-gray-700 mb-8">
                    Subscribe to our newsletter for the latest news, events, health tips, and inspiring stories from Kijabe Hospital. Keep up with our advancements and community outreach.
                </p>
                
                <!-- Subscription Form -->
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col md:flex-row items-start justify-start space-y-4 md:space-y-0 md:space-x-4 mb-6">
                    @csrf
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="Your Email Address" 
                        value="{{ old('email') }}"
                        class="w-full md:w-2/3 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#159ed5] text-gray-700" 
                        required
                    >
                    <button 
                        type="submit" 
                        class="w-full md:w-auto bg-[#159ed5] hover:bg-[#1279a8] text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out"
                    >
                        Subscribe Now
                    </button>
                </form>

                <!-- Explore Past Newsletters -->
                <div>
                    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Explore Past Newsletters</h2>
                    <p class="text-lg text-gray-700 mb-4">
                        Curious about what you'll receive? Here's a glimpse into our past issues:
                    </p>
                    <a href="https://kijabehospital.org/newsletters" class="inline-block" target="_blank">
                        <button class="bg-transparent border-2 border-[#159ed5] text-[#159ed5] hover:bg-[#159ed5] hover:text-white font-semibold py-2 px-6 rounded-lg transition duration-300 ease-in-out">
                            View Past Issues
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="md:col-span-1">
            <img src="{{ asset('images/newsletter.jpg') }}" alt="Kijabe Hospital Newsletter" class="w-full h-auto rounded-lg shadow-xl">
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-center" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection