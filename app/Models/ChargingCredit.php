<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargingCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_transaction_id',
        'client_id',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'description',
        'created_at',
        'updated_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Optional: Define additional relationships (e.g., orders)
}
