<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class GroupReservation extends Model
{
    use HasFactory;
    protected $table = "group_reservations";
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
    

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function participants()
    {
        return $this->hasMany(GroupParticipant::class, 'group_id');
    }
}
