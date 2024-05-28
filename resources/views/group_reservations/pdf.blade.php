<!DOCTYPE html>
<html>
<head>
    <title>Účastníci</title>
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
    <h1>Účastníci skupinovej rezervácie</h1>
    <p><strong>Tréner:</strong> {{ $groupReservation->trainer->user->first_name }} {{ $groupReservation->trainer->user->last_name }}</p>
    <p><strong>Začiatok:</strong> {{ $groupReservation->start_reservation }}</p>
    <p><strong>Koniec:</strong> {{ $groupReservation->end_reservation }}</p>
    <p><strong>Miestnosť:</strong> {{ $groupReservation->room->name }}</p>
    <p><strong>Účastníci:</strong></p>
    <table>
        <thead>
            <tr>
                <th>Meno</th>
                <th>Zaplatené kým:</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupReservation->participants as $participant)
                <tr>
                    <td>{{ $participant->name }}</td>
                    @if($participant->client && $participant->client->user)
                        <td>{{ $participant->client->user->first_name }} {{ $participant->client->user->last_name }}</td>
                    @else
                        <td>Neznáme</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
