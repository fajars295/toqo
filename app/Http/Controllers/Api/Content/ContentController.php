<?php

namespace App\Http\Controllers\Api\Content;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentContenResource;
use App\Http\Resources\ContendetaisResource;
use App\Http\Resources\ContentResource;
use App\Model\Content\CommentContent;
use App\Model\Content\Content;
use App\Model\Content\LikeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    //
    private $model;
    private  $modelLikeContent;
    private  $modelCommentContent;
    public function __construct()
    {
        $this->model = new Content();
        $this->modelLikeContent = new LikeContent();
        $this->modelCommentContent = new CommentContent();
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'foto' => 'required|file',
            'deskripsi' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $uploadimg = ResponeHelper::uploadImg($request->foto, 'Content');

        $cre = $request->all();
        $cre['foto'] = $uploadimg;
        $cre['users_id'] = Auth::user()->id;
        $cre['lihat'] = 0;

        $this->model->create($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Create Feed');
    }

    public function Updatedata(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string|',
            'content_id' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $cek = $this->model->find($request->content_id);
        if ($request->foto) {
            File::delete(public_path() . $cek->foto);
            $uploadimg = ResponeHelper::uploadImg($request->foto, 'Content');
        } else {
            $uploadimg = $cek->foto;
        }

        $cre = $request->all();
        $cre['foto'] = $uploadimg;
        $upd = $cek->update($cre);
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Feed');
    }

    public function List()
    {
        # code...
        $cek = $this->model->get();
        return ResponeHelper::GetDataBerhasil(ContentResource::collection(collect($cek)));
    }
    public function detail($id)
    {
        # code...
        $cek =  new ContendetaisResource($this->model->find($id));

        return ResponeHelper::GetDataBerhasil($cek);
    }

    public function Destroy($id)
    {
        # code...
        $data = $this->model->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Feed Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            $hapus = File::delete(public_path() . $data->foto);
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Feed');
        }
        return ResponeHelper::badRequest('Gagal Delete Feed');
    }

    public function LikeContent($id)
    {
        # code...
        $cekProduct = $this->model->find($id);
        if (!$cekProduct) {
            return ResponeHelper::badRequest('Content Tidak Di Temukan');
        }
        $ceklike = $this->modelLikeContent->where('users_id', Auth::user()->id)->where('contents_id', $id)->first();
        if ($ceklike) {
            return ResponeHelper::badRequest('Anda Sudah Pernah Like');
        }
        DB::beginTransaction();
        try {
            //code...
            $cre['users_id'] = Auth::user()->id;
            $cre['contents_id'] = $id;
            $input = $this->modelLikeContent->create($cre);
            DB::commit();
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Like');
        } catch (\Exception $th) {
            //throw $th;
            DB::rollBack();
            return ResponeHelper::badRequest('Gagal Create Like err:' . $th);
        }
    }

    public function UnLikeContent($id)
    {
        # code...
        $cekContent = $this->model->find($id);
        if (!$cekContent) {
            return ResponeHelper::badRequest('Content Tidak Di Temukan');
        }
        $ceklike = $this->modelLikeContent->where('users_id', Auth::user()->id)->where('contents_id', $id)->first();
        if (!$ceklike) {
            return ResponeHelper::badRequest('Anda Belum Pernah Like');
        }
        $del =  $ceklike->delete();
        return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil UnLike');
    }

    public function CommentContent(Request $request)
    {
        # code...

        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string|',
            'contents_id' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekProduct = $this->model->find($request->contents_id);
        if (!$cekProduct) {
            return ResponeHelper::badRequest('Content Tidak Di Temukan');
        }

        DB::beginTransaction();
        try {
            $cre = $request->all();
            $cre['users_id'] = Auth::user()->id;
            $input = $this->modelCommentContent->create($cre);
            DB::commit();
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Comment');
        } catch (\Exception $th) {
            DB::rollBack();
            return ResponeHelper::badRequest('Gagal Create Comment err:' . $th);
        }
    }
    public function CommentContentDelete($id)
    {
        # code...
        $data = $this->modelCommentContent->find($id);

        if (!$data) {
            return ResponeHelper::badRequest('Id Comment Tidak di Temukan');
        }
        $del = $data->delete();
        if ($del) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Delete Comment');
        }
        return ResponeHelper::badRequest('Gagal Delete Comment');
    }
}
