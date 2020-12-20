<?php

namespace App\Http\Resources;

use App\Model\Product\ProductFoto;
use Illuminate\Http\Resources\Json\JsonResource;

class transaksiresouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->diskon == 0) {
            $harga = $this->harga;
        } else {
            $bagi = $this->harga * $this->diskon / 100;
            $harga = $this->harga - $bagi;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'deskripsi' => $this->deskripsi,
            'harga' => $harga,
            'harga_sebelum' => $this->harga,
            'diskon' => $this->diskon . '%',
            'stock' => $this->stock,
            'berat_pengiriman' => $this->berat_pengiriman,
            'gratis_ongkir' => $this->status_ongkir == 1 ? true : false,
            'casback' => $this->casback,
            'type_pengiriman' => $this->type,
            'jumlah_pembelian' => $this->jumlah_pembelian,
            'harga_total' => intval($this->jumlah_pembelian) * $harga,
            'foto' => url(ProductFoto::where('products_id', $this->id)->first()->foto),
        ];
    }
}
