<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            margin: 0.5in;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 18pt;
            margin-bottom: 0.2cm;
        }

        h2 {
            font-size: 12pt;
            margin: 0.5cm 0;
            color: #0055b3;
        }

        .generated-at {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-bottom: 0.5cm;
        }

        ul {
            list-style-type: none;
            padding-left: 15px;
            margin: 0.5cm 0;
        }

        ul li {
            margin-bottom: 4pt;
            font-size: 9pt;
        }

        ul li strong {
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5cm;
            font-size: 8pt;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #666;
            margin-top: 0.5cm;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <div class="generated-at">Generated on {{ $generated_at }}</div>

    <h2>Applied Filters</h2>
    <ul>
        @foreach ($filters as $key => $value)
        <li><strong>{{ $key }}:</strong> {{ htmlspecialchars($value) }}</li>
        @endforeach
    </ul>

    <h2>Summary Statistics</h2>
    <ul>
        <li><strong>Total Appointments:</strong> {{ $totals['total_appointments'] }}</li>
        <li><strong>External Appointments:</strong> {{ $totals['pending_external_approvals'] + $totals['external_approved'] }}</li>
        <li><strong>Internal Appointments:</strong> {{ $totals['new_count'] + $totals['review_count'] + $totals['postop_count'] }}</li>
        <li><strong>Missed Appointments:</strong> {{ $totals['missed'] }}</li>
    </ul>

    <h2>Appointment Details ({{ count($appointments) }} records)</h2>
    @if (count($appointments) == 0)
    <p class="no-data">No appointments found for the selected filters.</p>
    @else
    <?php $perPage = 100;
    $page = 1; ?>
    @foreach ($appointments->chunk($perPage) as $chunk)
    <table>
        <thead>
            <tr>
                @foreach ($columns as $col => $header)
                <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($chunk as $appointment)
            <tr>
                @foreach ($columns as $col => $header)
                <td>
                    @if (in_array($col, ['appointment_date', 'previous_date', 'current_date']))
                    {{ $appointment->$col ? \Carbon\Carbon::parse($appointment->$col)->format('Y-m-d') : '-' }}
                    @else
                    {{ htmlspecialchars($appointment->$col ?? '-') }}
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @if (!$loop->last)
    <div class="page-break"></div>
    @endif
    <?php $page++; ?>
    @endforeach
    @endif
</body>

</html>