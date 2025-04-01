<?php

namespace App\Models;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'image_url',
        'rental_start_date',
        'rental_end_date',
        'expiry_date'
    ];

    protected $sortable = [
        'id',
        'title',
        'price',
        'created_at',
        'updated_at'
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(AdvertisementReview::class);
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

