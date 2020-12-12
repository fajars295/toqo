<?php

namespace App\Http\Controllers\Api\Content;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Benner as ResourcesBenner;
use App\Model\Content\Benner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;
use Illuminate\Support\Facades\File as FacadesFile;

class BennerController extends Controller
{
    //

    private $model;
    public function __construct()
    {
        $this->model = new Benner();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'file' => 'required|file',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $uploadimg = ResponeHelper::uploadImg($request->file, 'Benner');

        $cre = $request->all();
        $cre['file'] = $uploadimg;

        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Benner');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'banner_id' => 'required',
            // 'file' => 'required|string',
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->banner_id);
        if ($request->file) {
            FacadesFile::delete(public_path() . $cek->file);
            $uploadimg = ResponeHelper::uploadImg($request->file, 'Benner');
        } else {
            $uploadimg = $cek->file;
        }

        $cre = $request->all();
        $cre['file'] = $uploadimg;
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Benner');
    }

    public function List($type)
    {
        # code...
        $cek = $this->model->where('status', $type)->get();
        return ResponeHelper::GetDataBerhasil(ResourcesBenner::collection(collect($cek)));
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Benner Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            $hapus = FacadesFile::delete(public_path() . $data->foto);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Benner');
        }
        return ResponeHelper::badRequest('Gagal Delete Benner');
    }
}
