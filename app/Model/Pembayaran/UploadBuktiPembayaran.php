<?php

namespace App\Model\Pembayaran;

use Illuminate\Database\Eloquent\Model;

class UploadBuktiPembayaran extends Model
{
    //
    protected $fillable = [
        'nama_pemilik_rekening',
        'nomor_rekening',
        'invoice_id',
        'nama_bank',
        'foto',
        'status',
        'users_id'
    ];
}
