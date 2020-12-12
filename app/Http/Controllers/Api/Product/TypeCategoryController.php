<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\NameId;
use App\Model\Product\TypeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeCategoryController extends Controller
{
    //

    private $model;
    public function __construct()
    {
        $this->model = new TypeCategory();
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
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Type Category');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'type_category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->type_category_id);
        $cre = $request->all();
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Type Category');
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
            return ResponeHelper::badRequest('Id Type Category Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Type Category');
        }
        return ResponeHelper::badRequest('Gagal Delete Type Category');
    }
}
