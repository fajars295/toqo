<?php

namespace App\Http\Controllers\Api\Info;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Model\Info\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InfoController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new Info();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'keterangan' => 'required|string|',
            'category_infos_id' => 'required'
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cre = $request->all();
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Info');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'info_id' => 'required',
            'keterangan' => 'required|string|',
            'category_infos_id' => 'required'
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->info_id);

        $cre = $request->all();
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Info');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil($cek);
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Info Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Info');
        }
        return ResponeHelper::badRequest('Gagal Delete Info');
    }

    public function GetInfoCategory($id)
    {
        # code...
        $cek = $this->model->where('category_infos_id', $id)->get();
        return ResponeHelper::GetDataBerhasil($cek);
    }

    public function detail($id)
    {
        # code...
        $cek = $this->model->find($id);
        return ResponeHelper::GetDataBerhasil($cek);
    }
}
