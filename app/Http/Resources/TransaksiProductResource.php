<?php

namespace App\Http\Resources;

use App\Model\Product\Product;
use App\Model\Product\ProductFoto;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cekproduct = Product::find($this->product);
        $cekfoto = ProductFoto::where('products_id', $this->product)->first();
        return [
            'id' => $this->id,
            'product_id' => $this->product,
            'harga_product' => $this->harga_product,
            'foto_product' => url($cekfoto == null ? 'logo/account.jpg' : $cekfoto->foto),
            'nama_product' => $cekproduct == null ? 'Product Dihapus' : $cekproduct->name,
        ];
    }
}
