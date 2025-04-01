<?php

namespace App\Models;

use Endroid\QrCode\Builder\Builder as QrBuilder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'image_url',
    ];

    protected $sortable = ['price', 'created_at'];

    public function getQrCodeDataUri()
    {
        return QrBuilder::create()
            ->writer(new PngWriter())
            ->data(route('advertisements.show', $this->id))
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10)
            ->build()
            ->getDataUri();
    }

    public function scopeSortable(Builder $query, $request)
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
