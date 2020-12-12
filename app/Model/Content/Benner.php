<?php

namespace App\Model\Content;

use Illuminate\Database\Eloquent\Model;

class Benner extends Model
{
    //
    protected $fillable = [
        'name',
        'file',
        'status',
    ];
}
