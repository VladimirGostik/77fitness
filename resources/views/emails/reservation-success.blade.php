<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Potvrdenie Rezervácie</title>
</head>
<body>
    <h1>Potvrdenie Rezervácie</h1>

    <p>Vážený/á {{ $reservation->client->first_name }},</p>

    <p>Týmto potvrdzujeme Vašu rezerváciu u nás.</p>

    <p><b>Detaily rezervácie</b></p>
    <ul>
        <li><b>Tréner:</b> {{ $reservation->trainer->user->first_name }} {{ $reservation->trainer->user->last_name }}</li>
        <li><b>Dátum:</b> {{ $reservation->start_reservation->format('d.m.Y') }}</li>
        <li><b>Čas:</b> {{ $reservation->start_reservation->format('H:i') }} - {{ $reservation->end_reservation->format('H:i') }}</li>
        <li><b>Miesto:</b> 77fitness, Bajkalská 2i</li>
        <li><b>Cena tréningu:</b> {{ $reservation->reservation_price }} €</li>
    </ul>

    <p>Ďakujeme za Vašu rezerváciu!</p>

    <p>S pozdravom,<br>
        Tím 77fitness</p>
</body>
</html>
