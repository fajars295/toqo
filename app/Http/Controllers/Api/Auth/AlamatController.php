<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\alamatIndonesia;
use App\Http\Resources\AlamatUserResource;
use App\Model\User\AlamatUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new AlamatUser();
    }
    public function GetProvinsi()
    {
        $data = DB::table('provinsi')->select('id', 'name_provinsi as name')->get();

        if ($data) {

            return ResponeHelper::GetDataBerhasil(alamatIndonesia::collection($data));
        }
    }
    public function GetKabupaten($id)
    {
        $data = DB::table('kota_kabupaten')->select('id', 'nama_kota_kabupaten as name')->where('provinsi_id', $id)->get();
        if ($data) {
            return ResponeHelper::GetDataBerhasil($data);
        }
    }
    public function GetKecamatan($id)
    {
        $data = DB::table('kecamatan')->select('id', 'nama_kecamatan as name')->where('kota_kabupaten_id', $id)->get();
        if ($data) {
            return ResponeHelper::GetDataBerhasil($data);
        }
    }
    public function GetKelurahan($id)
    {
        $data = DB::table('kelurahan')->select('id', 'nama_kelurahan as name')->where('Kecamatan_id', $id)->get();
        if ($data) {
            return ResponeHelper::GetDataBerhasil($data);
        }
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'judul_alamat' => 'required|string',
            'nama_penerima' => 'required|string',
            'alamat' => 'required|string',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'kode_pos' => 'required|numeric',
            'patokan' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cre = $request->all();
        $cre['users_id'] = Auth::user()->id;
        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Alamat');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'judul_alamat' => 'required|string',
            'nama_penerima' => 'required|string',
            'alamat' => 'required|string',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'kode_pos' => 'required|numeric',
            'patokan' => 'required|string',
            'alamat_id' => 'required'
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->alamat_id);
        if (!$cek) {
            return ResponeHelper::badRequest('id tidak di temukan');
        }

        $cre = $request->all();
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Alamat');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil(AlamatUserResource::collection(collect($cek)));
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
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Alamat');
        }
        return ResponeHelper::badRequest('Gagal Delete Alamat');
    }

    public function detail($id)
    {
        # code...
        $cek = $this->model->find($id);
        return ResponeHelper::GetDataBerhasil(new AlamatUserResource($cek));
    }
}
