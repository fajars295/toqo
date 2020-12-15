<?php

namespace App\Model\Profile;

use Illuminate\Database\Eloquent\Model;

class TokoDetail extends Model
{
    //
    protected $fillable = [
        'logo',
        'foto_ktp',
        'foto_diri',
        'alamat_toko',
        'alamat_pemilik_toko',
        'nomor_toko',
        'users_id',
    ];
}
