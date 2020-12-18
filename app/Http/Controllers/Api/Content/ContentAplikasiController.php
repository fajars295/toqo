<?php

namespace App\Http\Controllers\Api\Content;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Model\Content\ContentAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentAplikasiController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new ContentAplikasi();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'keterangan' => 'required',
            'type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cre = $request->all();
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Content Aplikasi');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'keterangan' => 'required',
            'type' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->id);
        $cre = $request->all();
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Content Aplikasi');
    }

    public function List($type)
    {
        # code...
        $cek = $this->model->where('type', $type)->get();
        return ResponeHelper::GetDataBerhasil($cek);
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete');
        }
        return ResponeHelper::badRequest('Gagal Delete');
    }
}
