<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Reservation Confirmation</title>
</head>
<body>
    <h1>Group Reservation Confirmation</h1>
    
    <p>Hello,</p>
    
    <p>Your group reservation has been confirmed successfully.</p>
    
    <p>Here are the details:</p>
    
    <ul>
        <li><b>Tréner:</b> {{ $groupReservation->trainer->user->first_name }} {{ $groupReservation->trainer->user->last_name }}</li>
        <li><b>Dátum:</b> {{ $groupReservation->start_reservation->format('d.m.Y') }}</li>
        <li><b>Čas:</b> {{ $groupReservation->start_reservation->format('H:i') }} - {{ $groupReservation->end_reservation->format('H:i') }}</li>
        <li><b>Miesto:</b> 77fitness, Bajkalská 2i</li>
        <!-- Add more details as needed -->
    </ul>
    
    <p>Ďakujeme za Vašu rezerváciu!</p>

    <p>S pozdravom,<br>
        Tím 77fitness</p>
</body>
</html>
