<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'pressing_id',
        'status',
        'total_amount',
        'pickup_date',
        'confirmed_pickup_date',
        'client_address',
        'special_instructions'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function pressing(): BelongsTo
    {
        return $this->belongsTo(Pressing::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Méthode pour obtenir le statut en français
    // public function getStatusFrAttribute()
    // {
    //     $statuses = [
    //         'pending' => 'En attente',
    //         'confirmed' => 'Confirmée',
    //         'in_progress' => 'En cours',
    //         'completed' => 'Terminée',
    //         'delivered' => 'Livrée'
    //     ];

    //     return $statuses[$this->status] ?? $this->status;
    // }
}
