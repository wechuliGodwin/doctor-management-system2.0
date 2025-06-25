@extends('layouts.app')

@section('title', 'Donate to Friends of Kijabe')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-6 text-center">
        <h1 class="text-2xl font-bold mb-4">Support Friends of Kijabe</h1>
        <p class="text-gray-700 mb-6">We appreciate your generosity. You are being redirected to our donation site.</p>
        <p class="text-gray-700 mb-6">If you are not redirected automatically, please click the button below to donate.</p>
        <a href="https://friendsofkijabe.org" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Donate Now</a>
    </div>
</div>
<script>
    setTimeout(function() {
        window.location.href = "https://friendsofkijabe.org";
    }, 3000); // Redirect after 3 seconds
</script>
@endsection
