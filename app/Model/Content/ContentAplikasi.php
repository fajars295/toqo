<?php

namespace App\Model\Content;

use Illuminate\Database\Eloquent\Model;

class ContentAplikasi extends Model
{
    //
    protected $fillable = [
        'judul',
        'keterangan',
        'type',
    ];
}
