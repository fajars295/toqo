<?php

namespace App\Model\Pembayaran;

use Illuminate\Database\Eloquent\Model;

class MasterTransaction extends Model
{
    //
    protected $fillable = [
        'invoice',
        'users_id',
        'total_transaksi',
        'alamat_id',
        'metode_pembayaran',
        'bank_id',
        'status'
    ];
}
