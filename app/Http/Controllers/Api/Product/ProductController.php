<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\HistoryProductResource;
use App\Http\Resources\ProductResource;
use App\Model\Content\Notifikasi;
use App\Model\Product\Category;
use App\Model\Product\HistoryStock;
use App\Model\Product\LikeProduct;
use App\Model\Product\Product;
use App\Model\Product\ProductFavorit;
use App\Model\Product\ProductFoto;
use App\Model\Product\ProductSpesifikasi;
use App\Model\Product\TypeCategory;
use App\Model\Product\Typespesifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File as FacadesFile;

class ProductController extends Controller
{
    //
    private $model;
    private $modelSpesifikasi;
    private $modelFoto;
    private $modelHistoryStock;
    private $modelLikeProduct;
    private $modelProductFavorit;
    public function __construct()
    {
        $this->model = new Product();
        $this->modelSpesifikasi = new ProductSpesifikasi();
        $this->modelFoto = new ProductFoto();
        $this->modelHistoryStock = new HistoryStock();
        $this->modelLikeProduct = new LikeProduct();
        $this->modelProductFavorit = new ProductFavorit();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'diskon' => 'required|numeric',
            'categories_id' => 'required|numeric',
            'type_categories_id' => 'required|numeric',
            'foto' => 'required',
            'deskripsi_foto' => 'required',
            'type_spesifikasi_id' => 'required',
            'deskripsi_spesifikasi' => 'required',
            'stock' => 'required',
            'type' => 'required',
            'status_ongkir' => 'required',
            'casback' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekTypeCategory = TypeCategory::find($request->type_categories_id);
        if (!$cekTypeCategory) {
            return ResponeHelper::badRequest('Type Id Tidak Di Temukan');
        }

        $cekCategory = Category::find($request->categories_id);
        if (!$cekCategory) {
            return ResponeHelper::badRequest('Category Tidak Ditemukan');
        }

        $cre = $request->all();
        $cre['users_id'] = Auth::user()->id;
        $cre['like'] = 0;
        $cre['total_pembelian'] = 0;

        DB::beginTransaction();
        try {
            //code...
            $prod = $this->model->create($cre);


            foreach ($request->foto as $key => $value) {
                # code...
                $upload = ResponeHelper::uploadImg($value, 'Product');
                $foto =  $this->modelFoto->create([
                    'products_id' => $prod->id,
                    'foto' => $upload,
                    'deskripsi' => $request->deskripsi_foto[$key],
                ]);
            }


            foreach ($request->type_spesifikasi_id as $ke => $valu) {
                # code...
                $ceksfesifikasi = Typespesifikasi::find($valu);
                if (!$ceksfesifikasi) {
                    DB::rollBack();
                    return ResponeHelper::badRequest('Type Spesifikasi Tidak di temukan');
                }


                $spek =  $this->modelSpesifikasi->create([
                    'products_id' => $prod->id,
                    'type_spesifikasi' => $ceksfesifikasi->name,
                    'deskripsi' => $request->deskripsi_spesifikasi[$ke],
                ]);
            }

            $this->modelHistoryStock->create([
                'users_id' => Auth::user()->id,
                'stock' => $request->stock,
                'type' => 'Tambah',
                'keterangan' => 'Create New Product',
                'products_id' => $prod->id,
            ]);

            Notifikasi::create([
                'keterangan' => 'ada Product baru ayo kita cek ' . $request->name,
                'type' => '1',
                'users_id' => 0,
                'tujuan_id' => $prod->id,
                'status' => false,
            ]);

            ResponeHelper::fcmtoken(null, 'ada Product baru ayo kita cek', $request->name, 'all');
            DB::commit();
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Product');
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return ResponeHelper::badRequest('Gagal Create Product err : ' . $th);
        };
    }
    public function updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'diskon' => 'required|numeric',
            'categories_id' => 'required|numeric',
            'type_categories_id' => 'required|numeric',
            'products_id' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekTypeCategory = TypeCategory::find($request->type_categories_id);
        if (!$cekTypeCategory) {
            return ResponeHelper::badRequest('Type Id Tidak Di Temukan');
        }

        $cekCategory = Category::find($request->categories_id);
        if (!$cekCategory) {
            return ResponeHelper::badRequest('Category Tidak Ditemukan');
        }

        $cre = $request->all();

