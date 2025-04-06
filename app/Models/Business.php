<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'name',
        'contract_status',
        'contract_file',
        'contract_updated_at',
        'contract_reviewed_at',
        'contract_reviewed_by',
        'contract_rejection_reason'
    ];

    protected $casts = [
        'contract_updated_at' => 'datetime',
        'contract_reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contract_reviewed_by');
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(BusinessBlock::class)->orderBy('order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BusinessReview::class);
    }

    public function scopeSort($query, $sortBy = 'created_at', $direction = 'desc')
    {
        return $query->orderBy($sortBy, $direction);
    }
}
