<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    //
    protected $fillable = [
        'users_id',
        'koin',
        'keterangan',
        'status',
    ];
}
