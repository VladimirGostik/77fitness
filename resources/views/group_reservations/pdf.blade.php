<!DOCTYPE html>
<html>
<head>
    <title>Participants</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Group Reservation Participants</h1>
    <p><strong>Trainer:</strong> {{ $groupReservation->trainer->user->first_name }} {{ $groupReservation->trainer->user->last_name }}</p>
    <p><strong>Start Time:</strong> {{ $groupReservation->start_reservation }}</p>
    <p><strong>End Time:</strong> {{ $groupReservation->end_reservation }}</p>
    <p><strong>Room:</strong> {{ $groupReservation->room->name }}</p>
    <p><strong>Participants:</strong></p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Paid by:</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupReservation->participants as $participant)
                <tr>
                    <td>{{ $participant->name }}</td>
                    @if($participant->client && $participant->client->user)
                        <td>{{ $participant->client->user->first_name }} {{ $participant->client->user->last_name }}</td>
                    @else
                        <td>Unknown</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
