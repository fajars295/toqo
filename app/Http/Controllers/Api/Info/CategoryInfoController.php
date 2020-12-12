<?php

namespace App\Http\Controllers\Api\Info;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryInfoResource;
use App\Model\Info\CategoryInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryInfoController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new CategoryInfo();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'logo' => 'required|file',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $uploadimg = ResponeHelper::uploadImg($request->logo, 'Info');

        $cre = $request->all();
        $cre['logo'] = $uploadimg;

        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Category Info');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'category_info_id' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->category_info_id);
        if ($request->logo) {
            File::delete(public_path() . $cek->logo);
            $uploadimg = ResponeHelper::uploadImg($request->logo, 'Info');
        } else {
            $uploadimg = $cek->logo;
        }

        $cre = $request->all();
        $cre['logo'] = $uploadimg;
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Category Info');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil(CategoryInfoResource::collection(collect($cek)));
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Category Info Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            $hapus = File::delete(public_path() . $data->logo);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Category Info');
        }
        return ResponeHelper::badRequest('Gagal Delete Category Info');
    }
}
