<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class LikeProduct extends Model
{
    //
    protected $fillable = [
        'users_id',
        'products_id',
    ];
}
