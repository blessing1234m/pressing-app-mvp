<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'type' => 'string',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Relation avec les commandes passées par le client
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    /**
     * Relation avec le pressing pour les propriétaires
     */
    public function pressing(): HasOne
    {
        return $this->hasOne(Pressing::class, 'owner_id');
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est propriétaire
     */
    public function isOwner(): bool
    {
        return $this->type === 'owner';
    }

    /**
     * Vérifie si l'utilisateur est client
     */
    public function isClient(): bool
    {
        return $this->type === 'client';
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Si c'est un propriétaire, supprimer son pressing
            if ($user->pressing) {
                $user->pressing->delete();
            }

            // Si c'est un client, supprimer ses commandes
            if ($user->orders()->count() > 0) {
                $user->orders()->delete();
            }
        });
    }
}
