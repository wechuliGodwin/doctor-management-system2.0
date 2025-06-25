<!-- resources/views/updates.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Latest Updates</h1>
        <p class="mb-6 text-gray-700 dark:text-gray-300">Stay informed with the latest news from Kijabe Hospital:</p>
        <ul class="space-y-4">
            <li class="flex items-start">
                <i class="fas fa-video text-blue-500 mr-2 mt-1"></i>
                <span class="text-gray-700 dark:text-gray-300"><strong>Telemedicine Available:</strong> Access our Telemedicine services for remote consultations. <a href="{{ route('telemedicine-patient') }}" class="text-blue-500 hover:underline">Learn More</a></span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mr-2 mt-1"></i>
                <span class="text-gray-700 dark:text-gray-300"><strong>No MRI Services:</strong> We currently don't have MRI services, kindly note.</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-book-open text-blue-500 mr-2 mt-1"></i>
                <span class="text-gray-700 dark:text-gray-300"><strong>Research Writing Workshop:</strong> Feeling stuck? Complete your manuscript by the end of 2025. Join our 6 full-day workshop on May 8-9, June 5-6, and July 3-4. Training cost: Kes 16,500. Get feedback from peers, international researchers, and local writing mentors. Scan the QR code to apply. <span class="text-blue-500">#110YearsLegacy #EyesForward</span></span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-clinic-medical text-blue-500 mr-2 mt-1"></i>
                <span class="text-gray-700 dark:text-gray-300"><strong>Chronic Pain Clinic:</strong> We now have a visiting Chronic Pain Specialist. Clinic days: March 25th & 28th. Procedure days: March 27th & 31st. To book a clinic, please contact <a href="tel:+254709728215" class="text-blue-500 hover:underline">0709728215</a>. <span class="text-blue-500">#CompassionateHealthcare #110YearsLegacy</span></span>
            </li>
        </ul>
    </div>
@endsection