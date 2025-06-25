@extends('layouts.app')

@section('content')
<div class="container mx-auto my-10 px-6 flex flex-col lg:flex-row gap-6">
    <!-- Main Content (unchanged) -->
    <div class="lg:w-3/4 bg-gradient-to-r from-[#159ed5] to-[#4ecdc4] text-white shadow-lg rounded-lg overflow-hidden">
        @php
            $correctCode = '131187';
            $enteredCode = session('access_code', '');
            $isAuthenticated = $enteredCode === $correctCode;
        @endphp

        @if(!$isAuthenticated)
            <!-- Code Entry Form -->
            <div class="p-6 bg-white rounded-lg text-center">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">Enter Access Code</h1>
                <form method="POST" action="{{ route('suppliers.bulkmail.code') }}" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" name="code" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]" placeholder="Enter code here" required>
                    </div>
                    <button type="submit" class="w-full bg-[#159ed5] text-white py-3 rounded-md shadow-md hover:bg-blue-600 transition duration-300">
                        Submit Code
                    </button>
                </form>

                @if(request()->isMethod('post') && request()->has('code'))
                    @php
                        $submittedCode = request()->input('code');
                        if($submittedCode === $correctCode) {
                            session(['access_code' => $submittedCode]);
                            return redirect()->route('suppliers.bulkmail');
                        } else {
                            echo '<p class="text-red-600 mt-4">Incorrect code. Please try again.</p>';
                        }
                    @endphp
                @endif
            </div>
        @else
            <!-- Header for Bulk Mail Section -->
            <div class="p-6 border-b border-gray-200 border-opacity-25">
                <h1 class="text-3xl font-bold mb-4">Web Management Dashboard</h1>
                @if (session('success'))
                    <div class="alert alert-success bg-green-200 border-t border-b border-green-500 text-green-800 px-4 py-3 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <!-- Bulk Mail Form -->
            <div class="p-6 bg-white rounded-b-lg">
                <form action="{{ route('suppliers.bulkmail.send') }}" method="POST" id="bulkMailForm" class="space-y-6">
                    @csrf
                    <button type="submit" class="w-full bg-[#159ed5] text-white py-3 rounded-md shadow-md hover:bg-blue-600 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i> Send Bulk Mail
                    </button>
                </form>
            </div>

            <!-- Suppliers List -->
            <div class="p-6 bg-white shadow-lg rounded-lg mt-6">
                <div class="overflow-x-auto relative">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">Name</th>
                                <th scope="col" class="py-3 px-6">Email</th>
                                <th scope="col" class="py-3 px-6">Alternate Email</th>
                                <th scope="col" class="py-3 px-6">Telephone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6">{{ $supplier->name }}</td>
                                    <td class="py-4 px-6"><a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:underline">{{ $supplier->email }}</a></td>
                                    <td class="py-4 px-6">{!! $supplier->alternate_email ? '<a href="mailto:'. $supplier->alternate_email .'" class="text-blue-600 hover:underline">'. $supplier->alternate_email .'</a>' : 'N/A' !!}</td>
                                    <td class="py-4 px-6">{{ $supplier->telephone ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar for Page Statistics -->
    <div class="lg:w-1/4 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Page Statistics</h2>
        
        <!-- Total Views Per Day (Today) -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Views Today</h3>
            <p class="text-2xl font-bold text-[#159ed5]">{{ $viewsPerDay }}</p>
            @if($viewsPerPageToday->isNotEmpty())
                <ul class="space-y-2 mt-2">
                    @foreach($viewsPerPageToday as $page)
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-600">/{{ $page->url }}</span>
                            <span class="font-semibold text-[#159ed5]">{{ $page->total_views }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500 mt-2">No page views recorded for today.</p>
            @endif
        </div>

        <!-- All Pages Stats (Total Views) -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Page Views</h3>
            @if($pageStats->isNotEmpty())
                <ul class="space-y-2">
                    @foreach($pageStats as $stat)
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-600">/{{ $stat->url }}</span>
                            <span class="font-semibold text-[#159ed5]">{{ $stat->total_views }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">No page views recorded yet.</p>
            @endif
        </div>

        <!-- Daily Stats (Last 30 Days) -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Daily Views (Last 30 Days)</h3>
            @if($dailyStats->isNotEmpty())
                <ul class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($dailyStats as $stat)
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $stat->view_date }}</span>
                            <span class="font-semibold text-[#159ed5]">{{ $stat->total_views }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">No daily views recorded yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection