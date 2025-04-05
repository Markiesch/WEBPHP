<?php

namespace App\Models;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Advertisement extends Model
{
    use HasFactory;

    const TYPE_SALE = 'sale';
    const TYPE_RENTAL = 'rental';
    const TYPE_AUCTION = 'auction';
    const MAX_SALE_ADS = 4;

    const MAX_AUCTION_ADS = 4;
    const MAX_ADS_PER_BUSINESS = 8;
    const MAX_RENTAL_ADS = 4;
    const DEFAULT_EXPIRY_DAYS = 20;
    const MIN_BID_INCREMENT = 1.00;

    protected $fillable = [
        'title',
        'description',
        'price',
        'wear_percentage',
        'wear_per_day',
        'image_url',
        'business_id',
        'type',
        'rental_start_date',
        'rental_end_date',
        'expiry_date',
        'starting_price',
        'current_bid',
        'auction_end_date'
    ];

    protected $sortable = [
        'id',
        'title',
        'price',
        'starting_price',
        'current_bid',
        'wear_percentage',
        'wear_per_day',
        'type',
        'created_at',
        'updated_at',
        'expiry_date',
        'auction_end_date'
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'expiry_date' => 'date',
        'auction_end_date' => 'date',
        'price' => 'decimal:2',
        'starting_price' => 'decimal:2',
        'current_bid' => 'decimal:2',
        'wear_percentage' => 'integer',
        'wear_per_day' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($advertisement) {
            if (!$advertisement->expiry_date) {
                $advertisement->expiry_date = now()->addDays(self::DEFAULT_EXPIRY_DAYS);
            }
            if ($advertisement->isAuction() && !$advertisement->current_bid) {
                $advertisement->current_bid = $advertisement->starting_price;
            }
        });
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_SALE => 'For Sale',
            self::TYPE_RENTAL => 'For Rent',
            self::TYPE_AUCTION => 'Auction'
        ];
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function isAuction(): bool
    {
        return $this->type === self::TYPE_AUCTION;
    }

    public function isSale(): bool
    {
        return $this->type === self::TYPE_SALE;
    }

    public function isRental(): bool
    {
        return $this->type === self::TYPE_RENTAL;
    }

    public function placeBid(float $amount): bool
    {
        if (!$this->isAuction() ||
            $this->isAuctionEnded() ||
            $amount <= $this->current_bid ||
            $amount < ($this->current_bid + self::MIN_BID_INCREMENT)) {
            return false;
        }

        $this->bids()->create([
            'user_id' => Auth::id(),
            'amount' => $amount
        ]);

        $this->update(['current_bid' => $amount]);
        return true;
    }

    public function isReturned(): bool
    {
        return $this->transactions()
            ->where('type', 'rental')
            ->where('status', AdvertisementTransaction::STATUS_RETURNED)
            ->exists();
    }

    public function rental_return()
    {
        return $this->hasOne(RentalReturn::class);
    }

    public function isAuctionEnded(): bool
    {
        return $this->auction_end_date && $this->auction_end_date->isPast();
    }

    public function highestBidder(): ?User
    {
        return $this->bids()
            ->orderByDesc('amount')
            ->first()
            ?->user;
    }

    public function isExpired(): bool
    {
        if ($this->isAuction()) {
            return $this->isAuctionEnded();
        }
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function daysUntilExpiry(): int
    {
        if (!$this->expiry_date) {
            return 0;
        }
        if ($this->isAuction()) {
            return max(0, now()->diffInDays($this->auction_end_date, false));
        }
        return max(0, now()->diffInDays($this->expiry_date, false));
    }

    public function extend(int $days = self::DEFAULT_EXPIRY_DAYS): void
    {
        if ($this->isAuction()) {
            $this->update([
                'auction_end_date' => $this->auction_end_date
                    ? $this->auction_end_date->addDays($days)
                    : now()->addDays($days)
            ]);
            return;
        }

        $this->update([
            'expiry_date' => $this->expiry_date
                ? $this->expiry_date->addDays($days)
                : now()->addDays($days)
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(AdvertisementReview::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(AdvertisementFavorite::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AdvertisementTransaction::class);
    }

    public function isPurchased(): bool
    {
        return $this->transactions()->exists();
    }

    public function scopeOfType(EloquentBuilder $query, string $type): EloquentBuilder
    {
        return $query->where('type', $type);
    }

    public function scopeActive(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where(function ($query) {
            $query->where(function ($q) {
                $q->where('type', '!=', self::TYPE_AUCTION)
                    ->where(function ($q) {
                        $q->whereNull('expiry_date')
                            ->orWhere('expiry_date', '>', now());
                    });
            })->orWhere(function ($q) {
                $q->where('type', self::TYPE_AUCTION)
                    ->where('auction_end_date', '>', now());
            });
        });
    }

    public function scopeExpired(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where(function ($query) {
            $query->where(function ($q) {
                $q->where('type', '!=', self::TYPE_AUCTION)
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now());
            })->orWhere(function ($q) {
                $q->where('type', self::TYPE_AUCTION)
                    ->where('auction_end_date', '<=', now());
            });
        });
    }

    public function scopeActiveAuctions(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('type', self::TYPE_AUCTION)
            ->where('auction_end_date', '>', now());
    }

    public function getQrCodeDataUri()
    {
        $builder = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data(route('advertisements.show', $this->id))
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10)
            ->validateResult(false);

        return $builder->build()->getDataUri();
    }

    public function scopeSortable(EloquentBuilder $query, array $params): EloquentBuilder
    {
        $sortBy = $params['sort_by'] ?? null;
        $direction = $params['direction'] ?? 'desc';

        if ($sortBy && in_array($sortBy, $this->sortable)) {
            $query->orderBy($sortBy, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function relatedAdvertisements()
    {
        return $this->belongsToMany(
            Advertisement::class,
            'advertisement_relations',
            'advertisement_id',
            'related_advertisement_id'
        );
    }
}
