<?php

namespace App\Model\Pembayaran;

use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    //
    protected $fillable = [
        'product',
        'jumlah',
        'harga_product',
        'status',
        'users_id',
        'nomor_invoice',
        'master_transactions_id',
        'master_transactions_products_id'
    ];
}
