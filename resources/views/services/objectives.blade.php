@extends('layouts.microsoft')

@section('content')
<div class="flex flex-col md:flex-row-reverse min-h-screen">
    <!-- Sidebar -->
    <div class="w-full md:w-1/4 bg-gradient-to-b from-[#159ed5] to-[#6c5dd3] p-6 shadow-xl">
        <div class="sticky top-6">
            <ul class="space-y-4">
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <a href="{{ route('emr.benchmarking.page') }}" class="text-white">Back to EMR Benchmarking</a>
                </li>
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <a href="{{ route('emr.objectives.list') }}" class="text-white">Objectives Submitted</a>
                </li>
                <li class="bg-white/10 p-4 rounded-lg backdrop-blur-sm transition-all hover:bg-white/20">
                    <a href="{{ route('emr.features.list') }}" class="text-white">Feature Requests</a>
                </li>
                <!-- Other links for Upcoming Events if needed -->
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-full md:w-3/4 p-8 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-[#1a365d] mb-6">Submitted Objectives</h1>
            
            @if($objectives->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow-lg rounded-lg">
                        <thead class="bg-[#159ed5] text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Objective</th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Contact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($objectives as $objective)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $objective->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $objective->department }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $objective->objective }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($objective->contact)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Requested
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Not Requested
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $objectives->links() }} <!-- Using default Laravel pagination -->
                </div>
            @else
                <p class="text-gray-600 text-center">No objectives have been submitted yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection