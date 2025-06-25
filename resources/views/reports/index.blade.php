@extends('layouts.auth')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Patient Reports</h1>

    @if($appointments->isEmpty())
        <p class="text-center text-gray-600">No reports available for your appointments.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Appointment Date</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Doctor</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Service</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Report</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td class="border-t py-2 px-4">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                        <td class="border-t py-2 px-4">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                        <td class="border-t py-2 px-4">{{ $appointment->service->name ?? 'N/A' }}</td>
                        <td class="border-t py-2 px-4">
    				@if($appointment->path)
        			<a href="{{ asset('storage/uploads/' . basename($appointment->path)) }}" target="_blank" class="text-blue-500 hover:underline">View Report</a>
    				@else
        			No Report
    				@endif
			</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
