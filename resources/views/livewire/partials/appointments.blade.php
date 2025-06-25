<div class="container">
    <div class="flex justify-content-around flex-grow-1 text-center" style="padding:10px; justify-content:space-evenly;">
        <!-- Total Patients Container -->
        <div class="card shadow-sm p-9 w-3/12 mb-5 bg-white rounded flex-grow-1 mx-2" style="color: ">
            <div class="card-body flex-wrap">
                <i class="fas fa-users fa-2x mb-3 text-primary"></i>
                <h5 class="card-title">Total Patients</h5>
                <h2>{{ rand(1000, 5000) }}</h2>
                <p class="card-text">Till Today</p>
            </div>
        </div>

        <!-- Today's Patients Container -->
        <div class="card shadow-sm p-3 w-3/12 mb-5 bg-white rounded flex-grow-1 mx-2"style="color: #084a0d">
            <div class="card-body">
                <i class="fas fa-user-plus fa-2x mb-3"></i>
                <h5 class="card-title text-primary">Today's Patients</h5>
                <h2 class="text-dark">{{ rand(10, 50) }}</h2>
                <p class="card-text text-muted">Patients</p>
            </div>
        </div>

        <!-- Today's Appointments Container -->
        <div class="card shadow-sm p-3 mb-5 w-3/12 bg-white rounded flex-grow-1 mx-2" style="color: #084a0d">
            <div class="card-body">
                <i class="fas fa-calendar-check fa-2x mb-3 text-warning"></i>
                <h5 class="card-title">Today's Appointments</h5>
                <h2>@livewire('todays-appointment')</h2>
                <p class="card-text">Appointments</p>
            </div>
        </div>
    </div>
</div>

