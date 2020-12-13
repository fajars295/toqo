<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Model\Product\Category;
use App\Model\Product\TypeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class CategoryController extends Controller
{
    //

    private $model;
    public function __construct()
    {
        $this->model = new Category();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'type_categories_id' => 'required|string',
            'name' => 'required|string',
            'logo' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cektype = TypeCategory::find($request->type_categories_id);
        if (!$cektype) {
            return ResponeHelper::badRequest('Id Type Category Tidak Ditemukan');
        }

        $upload = ResponeHelper::uploadImg($request->logo, 'Category');
        $cre = $request->all();
        $cre['logo'] = $upload;
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Category');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'category_id' => 'required',
            'type_categories_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->category_id);
        if ($request->logo) {
            File::delete(public_path() . $cek->logo);
            $uploadimg = ResponeHelper::uploadImg($request->logo, 'Category');
        } else {
            $uploadimg = $cek->logo;
        }

        $cre = $request->all();
        $cre['logo'] = $uploadimg;
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Category');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil(CategoryResource::collection(collect($cek)));
    }

    public function ListTypeCategory($id)
    {
        # code...
        $cek = $this->model->where('type_categories_id', $id)->get();
        return ResponeHelper::GetDataBerhasil(CategoryResource::collection(collect($cek)));
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Category Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            File::delete(public_path() . $data->logo);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Category');
        }
        return ResponeHelper::badRequest('Gagal Delete Category');
    }
}
