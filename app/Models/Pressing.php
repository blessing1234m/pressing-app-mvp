<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Pressing extends Model
{
    use HasFactory;
    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'phone',
        'prices', // Cette ligne doit être présente
        'description',
        'is_approved',
        'approved_at',
        'approved_by'
    ];

    // If 'prices' is a JSON column in your database, add this:
    protected $casts = [
        'prices' => 'array',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public static function validatePrices($prices)
    {
        $validated = [];

        foreach ($prices as $item => $price) {
            $cleanItem = strtolower(trim($item));
            $cleanItem = preg_replace('/[^a-z0-9_]/', '_', $cleanItem);

            if ($cleanItem && is_numeric($price) && $price >= 0) {
                $validated[$cleanItem] = (int) $price;
            }
        }

        return $validated;

    }

    // Dans la classe Pressing
public function getPricesAttribute($value)
{
    if (is_array($value)) {
        return $value;
    }
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
    return [];
}
}
