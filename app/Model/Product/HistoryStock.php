<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    //
    protected $fillable = [
        'users_id',
        'stock',
        'type',
        'keterangan',
        'products_id'
    ];
    protected $hidden = [
        'products_id', 'created_at', 'updated_at',
    ];
}
