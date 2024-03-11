<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class GroupReservation extends Model
{
    use HasFactory;
    protected $table = "groups";
    protected $dates = ['start_reservation', 'end_reservation'];


    protected $fillable = [
        'trainer_id',
        'start_reservation',
        'end_reservation',
        'max_participants',
        'room_id',
    ];

    protected $casts = [
        'start_reservation' => 'datetime',
        'end_reservation' => 'datetime',
    ];
    
    protected $attributes = [
        'is_reserved' => false,
    ];
    // Add any relationships or additional logic as needed
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }
}
