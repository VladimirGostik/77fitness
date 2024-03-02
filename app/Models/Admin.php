<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins'; // Assuming your table name for admins is 'admins'

    protected $primaryKey = 'admin_id'; // Assuming your primary key for admins is 'admin_id'

    protected $fillable = [
        'user_id', // Assuming you have a foreign key 'user_id' in the 'admins' table
        // Add other admin-specific fields here
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
