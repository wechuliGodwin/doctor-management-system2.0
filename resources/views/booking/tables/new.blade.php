<div class="table-responsive">
    <table class="table table-bordered table-sm table-hover mb-0">
        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Tracing</th>
                <!-- <th>S.No</th> -->
                <th>Appointment No.</th>
                <th>Pt Name</th>
                <th>Pt No.</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Booking Type</th>
                <th>Tracing Status</th>
                <th>Appointment Status</th>
                <th>HMIS Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- DataTables will populate this -->
        </tbody>
    </table>
</div>

<style>
    .table-responsive {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        border: none;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }

    .table-sm th {
        background: #343a40;
        color: #ffffff;
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: #e6eef5;
    }

    .btn-sm {
        padding: 0.2rem 0.5rem;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #159ed5;
        border-color: #159ed5;
    }

    .btn-primary:hover {
        background-color: #159ed5;
        border-color: #159ed5;
    }

    .btn-warning {
        background-color: #d48806;
        border-color: #d48806;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #d48806;
        border-color: #d48806;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>