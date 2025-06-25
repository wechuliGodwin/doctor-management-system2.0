<div class="container mx-auto mt-5">
    <div class="flex justify-between">
        <!-- Calendar Column -->
        <div class="bg-white shadow-md rounded-lg p-4 w-8/12 mb-5 mx-2">
            @livewire('calendar')
        </div>

        <!-- Upcoming Appointments Column -->
        <div class="bg-white shadow-md rounded-lg p-4 w-4/12 mb-5 mx-2">
            <div class="flex items-center mb-4">
                <i class="fas fa-clock text-green-500 text-3xl mr-2"></i>
                <h5 class="text-xl font-semibold text-black-700">Upcoming Appointments</h5>
            </div>
            <ul class="list-circle list-inside text-gray-700" style="list-style-type: circle">
                @livewire('upcoming-appointments')
            </ul>
        </div>
    </div>
</div>
