<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'specialization',
        'description',
        'experience',
        'session_price',
    ];

    /**
     * Get the user that owns the trainer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
