<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupParticipant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'group_id', // Add this line
        'client_id',
        'name',
    ];


    protected $table = 'group_participants';

    public function group()
    {
        return $this->belongsTo(GroupReservation::class, 'group_id');
    }
}