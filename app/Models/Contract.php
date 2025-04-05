<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'description',
        'file_path',
        'status',
        'feedback',
        'reviewed_by',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime'
    ];

    protected $sortable = [
        'created_at',
        'status',
        'reviewed_at'
    ];

    public const STATUSES = [
        'pending' => 'pending',
        'approved' => 'approved',
        'rejected' => 'rejected'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeSortable(EloquentBuilder $query, $request): EloquentBuilder
    {
        $sortBy = $request->input('sort_by', 'created_at');
        $direction = $request->input('direction', 'desc');

        if (in_array($sortBy, $this->sortable)) {
            $query->orderBy($sortBy, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUSES['pending'];
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUSES['approved'];
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUSES['rejected'];
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUSES['approved'] => 'green',
            self::STATUSES['rejected'] => 'red',
            default => 'yellow'
        };
    }
}
