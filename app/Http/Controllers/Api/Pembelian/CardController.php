<?php

namespace App\Http\Controllers\Api\Pembelian;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Model\Pembelian\AddCard;
use App\Model\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new AddCard();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'products_id' => 'required|numeric|',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekproduct = Product::find($request->products_id);
        if (!$cekproduct) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }
        $cekcard = $this->model->where('users_id', Auth::user()->id)->where('products_id', $request->products_id)->first();
        if ($cekcard) {
            return ResponeHelper::badRequest('Product Sudah Ada Di Card Anda');
        }

        $cre = $request->all();
        $cre['users_id'] = Auth::user()->id;
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Add To Card');
    }

    public function List()
    {
        # code...
        $cek = $this->model->where('users_id', Auth::user()->id)->get();
        return ResponeHelper::GetDataBerhasil(CardResource::collection(collect($cek)));
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Card Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Card');
        }
        return ResponeHelper::badRequest('Gagal Delete Card');
    }
}
