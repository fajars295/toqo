<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\NameId;
use App\Model\Product\Typespesifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeSpesifikasiController extends Controller
{
    //
    //

    private $model;
    public function __construct()
    {
        $this->model = new Typespesifikasi();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cre = $request->all();
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Type spesifikasi');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'type_spesifikasi_id' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->type_spesifikasi_id);
        $cre = $request->all();
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Type spesifikasi');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil(NameId::collection(collect($cek)));
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Type spesifikasi Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Type spesifikasi');
        }
        return ResponeHelper::badRequest('Gagal Delete Type spesifikasi');
    }
}
