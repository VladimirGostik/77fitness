<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupReservation extends Model
{
    use HasFactory;
    protected $table = "groups";


    protected $fillable = [
        'trainer_id',
        'start_reservation',
        'end_reservation',
        'max_participants',
        'room_id',
    ];

    // Add any relationships or additional logic as needed
}
