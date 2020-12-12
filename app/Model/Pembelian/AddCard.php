<?php

namespace App\Model\Pembelian;

use Illuminate\Database\Eloquent\Model;

class AddCard extends Model
{
    //
    protected $fillable = [
        'products_id',
        'users_id',
    ];
}
