<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupParticipant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'group_id', 
        'client_id',
        'name',
    ];


    protected $table = 'group_participants';

    public function group()
    {
        return $this->belongsTo(GroupReservation::class, 'group_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'user_id');
    }
    
}
