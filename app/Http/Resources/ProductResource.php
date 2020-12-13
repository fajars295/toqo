<?php

namespace App\Http\Resources;

use App\Model\Product\Category;
use App\Model\Product\LikeProduct;
use App\Model\Product\ProductFoto;
use App\Model\Product\ProductSpesifikasi;
use App\Model\Product\TypeCategory;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
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
        $like = LikeProduct::where('users_id', Auth::user()->id)->where('products_id', $this->id);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'deskripsi' => $this->deskripsi,
            'harga' => $harga,
            'harga_sebelum' => $this->harga,
            'diskon' => $this->diskon . '%',
            'stock' => $this->stock,
            'categories_id' => Category::find($this->categories_id)->name,
            'type_categories_id' => TypeCategory::find($this->type_categories_id)->name,
            'total_pembelian' => $this->total_pembelian,
            'users' => User::find($this->users_id),
            'berat_pengiriman' => $this->berat_pengiriman,
            'gratis_ongkir' => $this->status_ongkir == 1 ? true : false,
            'casback' => $this->casback,
            'type_pengiriman' => $this->type,
            'foto' => ProductFotoResource::collection(collect(ProductFoto::where('products_id', $this->id)->get())),
            'spesifikasi' => ProductSpesifikasi::where('products_id', $this->id)->get(),
            'like' => $like->first() == null ? false : true,
            'jumlah_like' => $like->count(),
        ];
    }
}
