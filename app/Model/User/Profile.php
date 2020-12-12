<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    protected $fillable = [
        'users_id',
        'nomor_hp',
        'jenis_kelamin',
        'tanggal_lahir',
        'foto',
        'nama_toko',
    ];
}
