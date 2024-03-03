<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = 'reservations'; // Assuming your table name for admins is 'admins'

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'trainer_id',
        'start_reservation',
        'end_reservation',
        'reservation_price',
    ];

    protected $casts = [
        'start_reservation' => 'datetime',
        'end_reservation' => 'datetime',
    ];
    /**
     * Get the client associated with the reservation.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the trainer associated with the reservation.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }
}
