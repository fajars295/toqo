<?php

namespace App\Model\Content;

use Illuminate\Database\Eloquent\Model;

class CommentContent extends Model
{
    //
    protected $fillable = [
        'deskripsi',
        'users_id',
        'contents_id',
    ];
}
