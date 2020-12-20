<?php

namespace App\Http\Controllers\Api\Pembelian;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UploadBuktiResouce;
use App\Model\Pembayaran\MasterTransaction;
use App\Model\Pembayaran\UploadBuktiPembayaran;
use App\Model\User\Loyalty;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UploadBuktiPembeyaranController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new UploadBuktiPembayaran();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'nama_pemilik_rekening'  => 'required|string|',
            'nomor_rekening'  => 'required|string|',
            'invoice_id'  => 'required|string|',
            'nama_bank'  => 'required|string|',
            'foto'  => 'required|file|',
        ]);
        if ($validator->fails()) {
            return  ResponeHelper::ResponValidator($validator);
        }

        $cekdata = MasterTransaction::where('invoice', $request->invoice_id)->first();
        if (!$cekdata) {
            return  ResponeHelper::badRequest('Transaksi Product Tidak di temukan');
        }

        $cekinvoice = $this->model->where('invoice_id', $request->invoice_id)->where('status', 1)->first();
        if ($cekinvoice) {
            return  ResponeHelper::badRequest('Anda Sudah upload product tunggu approval');
        }

        $uploadimg = ResponeHelper::uploadImg($request->foto, 'BuktiPembayaran');

        $cre = $request->all();
        $cre['foto'] = $uploadimg;
        $cre['status'] = 1;
        $cre['users_id'] = Auth::user()->id;
        $this->model->create($cre);

        ResponeHelper::fcmtoken(Auth::user()->email, 'Berhasil Upload Bukti Pmebayaran', 'Tunggu Aprovel Dari Admin', null);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Upload Bukti Pembayaran');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil(UploadBuktiResouce::collection(collect($cek)));
    }
    public function ListByInvoiceId($id)
    {
        # code...
        $cek = $this->model->where('invoice_id', $id)->get();
        return ResponeHelper::GetDataBerhasil(UploadBuktiResouce::collection(collect($cek)));
    }

    public function UpdateStatus(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'id_upload' => 'required',
            'status' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return  ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->id_upload);
        if (!$cek) {
            return  ResponeHelper::badRequest('transaksi tidak di temukan');
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
            //code...
            $update = $cek->update([
                'status' => $request->status,
            ]);

            ResponeHelper::fcmtoken(User::find($cek->users_id)->email, 'Ayo Cek Status Pembayaran Anda', 'Status Pembayaran Anda di ' . $status, null);

            if ($request->status == 2) {
                $cekdata = MasterTransaction::where('invoice', $cek->invoice_id)->first();
                if (!$cekdata) {
                    return  ResponeHelper::badRequest('Transaksi Product Tidak di temukan');
                }

                $cekdata->update([
                    'status' => 1
                ]);


                Loyalty::create([
                    'users_id' => $cekdata->users_id,
                    'koin' => round($cekdata->total_transaksi / 1000),
                    'keterangan' => 'Casback Pembelian',
                    'status' => 1,
                ]);
            }
            DB::commit();
            return  ResponeHelper::CreteorUpdateBerhasil(null, 'berhasil Update status');
        } catch (\Exception $th) {
            //throw $th;
            return ResponeHelper::badRequest('services error err :' . $th);
        }
    }
}
