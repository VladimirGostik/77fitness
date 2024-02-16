<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'trainer_id',
        'is_group_reservation',
        'reservation_date',
        'reservation_price',
        'price',
        // Add any additional fields you want to be mass assignable for reservations
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
