<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessBlock extends Model
{
    protected $fillable = [
        'business_id',
        'type',
        'content',
        'order',
        'active'
    ];

    protected $casts = [
        'content' => 'array',
        'active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
