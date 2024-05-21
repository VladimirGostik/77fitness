<!DOCTYPE html>
<html>
<head>
    <title>Participants List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
    <h1>Participants List</h1>
    <p><strong>Trainer:</strong> {{ $groupReservation->trainer->user->first_name }} {{ $groupReservation->trainer->user->last_name }}</p>
    <p><strong>Start Time:</strong> {{ date('H:i d.m.Y', strtotime($groupReservation->start_reservation)) }}</p>
    <p><strong>End Time:</strong> {{ date('H:i d.m.Y', strtotime($groupReservation->end_reservation)) }}</p>
    <p><strong>Room:</strong> {{ $groupReservation->room->name }}</p>
    <p><strong>Participants:</strong></p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Client ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupReservation->participants as $participant)
            <tr>
                <td>{{ $participant->user->first_name }} {{ $participant->user->last_name }}</td>
                <td>{{ $participant->client_id ? $participant->client_id : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
