<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervácia upravená</title>
</head>
<body>
    <h1>Rezervácia upravená</h1>
    
    <p>Dobrý deň,</p>
    
    <p>Vaša rezervácia bola úspešne upravená.</p>
    
    <p>Tu sú aktualizované detaily:</p>
    
    <ul>
        <li>Tréner: {{ $reservation->trainer->user->first_name }} {{ $reservation->trainer->user->last_name }}</li>
        <li>Dátum: {{ $reservation->start_reservation->format('d.m.Y') }}</li>
        <li>Čas: {{ $reservation->start_reservation->format('H:i') }} - {{ $reservation->end_reservation->format('H:i') }}</li>
        <li>Miesto: 77fitness, Bajkalská 2i</li>
        <li>Cena tréningu: {{ $reservation->reservation_price }} €</li>
    </ul>
    
    <p>Ďakujeme, že využívate naše služby!</p>
    
    <p>S pozdravom,<br>Tím 77fitness</p>
</body>
</html>
