<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Model\Product\Komplain;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class KompalianController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new Komplain();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'pembelian_id' => 'required|string',
            'penjual_id' => 'required|string',
            'masalah_product' => 'required|string',
            'type_product' => 'required|string',
            'foto' => 'required|file',
            // 'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekcomplain = $this->model->where('pembelian_id', $request->pembelian_id)->where('penjual_id', $request->penjual_id)->first();
        if ($cekcomplain) {
            return  ResponeHelper::badRequest('Anda sudah complain Untuk Product Ini');
        }

        $upload = ResponeHelper::uploadImg($request->foto, 'Komplain');
        $cre = $request->all();
        $cre['foto'] = $upload;
        $cre['status'] = 0;
        $cre['users_id'] = Auth::user()->id;
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Komplain');
    }

    public function UpdatestatusComplain(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'id_complain' => 'required',
            'status' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return  ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->id_complain);
        if (!$cek) {
            return  ResponeHelper::badRequest('Complain tidak di temukan');
        }

        if ($request->status == 0) {
            $status = 'Tolak';
        } elseif ($request->status == 1) {
            # code...
            $status = 'Pending';
        } elseif ($request->status == 2) {
            $status = 'Berhasil';
        } else {
            return ResponeHelper::Forbidden('status tidak di temukan');
        }

        DB::beginTransaction();
        try {
            $update = $cek->update([
                'status' => $request->status,
            ]);
            ResponeHelper::fcmtoken(User::find($cek->users_id)->email, 'Ayo Cek Status Complain', 'Status Compalian Anda di ' . $status, null);
            DB::commit();
            return  ResponeHelper::CreteorUpdateBerhasil(null, 'berhasil Update status');
        } catch (\Exception $th) {
            //throw $th;
            return ResponeHelper::badRequest('services error err :' . $th);
        }
    }


    public function ListComplain()
    {
        # code...
        $cek = $this->model->where('users_id', Auth::user()->id)->get();
        return ResponeHelper::GetDataBerhasil($cek);
    }
}
