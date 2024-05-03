<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerGalleryPhoto extends Model
{
    use HasFactory;
    
    protected $fillable = ['trainer_id', 'filename', 'path'];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
