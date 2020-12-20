<?php

namespace App\Http\Controllers\Api\Pembelian;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TransaksiPembayaranProductResource;
use App\Http\Resources\TransaksiPembayaranResource;
use App\Http\Resources\TransaksiProductResource;
use App\Http\Resources\transaksiresouce;
use App\Model\Pembayaran\MasterTransaction;
use App\Model\Pembayaran\MasterTransactionProduct;
use App\Model\Pembayaran\TransactionModel;
use App\Model\Pembelian\AddCard;
use App\Model\Product\Product;
use App\Model\Product\ProductRating;
use App\Model\User\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class TransaksiController extends Controller
{
    //

    public function QueryTransaksi(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'product' => 'required|string',
            'jumlah' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $product =  explode(",", $request->product);
        $jumlah =  explode(",", $request->jumlah);

        $data = Product::whereIn('id', $product)->get()->groupBy('users_id')->map(function ($item) {
            return $item;
        });
        $arr = [];
        // dd($data);

        foreach ($data as $key => $value) {
            $has = [];
            $penjual = '';
            $id = '';
            $totalPesan = 0;
            foreach ($value as $l => $d) {
                # code...
                if ($d->diskon == 0) {
                    $harga = $d->harga;
                } else {
                    $bagi = $d->harga * $d->diskon / 100;
                    $harga = $d->harga - $bagi;
                }

                $jul = 0;
                foreach ($product as $e => $l) {
                    # code...
                    if ($d->id == $l) {
                        $jul = $jumlah[$e];
                    }
                }

                $penjual = $d->users_id;
                $pro = $d;
                $pro['jumlah_pembelian'] = $jul;

                $totalBarang = intval($jul) * $harga;
                $totalPesan = $totalBarang + $totalPesan;

                array_push($has, new transaksiresouce($d));
            }
            $profile = Profile::where('users_id', $penjual)->first();
            $d = [
                'penjual_id' => User::find($penjual)->id,
                'penjual' => User::find($penjual)->name,
                'foto' => $profile == null ? url('logo/account.jpg') : url($profile->foto),
                'total_harga' => $totalPesan,
                'product' => $has
            ];
            array_push($arr, $d);
        }
        return ResponeHelper::GetDataBerhasil($arr);
    }

    public function CommitTransaksi(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'product' => 'required|string',
            'jumlah' => 'required|string',
            'kurir' => 'required|string',
            'harga_kurir' => 'required',
            'harga_total_perbarang' => 'required',
            'alamat_id' => 'required',
            'metode_pembayaran' => 'required',
            'harga_total_keseluruhan' => 'required',
            'bank_id' => 'required'
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $rand =  ResponeHelper::GeneretInvoice(10);
        $invoice = 'INVC' . Auth::user()->id . date('Ymd') . $rand;

        $randtotal = ResponeHelper::GeneretInvoice(3);
        $ttl = intval($randtotal) + intval($request->harga_total_keseluruhan);

        DB::beginTransaction();
        try {
            //code...
            $pro = MasterTransaction::create([
                'invoice' => $invoice,
                'users_id' => Auth::user()->id,
                'total_transaksi' => $ttl,
                'metode_pembayaran' => $request->metode_pembayaran,
                'alamat_id' => $request->alamat_id,
                'bank_id' => $request->bank_id,
                'status' => 0
            ]);

            $product =  explode(",", $request->product);
            $jumlah =  explode(",", $request->jumlah);
            $kurir =  explode(",", $request->kurir);
            $harga_kurir =  explode(",", $request->harga_kurir);
            $harga_total_perbarang =  explode(",", $request->harga_total_perbarang);


            $data = Product::whereIn('id', $product)->get()->groupBy('users_id')->map(function ($item) {
                return $item;
            });


            foreach ($data as $kun => $value) {
                $dl = intval($kun) - intval(1);

                $ra =  ResponeHelper::GeneretInvoice(10);
                $invoicePRD = 'INVCPRD' . Auth::user()->id . date('Ymd') . $ra;
                # code...
                $cre = MasterTransactionProduct::create([
                    'invoice' => $invoicePRD,
                    'kurir' => $kurir[$dl],
                    'harga_kurir' => $harga_kurir[$dl],
                    'master_transactions_id' => $pro->id,
                    'users_id' => Auth::user()->id,
                    'status' => 0,
                ]);

                $penjual = 0;

                foreach ($value as $ky => $da) {
                    # code...
                    $jul = 0;
                    foreach ($product as $e => $l) {
                        # code...
                        if ($da->id == $l) {
                            $jul = $jumlah[$e];
                        }
                    }
                    $hag = 0;
                    foreach ($product as $eu => $du) {
                        # code...
                        if ($da->id == $du) {
                            $hag = $harga_total_perbarang[$eu];
                        }
                    }

                    $penjual = $da->users_id;


                    $lol = TransactionModel::create([
                        'product' => $da->id,
                        'jumlah' => $jul,
                        'harga_product' => $hag,
                        'status' => 0,
                        'users_id' => Auth::user()->id,
                        'nomor_invoice' => $invoicePRD,
                        'master_transactions_id' => $pro->id,
                        'master_transactions_products_id' => $cre->id,
                    ]);
                }

                $cre->update([
                    'penjual_id' => $penjual,
                ]);
            }

            foreach ($product as $de => $del) {
                # code...
                $list =  AddCard::where('products_id', $del)->where('users_id', Auth::user()->id)->first();
                if ($list) {
                    $list->delete();
                }
            }

            DB::commit();
            return ResponeHelper::CreteorUpdateBerhasil($pro, 'berhasil create');
        } catch (\Exception $th) {

            DB::rollBack();
            return ResponeHelper::badRequest('gagal transaksi : error' . $th);
            //throw $th;
        }
    }

    public function ListTransacation()
    {
        # code...

        $get =  MasterTransaction::where('users_id', Auth::user()->id)->where('status', 0)->get();

        return ResponeHelper::GetDataBerhasil(TransaksiPembayaranResource::collection(collect($get)));
    }

    public function ListTransaksiDikemas()
    {
        # code...
        $get = MasterTransactionProduct::where('users_id', Auth::user()->id)->where('status', 0)->get();
        return ResponeHelper::GetDataBerhasil(TransaksiPembayaranProductResource::collection(collect($get)));
    }
    public function ListransaksiDiKirim()
    {
        # code...
        $get = MasterTransactionProduct::where('users_id', Auth::user()->id)->where('status', 1)->get();
        return ResponeHelper::GetDataBerhasil(TransaksiPembayaranProductResource::collection(collect($get)));
    }
    public function ListransaksiDiTerima()
    {
        # code...
        $get = MasterTransactionProduct::where('users_id', Auth::user()->id)->where('status', 2)->get();
        return ResponeHelper::GetDataBerhasil(TransaksiPembayaranProductResource::collection(collect($get)));
    }

    public function UpdateDikemas($id)
    {
        # code...

        $cek = MasterTransactionProduct::find($id);
        if ($cek) {
            $cek->update([
                'status' => 1
            ]);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'berhasil update status');
        }
        return ResponeHelper::badRequest('gagal transaksi');
    }
    public function UpdateDiterima($id)
    {
        # code...

        $cek = MasterTransactionProduct::find($id);
        if ($cek) {
            $cek->update([
                'status' => 2
            ]);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'berhasil update status');
        }
        return ResponeHelper::badRequest('gagal transaksi');
    }

    public function RettingProduct(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string',
            'rating' => 'required|numeric',
            'products_id' => 'required|numeric',
            'penjual_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cre = $request->all();
        $cre['users_id'] = Auth::user()->id;
        ProductRating::create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'berhasil update reting');
    }
}
