
<div>
    <ul>
        @foreach($appointments as $appointment)
            <li>
                <strong>Platform: </strong>{{ $appointment->platform }}<br>
                <strong>Description: </strong>{{ $appointment->description }}<br>
                <strong>Date: </strong>{{ $appointment->appointment_date }}
            </li>
            <br>
        @endforeach
    </ul>
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</div>


