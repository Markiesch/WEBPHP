<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory,HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(AdvertisementReview::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(AdvertisementFavorite::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function advertisementTransactions(): HasMany
    {
        return $this->hasMany(AdvertisementTransaction::class);
    }
}
