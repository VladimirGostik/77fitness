<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Potvrdenie skupinovej rezervácie</title>
</head>
<body>
    <h1>Potvrdenie skupinovej rezervácie</h1>
    
    <p>Dobrý deň,</p>
    
    <p>Vaša skupinová rezervácia bola úspešne potvrdená.</p>
    
    <p>Tu sú detaily:</p>
    
    <ul>
        <li><b>Tréner:</b> {{ $groupReservation->trainer->user->first_name }} {{ $groupReservation->trainer->user->last_name }}</li>
        <li><b>Dátum:</b> {{ $groupReservation->start_reservation->format('d.m.Y') }}</li>
        <li><b>Čas:</b> {{ $groupReservation->start_reservation->format('H:i') }} - {{ $groupReservation->end_reservation->format('H:i') }}</li>
        <li><b>Miesto:</b> 77fitness, Bajkalská 2i</li>
        <!-- Pridajte ďalšie detaily podľa potreby -->
    </ul>
    
    <p>Ďakujeme za Vašu rezerváciu!</p>

    <p>S pozdravom,<br>
        Tím 77fitness</p>
</body>
</html>
