<?php

namespace App\Model\Info;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    //
    protected $fillable = [
        'name',
        'keterangan',
        'category_infos_id'
    ];
    protected $hidden = [
        'category_infos_id', 'created_at', 'updated_at',
    ];
}
