<?php

namespace App\Http\Controllers\Api\Content;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Model\Content\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FcmTokenController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new FcmToken();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|',
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $this->model->updateOrCreate(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $request->token
            ]
        );
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Updat Token');
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
            return ResponeHelper::badRequest('Id Token Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Token');
        }
        return ResponeHelper::badRequest('Gagal Delete Token');
    }
}
