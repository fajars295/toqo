<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductSpesifikasi extends Model
{
    //
    protected $fillable = [
        'products_id',
        'type_spesifikasi',
        'deskripsi',
    ];

    protected $hidden = [
        'products_id', 'created_at', 'updated_at',
    ];
}
