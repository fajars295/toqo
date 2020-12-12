<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    //
    protected $fillable = [
        'users_id',
        'deskripsi',
        'rating',
        'products_id',
    ];
}
