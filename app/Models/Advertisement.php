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

class Advertisement extends Model
{
    use HasFactory;

    const TYPE_SALE = 'sale';
    const TYPE_RENTAL = 'rental';
    const MAX_SALE_ADS = 4;

    const MAX_ADS_PER_BUSINESS = 8;

    const MAX_RENTAL_ADS = 4;

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
        'expiry_date'
    ];

    protected $sortable = [
        'id',
        'title',
        'price',
        'wear_percentage',
        'wear_per_day',
        'type',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'expiry_date' => 'date',
        'price' => 'decimal:2',
        'wear_percentage' => 'integer',
        'wear_per_day' => 'decimal:2'
    ];

    public static function getTypes(): array
    {
        return [
            self::TYPE_SALE => 'For Sale',
            self::TYPE_RENTAL => 'For Rent'
        ];
    }

    public function isSale(): bool
    {
        return $this->type === self::TYPE_SALE;
    }

    public function isRental(): bool
    {
        return $this->type === self::TYPE_RENTAL;
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

    public function scopeSortable(EloquentBuilder $query, $request)
    {
        $sortBy = $request->input('sort_by');
        $direction = $request->input('direction', 'desc');

        if ($sortBy && in_array($sortBy, $this->sortable)) {
            $query->orderBy($sortBy, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
}
