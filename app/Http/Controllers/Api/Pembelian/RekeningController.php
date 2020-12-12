<?php

namespace App\Http\Controllers\Api\Pembelian;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\RekeningResource;
use App\Model\Pembelian\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class RekeningController extends Controller
{
    //

    private $model;
    public function __construct()
    {
        $this->model = new Rekening();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'nomor_rekening' => 'required|string',
            'cabang' => 'required|string',
            'nama_pemilik' => 'required|string',
            'nama_bank' => 'required|string',
            // 'logo' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $uploadimg = null;
        if ($request->logo) {

            $uploadimg = ResponeHelper::uploadImg($request->logo, 'Rekening');
        }

        $cre = $request->all();
        $cre['logo'] = $uploadimg;
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Rekening');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'nomor_rekening' => 'required|string',
            'cabang' => 'required|string',
            'nama_pemilik' => 'required|string',
            'rekenings_id' => 'required|numeric',
            'nama_bank' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->rekenings_id);
        if ($request->logo) {
            if ($cek->logo != null) {
                File::delete(public_path() . $cek->logo);
            }
            $uploadimg = ResponeHelper::uploadImg($request->logo, 'Rekening');
        } else {
            $uploadimg = $cek->logo;
        }

        $cre = $request->all();
        $cre['file'] = $uploadimg;
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Rekening');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil(RekeningResource::collection(collect($cek)));
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Rekening Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            $hapus = File::delete(public_path() . $data->foto);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Rekening');
        }
        return ResponeHelper::badRequest('Gagal Delete Rekening');
    }
}
