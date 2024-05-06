<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $table = "trainers";
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'specialization',
        'description',
        'experience',
        'session_price',
        'profile_photo',
    ];

    /**
     * Get the user that owns the trainer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the key for the model.
     *
     * @return string
     */
    public function getKey()
    {
        return 'user_id'; // Set the primary key attribute name
    }

    public function profilePhotos()
    {
        return $this->hasMany(TrainerGalleryPhoto::class, 'trainer_id');
    }

    /**
     * Get the primary key type.
     *
     * @return string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
