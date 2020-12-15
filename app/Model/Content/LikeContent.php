<?php

namespace App\Model\Content;

use Illuminate\Database\Eloquent\Model;

class LikeContent extends Model
{
    //
    protected $fillable = [
        'users_id',
        'contents_id',
    ];
}
