<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Edited</title>
</head>
<body>
    <h1>Reservation Edited</h1>
    
    <p>Hello,</p>
    
    <p>Your reservation has been edited successfully.</p>
    
    <p>Here are the updated details:</p>
    
    <ul>
        <li>Trainer: {{ $reservation->trainer->user->first_name }} {{ $reservation->trainer->user->last_name }}</li>
        <li>Date: {{ $reservation->start_reservation->format('d.m.Y') }}</li>
        <li>Time: {{ $reservation->start_reservation->format('H:i') }} - {{ $reservation->end_reservation->format('H:i') }}</li>
        <li>Location: 77fitness, Bajkalská 2i</li>
        <li>Training Price: {{ $reservation->reservation_price }} €</li>
    </ul>
    
    <p>Thank you for using our service!</p>
    
    <p>Best regards,<br>77fitness Team</p>
</body>
</html>
