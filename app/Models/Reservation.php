<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = 'reservations'; 
    protected $dates = ['start_reservation', 'end_reservation']; 

    protected $fillable = [
        'client_id', // ID klienta
        'trainer_id', // ID trénera
        'start_reservation', // Začiatok rezervácie
        'end_reservation', // Koniec rezervácie
        'reservation_price', // Cena rezervácie
        'transaction_id', // ID transakcie
    ];

    protected $casts = [
        'start_reservation' => 'datetime', // Konverzia na dátumový objekt
        'end_reservation' => 'datetime', // Konverzia na dátumový objekt
    ];

    public function client()  // Vzťah: Rezervácia patrí klientovi
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function trainer() // Vzťah: Rezervácia patrí trénerovi
    {
        return $this->belongsTo(Trainer::class, 'trainer_id'); 
    }
}

