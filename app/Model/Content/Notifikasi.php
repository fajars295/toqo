<?php

namespace App\Model\Content;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    //
    protected $fillable = [
        'keterangan',
        'type',
        'users_id',
        'tujuan_id',
        'status',
    ];
}
