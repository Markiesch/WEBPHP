<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertisementReview extends Model
{
    protected $fillable = [
        'user_id',
        'advertisement_id',
        'rating',
        'comment',
    ];

    /**
     * Get the user who wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the advertisement being reviewed
     */
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }
}
