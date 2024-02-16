<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'reservation_id',
        'amount',
        'date_time',
        // Add any additional fields you want to be mass assignable for payments
    ];

    /**
     * Get the client associated with the payment.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the reservation associated with the payment.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
