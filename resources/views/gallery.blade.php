@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Gallery</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($images as $image)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="{{ asset('images/gallery/' . $image) }}" alt="Gallery Image" class="w-full h-64 object-cover">
            </div>
        @endforeach
    </div>
</div>
@endsection
