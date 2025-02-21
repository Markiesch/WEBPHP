<?php
namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use CrudTrait;
use HasFactory;

protected $fillable = ['title', 'description', 'price', 'rental_start_date', 'rental_end_date', 'expiry_date'];
}
