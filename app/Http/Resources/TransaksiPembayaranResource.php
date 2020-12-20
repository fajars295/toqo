<?php

namespace App\Http\Resources;

use App\Model\Pembayaran\MasterTransaction;
use App\Model\Pembayaran\MasterTransactionProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiPembayaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice' => $this->invoice,
            'metode_pembayaran' => $this->metode_pembayaran,
            'total_pembayaran' => $this->total_transaksi,
            'product' =>  TransaksiPembayaranProductResource::collection(collect(MasterTransactionProduct::where('master_transactions_id', $this->id)->get())),
        ];
    }
}