        DB::beginTransaction();
        try {
            $prod = $this->model->find($request->products_id)->update($cre);
            DB::commit();
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Product');
        } catch (\Exception $th) {
            DB::rollBack();
            return ResponeHelper::badRequest('Gagal Create Product err : ' . $th);
        };
    }

    public function DeleteProduct($id)
    {
        # code...
        $cek = $this->model->find($id);
        if ($cek) {
            $del = $cek->delete();
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Pruduct');
        } else {
            return ResponeHelper::badRequest('Data Product Tidak Di Temukan');
        }
    }

    public function GetProduct(Request $request)
    {
        # code...
        $product = $this->model->where('name', 'like', "%" . $request->name . "%")->orderBy('id', 'desc')->get();

        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }

    public function TambahFoto(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'products_id' => 'required',
            'foto' => 'required',
            'deskripsi' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekproduct = $this->model->find($request->products_id);
        if (!$cekproduct) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }

        $uploadimg = ResponeHelper::uploadImg($request->foto, 'Product');
        $dat = $request->all();
        $dat['foto'] = $uploadimg;
        $this->modelFoto->create($dat);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Upload Foto');
    }
    public function DeleteFoto($id)
    {
        # code...
        $cek = $this->modelFoto->find($id);
        if ($cek) {
            $del = $cek->delete();
            FacadesFile::delete(public_path() . $cek->logo);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Foto Prudoct');
        } else {
            return ResponeHelper::badRequest('Data Product Foto Tidak Di Temukan');
        }
    }
    public function TambahSpesifikasi(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'products_id' => 'required',
            'type_spesifikasi' => 'required',
            'deskripsi' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekproduct = $this->model->find($request->products_id);
        if (!$cekproduct) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }
        $cekspek = Typespesifikasi::find($request->type_spesifikasi);
        if (!$cekspek) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }
        $dat = $request->all();
        $dat['type_spesifikasi'] = $cekspek->name;
        $this->modelSpesifikasi->create($dat);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Upload Spesifikasi');
    }

    public function DeleteSpesifikasi($id)
    {
        # code...
        $cek = $this->modelSpesifikasi->find($id);
        if ($cek) {
            $del = $cek->delete();
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Spesifikasi Prudoct');
        } else {
            return ResponeHelper::badRequest('Data Product Spesifikasi Tidak Di Temukan');
        }
    }

    public function PenambahanStock(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            // 'users_id' => 'required',
            'stock' => 'required|numeric',
            // 'type' => 'required',
            'keterangan' => 'required',
            'products_id' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cekproduct = $this->model->find($request->products_id);
        if (!$cekproduct) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }

        $cre = $request->all();
        $cre['type'] = 'Tambah';
        $cre['users_id'] = Auth::user()->id;

        DB::beginTransaction();
        try {
            //code...
            $add =  $this->modelHistoryStock->create($cre);

            if ($add) {
                $tambah = $cekproduct->stock + $request->stock;
                $cekproduct->update([
                    'stock' => $tambah
                ]);
            }
            DB::commit();
            return ResponeHelper::CreteorUpdateBerhasil(NULL, 'Berhasil Menambahkan Stock');
        } catch (\Exception $th) {
            DB::rollBack();
            return ResponeHelper::badRequest('Gagal Update Stock Product err : ' . $th);
        };
    }

    public function HistoryProduct($id)
    {
        # code...
        $data = $this->modelHistoryStock->where('products_id', $id)->get();
        return ResponeHelper::GetDataBerhasil(HistoryProductResource::collection(collect($data)));
    }

    public function DetailsProduct($id)
    {
        # code...
        $product = $this->model->find($id);
        return ResponeHelper::GetDataBerhasil(new ProductResource($product));
    }

    public function LikeProduct($id)
    {
        # code...
        $cekProduct = $this->model->find($id);
        if (!$cekProduct) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }
        $ceklike = $this->modelLikeProduct->where('users_id', Auth::user()->id)->where('products_id', $id)->first();
        if ($ceklike) {
            return ResponeHelper::badRequest('Anda Sudah Pernah Like');
        }
        DB::beginTransaction();
        try {
            //code...
            $cre['users_id'] = Auth::user()->id;
            $cre['products_id'] = $id;
            $input = $this->modelLikeProduct->create($cre);
            $j =  $cekProduct->like + 1;
            if ($input) {
                $cekProduct->update([
                    'like' => $j
                ]);
            }

            DB::commit();

            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Like');
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return ResponeHelper::badRequest('Gagal Create Like err:' . $th);
        }
    }
    public function UnLikeProduct($id)
    {
        # code...
        $cekProduct = $this->model->find($id);
        if (!$cekProduct) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }
        $ceklike = $this->modelLikeProduct->where('users_id', Auth::user()->id)->where('products_id', $id)->first();
        if (!$ceklike) {
            return ResponeHelper::badRequest('Anda Belum Pernah Like');
        }
        $del =  $ceklike->delete();
        $un = $cekProduct->like - 1;
        $cekProduct->update([
            'like' => $un
        ]);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil UnLike');
    }

    public function ProductCatagory(Request $request, $id)
    {
        # code...


        switch ($request->type) {

            case 'terkait':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('categories_id', $id)
                    ->orderBy('id', 'desc')->get();
                break;
            case 'terbaru':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('categories_id', $id)
                    ->orderBy('id', 'desc')->get();
                break;
            case 'terlaris';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('categories_id', $id)
                    ->orderBy('total_pembelian', 'desc')
                    ->get();
                break;
            case 'termurah';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('categories_id', $id)
                    ->orderBy('harga', 'asc')
                    ->get();
                break;

            default:
                # code...
                return ResponeHelper::GetDataBerhasil(null);
                break;
        }

        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }
    public function ProductTypeCatagory(Request $request, $id)
    {
        # code...
        // dd('masuk');
        switch ($request->type) {

            case 'terkait':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type_categories_id', $id)
                    // ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terbaru':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type_categories_id', $id)
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terlaris';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type_categories_id', $id)
                    ->orderBy('total_pembelian', 'desc')
                    ->get();
                break;
            case 'termurah';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type_categories_id', $id)
                    ->orderBy('harga', 'asc')
                    ->get();
                break;

            default:
                # code...
                return ResponeHelper::GetDataBerhasil(null);
                break;
        }


        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }

    public function ProductPromoOrDiskon(Request $request)
    {
        # code...
        // dd('masuk');
        switch ($request->type) {

            case 'terkait':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('diskon', '!=', 0)
                    // ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terbaru':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('diskon', '!=', 0)
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terlaris';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('diskon', '!=', 0)
                    ->orderBy('total_pembelian', 'desc')
                    ->get();
                break;
            case 'termurah';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('diskon', '!=', 0)
                    ->orderBy('harga', 'asc')
                    ->get();
                break;

            default:
                # code...
                return ResponeHelper::GetDataBerhasil(null);
                break;
        }


        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }
    public function ProductCasback(Request $request)
    {
        # code...
        // dd('masuk');
        switch ($request->type) {

            case 'terkait':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('casback', '!=', 0)
                    // ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terbaru':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('casback', '!=', 0)
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terlaris';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('casback', '!=', 0)
                    ->orderBy('total_pembelian', 'desc')
                    ->get();
                break;
            case 'termurah';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('casback', '!=', 0)
                    ->orderBy('harga', 'asc')
                    ->get();
                break;

            default:
                # code...
                return ResponeHelper::GetDataBerhasil(null);
                break;
        }


        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }
    public function ProductGratisOnkir(Request $request)
    {
        # code...
        // dd('masuk');
        switch ($request->type) {

            case 'terkait':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('status_ongkir', 1)
                    // ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terbaru':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('status_ongkir', 1)
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terlaris';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('status_ongkir', 1)
                    ->orderBy('total_pembelian', 'desc')
                    ->get();
                break;
            case 'termurah';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('status_ongkir', 1)
                    ->orderBy('harga', 'asc')
                    ->get();
                break;

            default:
                # code...
                return ResponeHelper::GetDataBerhasil(null);
                break;
        }


        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }
    public function ProductCOD(Request $request)
    {
        # code...
        // dd('masuk');
        switch ($request->type) {

            case 'terkait':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type', 'Cod')
                    // ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terbaru':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type', 'Cod')
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terlaris';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type', 'Cod')
                    ->orderBy('total_pembelian', 'desc')
                    ->get();
                break;
            case 'termurah';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->where('type', 'Cod')
                    ->orderBy('harga', 'asc')
                    ->get();
                break;

            default:
                # code...
                return ResponeHelper::GetDataBerhasil(null);
                break;
        }


        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }
    public function ProductBrandBaru(Request $request)
    {
        # code...
        // dd('masuk');
        switch ($request->type) {

            case 'terkait':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    // ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terbaru':
                # code...
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'terlaris';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->orderBy('total_pembelian', 'desc')
                    ->get();
                break;
            case 'termurah';
                $product = $this->model->where('name', 'like', "%" . $request->name . "%")
                    ->orderBy('harga', 'asc')
                    ->get();
                break;

            default:
                # code...
                return ResponeHelper::GetDataBerhasil(null);
                break;
        }


        return ResponeHelper::GetDataBerhasil(ProductResource::collection(collect($product)));
    }
    public function ProductPopuler(Request $request)
    {
        # code...
        $product = $this->model->where('name', 'like', "%" . $request->name . "%")
            ->orderBy('like', 'desc')->get();
        $show = ProductResource::collection(collect($product));

        return ResponeHelper::GetDataBerhasil($show);
    }
    public function ProductTerlaris(Request $request)
    {
        # code...
        $product = $this->model->where('name', 'like', "%" . $request->name . "%")
            ->orderBy('total_pembelian', 'desc')->get();
        $show = ProductResource::collection(collect($product));

        return ResponeHelper::GetDataBerhasil($show);
    }

    public function AddProduct(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'products_id' => 'required|numeric|',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekproduct = Product::find($request->products_id);
        if (!$cekproduct) {
            return ResponeHelper::badRequest('Product Tidak Di Temukan');
        }
        $cekcard = $this->modelProductFavorit->where('users_id', Auth::user()->id)->where('products_id', $request->products_id)->first();
        if ($cekcard) {
            return ResponeHelper::badRequest('Product Sudah Ada Di Categori Favorit Anda');
        }

        $cre = $request->all();
        $cre['users_id'] = Auth::user()->id;
        $this->modelProductFavorit->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Add To Favorit');
    }

    public function ProductFavorit()
    {
        # code...
        $cek = $this->modelProductFavorit->where('users_id', Auth::user()->id)->get();
        return ResponeHelper::GetDataBerhasil(CardResource::collection(collect($cek)));
    }
}
