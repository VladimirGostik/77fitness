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
     * Atribúty, ktoré sú hromadne priraditeľné.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', // Pridajte 'user_id' sem
        'specialization',
        'description',
        'experience',
        'session_price',
        'profile_photo',
    ];

    /**
     * Získanie používateľa, ktorý vlastní trénera.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Získanie kľúča pre model.
     *
     * @return string
     */
    public function getKey()
    {
        return 'user_id'; // Nastavenie názvu primárneho kľúča
    }

    /**
     * Získanie profilových fotografií trénera.
     */
    public function profilePhotos()
    {
        return $this->hasMany(TrainerGalleryPhoto::class, 'trainer_id');
    }

    /**
     * Typ primárneho kľúča.
     *
     * @return string
     */
    protected $keyType = 'int';

    /**
     * Indikuje, či sú ID auto-incrementné.
     *
     * @var bool
     */
    public $incrementing = false;
}
