<?php

namespace App\Models;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'image_url'
    ];

    protected $sortable = [
        'id',
        'title',
        'price',
        'created_at',
        'updated_at'
    ];

    public function getQrCodeDataUri()
    {
        $builder = \Endroid\QrCode\Builder\Builder::create()
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
