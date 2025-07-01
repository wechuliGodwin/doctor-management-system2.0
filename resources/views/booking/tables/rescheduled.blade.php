<!-- resources/views/booking/tables/rescheduled.blade.php -->
<div class="table-responsive">
    <table class="table table-bordered table-sm table-hover mb-0">
        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th>S.No</th>
                <th>Prev App No.</th>
                <th>Pt Name</th>
                <th>Prev Spec.</th>
                <th>Prev Appt Date</th>
                <th>Prev Appt Time</th>
                <th>To</th>
                <th>Cur App No.</th>
                <th>Cur Spec.</th>
                <th>Cur Appt Date</th>
                <th>Cur Appt Time</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <!-- Populated by DataTables -->
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

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: #e6eef5;
    }
</style>