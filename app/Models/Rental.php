<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
use HasFactory;

protected $fillable = [
'product_name',
'start_date',
'end_date',
];
}
