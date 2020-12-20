<?php

namespace App\Http\Resources;

use App\Model\Pembayaran\TransactionModel;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TransaksiPembayaranProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cekpro = TransactionModel::where('master_transactions_products_id', $this->id)->get();
        $harga = 0;
        foreach ($cekpro as $key => $value) {
            # code...
            $harga = $value->harga_product + $harga;
        }

        $hasil = $harga + $this->harga_kurir;

        return [
            'id' => $this->id,
            'invoice' => $this->invoice,
            'kurir' => $this->kurir,
            'harga_kurir' => $this->harga_kurir,
            'harga_total' => $hasil,
            'penjual' => User::find($this->penjual_id)->name,
            'penjual_id' => $this->penjual_id,
            'product' => TransaksiProductResource::collection(collect($cekpro)),
        ];
    }
}
