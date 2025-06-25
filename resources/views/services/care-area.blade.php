@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-16">
        <h1 class="text-3xl md:text-4xl font-bold text-[#159ed5] mb-4">{{ $careName }}</h1>
        <p class="text-gray-800 text-lg">Learn more about our {{ $careName }} services. Contact us for details or book an appointment below.</p>
        <a href="{{ route('booking.show') }}" class="mt-4 inline-block px-6 py-3 bg-[#159ed5] text-white rounded-lg hover:bg-[#0d7ca7]">Book an Appointment</a>
    </div>
@endsection