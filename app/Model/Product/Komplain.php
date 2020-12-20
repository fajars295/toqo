<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    //
    protected $fillable = [
        'pembelian_id',
        'penjual_id',
        'masalah_product',
        'type_product',
        'foto',
        'status',
        'users_id'
    ];
}
