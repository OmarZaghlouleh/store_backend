<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $casts = ['category_id' => 'integer'];
    protected $fillable = [
        'name',
        'price',
        'img',
        'description',
        'category_id'

    ];
}