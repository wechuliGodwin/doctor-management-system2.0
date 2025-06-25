@extends('layouts.app')

@section('content')
@php
    $year = request()->route('year');
    $details = [
        '1915' => "Kijabe Mission Station was first established by missionaries from AIM as an outpost in 1903. The first hospital at Kijabe, Theodora Hospital, was established in 1915. This served the medical needs of the area until the present complex was begun. The first building of the present complex was opened in 1961.

Today, Kijabe Hospital is a non-profit, 363-bed hospital owned and operated by AIC of Kenya as part of a network of four hospitals and 45 dispensaries. It employs over 900 staff and strives to balance Kenyan and missionary consultants. The hospital offers a broad range of inpatient and outpatient curative services to people from the surrounding farming communities...

",
        '1920' => "[Include details for 1920]",
        '1950' => "[Include details for 1950]",
        '1960' => "[Include details for 1960]",
        '1980' => "[Include details for 1980]",
        '2014' => "[Include details for 2014]",
        '2020' => "[Include details for 2020]",
        '2022' => "[Include details for 2022]",
    ];
@endphp

<div class="container mx-auto my-10 px-6">
    <div class="mb-6">
        <a href="{{ route('history') }}" class="text-blue-600 hover:underline">&larr; Back to History</a>
    </div>
    <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Kijabe Hospital in {{ $year }}</h1>

    <div class="max-w-3xl mx-auto">
        <img src="https://kijabehospital.or.ke/images/history/{{ $year }}.jpg" alt="Kijabe Hospital in {{ $year }}" class="w-full h-auto mb-6 rounded shadow">

        @if(isset($details[$year]))
            <div class="prose prose-lg text-gray-800">
                {!! nl2br(e($details[$year])) !!}
            </div>
        @else
            <div class="text-center text-red-500">
                <p>Details not found for the year {{ $year }}.</p>
            </div>
        @endif
    </div>
</div>
@endsection
