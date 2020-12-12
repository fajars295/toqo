<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductFavorit extends Model
{
    //
    protected $table = 'favorit_product';
    protected $fillable = ['products_id', 'users_id'];
}
