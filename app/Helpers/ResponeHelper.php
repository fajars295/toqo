<?php

namespace App\Helpers;

use App\transfermodel;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class ResponeHelper
{
    public static function ResponValidator($validator)
    {
        $array = "";
        foreach ($validator->errors()->all() as $key => $value) {
            $array .=  $value . ',';
        }
        # code...
        return   response()->json([
            'code' => 422,
            "status" => false,
            'message' =>  $array,
        ], 422);
    }

    public static function TidakadaContet($data)
    {
        # code...
        return   response()->json([
            'code' => 204,
            "status" => false,
            'message' => 'Tidak Ada Conten tersedia',
            'data' => $data
        ], 204);
    }

    public static function GetDataBerhasil($data)
    {
        # code...
        return   response()->json([
            'code' => 200,
            "status" => true,
            'message' => 'Berhasil Mengambil data',
            'data' => $data
        ], 200);
    }

    public static function CreteorUpdateBerhasil($data, $message)
    {
        # code...
        return   response()->json([
            'code' => 200,
            "status" => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function Conflikdata($data)
    {
        # code...
        return   response()->json([
            'code' =>  409,
            "status" => false,
            'message' => $data,
        ], 409);
    }


    public static function worngdata($data)
    {
        # code...
        return   response()->json([
            'code' =>  401,
            "status" => false,
            'message' => $data,
        ], 401);
    }



    public static function SevicesErorr($data)
    {
        # code...
        return   response()->json([
            'code' =>  500,
            "status" => false,
            'message' => $data,
        ], 500);
    }

    public static function badRequest($msg)
    {
        return response()->json([
            'status' => false,
            'code' => 400,
            'message' => $msg,
        ], 400);
    }

    public static function Forbidden($msg)
    {
        return response()->json([
            'status' => false,
            'code' => 403,
            'message' => $msg,
        ], 403);
    }

    public static function uploadImg($request, $folder)
    {
        # code...
        $file = $request;
        // dd($file);
        $imagePath = '/gambar/' . $folder . '/';
        $path = public_path() . $imagePath;
        $extension = $file->getClientOriginalExtension();
        $filename = $folder . '-' . Str::random(16) . Auth::user()->id . '.' . $extension;
        $file->move($path, $filename);

        return $imagePath . $filename;
    }
}
