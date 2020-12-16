<?php

namespace App\Model\Content;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    //

    protected $fillable  = [
        'email',
        'token',
    ];
}
