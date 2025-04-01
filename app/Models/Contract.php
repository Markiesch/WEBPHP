<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'description',
        'file_path',
    ];

    protected $sortable = [
        'created_at',
    ];

    public function scopeSortable(EloquentBuilder $query, $request)
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
}
