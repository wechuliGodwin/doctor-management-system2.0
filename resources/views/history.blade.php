@extends('layouts.app')

@section('content')
<div class="container mx-auto my-10 px-6">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-[#159ed5]">History of Kijabe Hospital</h1>
        <a href="{{ route('historytext') }}" class="text-blue-600 hover:underline">Text Version</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['year' => 1915, 'summary' => 'Establishment of Theodora Hospital.'],
            ['year' => 1920, 'summary' => 'Early Expansion.'],
            ['year' => 1950, 'summary' => 'Mid-century Developments.'],
            ['year' => 1960, 'summary' => 'Opening of the Present Complex.'],
            ['year' => 1980, 'summary' => 'Modernization Efforts.'],
            ['year' => 2014, 'summary' => 'Technological Advancements.'],
            ['year' => 2020, 'summary' => 'Response to Global Pandemic.'],
            ['year' => 2022, 'summary' => 'Future Plans and Growth.'],
        ] as $event)
            <a href="{{ route('historydetail', ['year' => $event['year']]) }}" class="block bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <img src="https://kijabehospital.org/images/history/{{ $event['year'] }}.jpg" alt="Kijabe Hospital in {{ $event['year'] }}" class="w-full h-64 object-cover">
                <div class="p-4">
                    <h2 class="text-xl font-bold text-center text-gray-800">{{ $event['year'] }}</h2>
                    <p class="text-gray-600 mt-2 text-center">{{ $event['summary'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
