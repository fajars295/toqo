<?php

namespace App\Model\Pembelian;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    //
    protected $fillable = [
        'nomor_rekening',
        'cabang',
        'nama_pemilik',
        'logo',
        'nama_bank',
    ];
}
