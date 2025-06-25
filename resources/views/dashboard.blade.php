@extends('layouts.auth') <!-- Using the logged-in user layout -->

@section('content')
<div class="container mx-auto p-6">
    <!-- Welcome Section -->
    <div class="text-center text-lg font-semibold text-gray-700 mb-6">
        Welcome, {{ auth()->user()->name }} <!-- Display the logged-in user's name here -->
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-20">
        <!-- Talk to a Doctor -->
        <div class="bg-blue-600 text-white shadow-lg rounded-lg p-6 transform transition duration-500 hover:scale-105 hover:shadow-xl">
            <h2 class="text-lg font-semibold">Our Services</h2>
            <p class="text-2xl font-bold">
                <a href="#" onclick="openModal('talkDoctorModal')" class="hover:text-white underline">Services</a>
            </p>
        </div>

        <!-- Available Services -->
        <div class="bg-green-600 text-white shadow-lg rounded-lg p-8 transform transition duration-500 hover:scale-105 hover:shadow-xl">
            <h2 class="text-lg font-semibold">Book Appointment</h2>
            <p class="text-2xl font-bold">
                <a href="#" onclick="openModal('serviceModal')" class="hover:text-white underline">{{ $availableServices }} specialities</a>
            </p>
        </div>

        <!-- All Appointments -->
        <div class="bg-purple-600 text-white shadow-lg rounded-lg p-6 transform transition duration-500 hover:scale-105 hover:shadow-xl">
            <h2 class="text-lg font-semibold">All Appointments</h2>
            <p class="text-2xl font-bold">
                <a href="#" onclick="openModal('appointmentsModal')" class="hover:text-white underline">{{ $availableTimeSlots }} Appointments</a>
            </p>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-48">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Upcoming Appointments</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Status</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Platform</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Date</th>
                        <th class="px-6 py-4 bg-gray-200 text-gray-600 font-semibold text-left">Payment Status</th>
			<th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Meeting</th>
                         <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Medication Prescription</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingAppointments as $appointment)
                    <tr>
                        <td class="border-t py-2 px-4">{{ $appointment->status }}</td>
                        <td class="border-t py-2 px-4">{{ $appointment->platform ?? 'No patient assigned' }}</td>
                        <td class="border-t py-2 px-4">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
		    


				<td class="border-t py-2 px-4">{{ ucfirst($appointment->payment_status) }}</td>


                <td class="border-t py-2 px-4">
                    @if ($appointment->meeting_id)
                        <a href="{{ $appointment->meeting_id }}" target="_blank" class="text-green-500 hover:underline">
                            Meeting Link
                        </a>
                    @else
                        <span class="text-gray-400">No meeting link</span>
                    @endif
                </td>



 <td class="border-t py-2 px-4">
                            @if($appointment->prescribe)
                                <a href="{{ route('download.prescription', $appointment->id) }}" class="text-blue-500 hover:underline">
                                    Download
                                </a>
                            @else
                                <span class="text-gray-400">Not Available</span>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Talk to a Doctor Modal -->
<div id="talkDoctorModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full transform transition duration-500 hover:scale-105">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Our Services</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <div class="p-4 rounded-lg bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-General Medicine Consultation</span>
                <span class="text-gray-600 font-semibold">500</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-green-200 via-green-300 to-green-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Adult Psychiatry</span>
                <span class="text-gray-600 font-semibold">4500</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-pink-200 via-pink-300 to-pink-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Child Psychiatry</span>
                <span class="text-gray-600 font-semibold">6000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-green-200 via-green-300 to-green-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Specialty consultation</span>
                <span class="text-gray-600 font-semibold">1000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Private consultation</span>
                <span class="text-gray-600 font-semibold">3000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-yellow-200 via-yellow-300 to-yellow-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Psychotherapy</span>
                <span class="text-gray-600 font-semibold">1000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-red-200 via-red-300 to-red-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Nutrition Consultation</span>
                <span class="text-gray-600 font-semibold">1000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-green-200 via-green-300 to-green-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Child Psychiatry follow up</span>
                <span class="text-gray-600 font-semibold">5000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-green-200 via-green-300 to-green-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Adult Psychiatry follow up</span>
                <span class="text-gray-600 font-semibold">4000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-yellow-200 via-yellow-300 to-yellow-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Palliative consultation</span>
                <span class="text-gray-600 font-semibold">500</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-green-200 via-green-300 to-green-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Palliative follow up</span>
                <span class="text-gray-600 font-semibold">1000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-red-200 via-red-300 to-red-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Palliative therapy</span>
                <span class="text-gray-600 font-semibold">3000</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-indigo-200 via-indigo-300 to-indigo-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Oncology General</span>
                <span class="text-gray-600 font-semibold">1500</span>
            </div>
            <div class="p-4 rounded-lg bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">Tele-Oncology Specialist</span>
                <span class="text-gray-600 font-semibold">4000</span>
            </div>
        </div>
        <button onclick="closeModal('talkDoctorModal')" class="mt-4 bg-gray-600 text-white px-4 py-2 rounded-lg">Close</button>
    </div>
</div>

<!-- Service Modal -->
<div id="serviceModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl w-full transform transition duration-500 hover:scale-105">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Available Services</h2>
        <input type="text" id="searchService" onkeyup="filterServices()" placeholder="Search services..." class="w-full p-2 mb-3 border border-gray-300 rounded" />

        <table class="w-full">
            <tbody id="servicesList">
                @foreach($services as $service)
                <tr class="service-item hover:bg-blue-50 cursor-pointer" onclick="selectService({{ $service->id }})">
                    <td class="py-2 text-gray-800 font-medium">{{ $service->name }}</td>
		            <td class="py-2 text-center text-gray-600">{{ number_format($service->cost, 2) }}KSH</td>
                    <td class="py-2 text-green-400 font-medium"> {{ $service->status }}</td>
                    <td class="py-2 text-gray-800 font-medium">{{ $service->department}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button onclick="closeModal('serviceModal')" class="bg-blue-600 text-white px-4 py-1 mt-4 rounded">Close</button>
    </div>
</div>

<!-- Appointments Modal -->
<div id="appointmentsModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full transform transition duration-500 hover:scale-105">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">All Appointments</h2>
        <div class="overflow-y-auto max-h-96">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Clinic</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Patient</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Date</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allAppointments as $appointment)
                    <tr>
                        <td class="border-t py-2 px-4">{{ $appointment->doctor->name ?? 'No doctor assigned' }}</td>
                        <td class="border-t py-2 px-4">{{ $appointment->patient->name ?? 'No patient assigned' }}</td>
                        <td class="border-t py-2 px-4">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                        <td class="border-t py-2 px-4">{{ ucfirst($appointment->payment_status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button onclick="closeModal('appointmentsModal')" class="mt-4 bg-gray-600 text-white px-4 py-2 rounded-lg">Close</button>
    </div>
</div>

<!-- Modal Scripts -->
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.getElementById(modalId).classList.add('flex');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('flex');
        document.getElementById(modalId).classList.add('hidden');
    }

    function filterServices() {
        const searchValue = document.getElementById('searchService').value.toLowerCase();
        document.querySelectorAll('.service-item').forEach(service => {
            service.style.display = service.querySelector('td').textContent.toLowerCase().includes(searchValue) ? '' : 'none';
        });
    }

    function selectService(serviceId) {
        window.location.href = `{{ route('services.book', '') }}/${serviceId}`;
    }
</script>
@endsection

