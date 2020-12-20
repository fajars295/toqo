<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponeHelper;
use App\Http\Controllers\Controller;
use App\Model\User\Loyalty as UserLoyalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Loyalty extends Controller
{
    //

    public function ClaimLoyalty()
    {
        # code...
        $gethari = ResponeHelper::GetHari();
        $getpoint = ResponeHelper::ClaimPoint($gethari);

        $cekclaim = UserLoyalty::where('users_id', Auth::user()->id)->whereDate('created_at', '=', date('Y-m-d'))->first();
        if ($cekclaim) {
            return ResponeHelper::Forbidden('anda sudah claim hari ini');
        }

        $cre = UserLoyalty::create([
            'users_id' => Auth::user()->id,
            'koin' => $getpoint,
            'keterangan' => 'Koin hari ' . $gethari,
            'status' => 1,
        ]);

        return ResponeHelper::CreteorUpdateBerhasil(null, 'berhasil Claim point');
    }
}
