<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertisementTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'advertisement_id',
        'price',
        'type',
        'status',
        'returned',
        'return_date',
        'return_photo',
        'calculated_wear',
        'rental_days'
    ];

    protected $casts = [
        'return_date' => 'datetime',
        'returned' => 'boolean',
        'rental_days' => 'integer'
    ];

    const STATUS_SOLD = 'sold';
    const STATUS_RETURNED = 'returned';

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsReturned(array $data = []): bool
    {
        return $this->update([
            'returned' => true,
            'status' => self::STATUS_RETURNED,
            'return_date' => $data['return_date'] ?? now(),
            'return_photo' => $data['return_photo'] ?? null,
            'calculated_wear' => $data['calculated_wear'] ?? null
        ]);
    }
}
