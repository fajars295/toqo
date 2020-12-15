<?php

namespace App\Model\Content;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $fillable = [
        'foto',
        'deskripsi',
        'lihat',
        'users_id'
    ];
}
