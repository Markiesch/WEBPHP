<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'advertisement_id',
        'start_date',
        'end_date',
        'status',
        'note'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }

    public function isUpcoming(): bool
    {
        return now()->isBefore($this->start_date);
    }

    public function isOverdue(): bool
    {
        return now()->isAfter($this->end_date);
    }
}
