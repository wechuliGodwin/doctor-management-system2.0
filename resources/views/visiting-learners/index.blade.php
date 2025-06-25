{{-- Inside resources/views/visiting-learners/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-12 p-6">
        @if(session('success'))
            <div class="bg-[#159ed5] border border-[#107ba3] text-white px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @elseif(session('error'))
            <div class="bg-[#159ed5] border border-[#107ba3] text-white px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Application Received</h1>
        <p class="text-gray-700 text-center text-lg">Your Visiting Learner application has been received. You will be
            contacted soon.</p>
    </div>
@endsection
