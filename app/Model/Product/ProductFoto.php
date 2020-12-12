<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductFoto extends Model
{
    //
    protected $fillable = [
        'products_id',
        'foto',
        'deskripsi',
    ];
}
