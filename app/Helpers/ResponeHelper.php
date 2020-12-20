<?php

namespace App\Helpers;

use App\Model\Content\FcmToken;
use App\Model\Content\Notifikasi;
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

    public static function fcmtoken($email, $title, $body, $type)
    {
        # code...
        // dd($header);

        if ($type == 'all') {
            $getAllToken = FcmToken::get()->map(function ($value) {
                return $value->token;
            })->toArray();
        } else {
            $getAllToken = FcmToken::where('email', $email)->get()->map(function ($value) {
                return $value->token;
            })->toArray();
        }

        $array = [
            "registration_ids" => $getAllToken,
            "notification" => [
                "title" => $title,
                "body" => $body
            ]
        ];
        $bod = json_encode($array);

        $client = new Client();
        $url = 'https://fcm.googleapis.com/fcm/send';

        try {
            $response = $client->post($url, [
                'http_errors' => false,
                'body' => $bod,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => "key=AAAAlH0fdOc:APA91bG4R08nIyGt42q0w61_kRHOUelOQH5xHHDBfUjKZ1ehYgfO9SbyST9io5aXtlm2bf7QLv0UqFLW0WuQrKdXx-Z0I9sFaAXec0AsjAloIYgs5puhhvkvc4sH-CM4sXx7DbFYFcUf"
                ]

            ]);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return false;
        } catch (\Exception $exception) {
            return false;
        }
        $respons = $response->getBody()->getContents();
        return $respons;
    }

    public static function GeneretInvoice($maxLength)
    {
        # code...
        $code = 0;
        for ($i = 0; $i < $maxLength; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }

    public static function GetHari()
    {
        # code...
        $daftar_hari = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );
        $date = date('Y-m-d');
        $namahari = date('l', strtotime($date));

        return $daftar_hari[$namahari];
    }

    public static function ClaimPoint($hari)
    {
        # code...
        switch ($hari) {
            case 'Senin':
                return 1;
                break;
            case 'Selasa':
                # code...
                return 2;
                break;
            case 'Rabu':
                # code...
                return 3;
                break;
            case 'Kamis':
                # code...
                return 4;
                break;
            case 'Jumat':
                # code...
                return 5;
                break;
            case 'Sabtu':
                # code...
                return 6;
                break;
            case 'Minggu':
                # code...
                return 7;
                break;

            default:
                # code...
                return 8;
                break;
        }
    }
}
