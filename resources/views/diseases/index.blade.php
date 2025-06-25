@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Title -->
    <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Disease Database</h1>

    <!-- Alphabet Navigation -->
    <div class="flex flex-wrap justify-center mb-6">
        @foreach($alphabet as $char)
            <a href="{{ route('diseases.index', array_merge(request()->all(), ['letter' => $char])) }}"
               class="m-1 px-3 py-1 border border-[#159ed5] text-[#159ed5] rounded-full hover:bg-[#159ed5] hover:text-white transition-colors duration-300
                      {{ isset($letter) && $letter === $char ? 'bg-[#159ed5] text-white' : '' }}">
                {{ $char }}
            </a>
        @endforeach
        <!-- Reset Filter Option -->
        @if(isset($letter))
            <a href="{{ route('diseases.index', array_merge(request()->all(), ['letter' => null])) }}"
               class="m-1 px-3 py-1 border border-red-500 text-red-500 rounded-full hover:bg-red-500 hover:text-white transition-colors duration-300">
                All
            </a>
        @endif
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('diseases.index') }}" class="mb-8">
        <div class="flex items-center justify-center">
            <input
                type="text"
                name="query"
                value="{{ old('query', $query) }}"
                placeholder="Search diseases..."
                class="w-full max-w-md p-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#159ed5]"
            >
            @if(isset($letter))
                <input type="hidden" name="letter" value="{{ $letter }}">
            @endif
            <button type="submit" class="bg-[#159ed5] hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-r-lg">
                Search
            </button>
        </div>
    </form>

    @if($diseases->count())
        <!-- Diseases List -->
        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($diseases as $disease)
                <li class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    @if ($disease->image)
                        <img src="{{ asset($disease->image) }}" alt="{{ $disease->name }}" class="w-full h-40 object-cover rounded-md mb-4">
                    @endif
                    <h2 class="text-2xl font-bold text-[#159ed5] mb-2">
                        <a href="{{ route('diseases.show', $disease->id) }}" class="hover:underline">
                            {{ $disease->name }}
                        </a>
                    </h2>
                    <p class="text-gray-700 mb-4">{{ Str::limit($disease->overview, 100) }}</p>
                    <a href="{{ route('diseases.show', $disease->id) }}" class="text-[#159ed5] hover:underline font-semibold">
                        Learn More
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Pagination Links -->
        <div class="mt-8 flex justify-center">
            {{ $diseases->links() }}
        </div>
    @else
        <p class="text-center text-gray-600">No diseases found{{ $query ? ' for your search.' : '.' }}</p>
    @endif
</div>
@endsection