<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class AlamatUser extends Model
{
    //
    protected $fillable = [
        'judul_alamat',
        'nama_penerima',
        'alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'patokan',
        'users_id',
        'nomor_hp',
    ];
}
