<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'trainer_id',
        'title',
        'content',
        'date_time',
        // Add any additional fields you want to be mass assignable for articles
    ];

    /**
     * Get the admin associated with the article.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Get the trainer associated with the article.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }
}
