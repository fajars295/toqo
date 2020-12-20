<?php

namespace App\Model\Pembayaran;

use Illuminate\Database\Eloquent\Model;

class MasterTransactionProduct extends Model
{
    //
    protected $fillable = [

        'invoice',
        'kurir',
        'harga_kurir',
        'master_transactions_id',
        'users_id',
        'status',
        'penjual_id'
    ];
}
