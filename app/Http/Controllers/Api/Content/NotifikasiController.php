<?php

namespace App\Http\Controllers\Api\Content;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Model\Content\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    //
    private $model;
    public function __construct()
    {
        $this->model = new Notifikasi();
    }

    public function List()
    {
        # code...
        $cek = $this->model->orderBy('id', 'desc')->paginate(15);
        return ResponeHelper::GetDataBerhasil($cek);
    }
}
