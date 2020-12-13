<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'name',
        'deskripsi',
        'harga',
        'diskon',
        'categories_id',
        'type_categories_id',
        'total_pembelian',
        'users_id',
        'berat_pengiriman',
        'stock',
        'like',
        'type',
        'status_ongkir',
        'casback',
    ];
}
