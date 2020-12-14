<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponeHelper;
use App\Http\Resources\UserResource;
use App\Model\User\Profile;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function eror()
    {
        return response()->json([
            "status" => false,
            "code" => 401,
            "message" => "Unauthenticated.",
        ], 401);
    }


    public function Register(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }
        $arr = explode(' ', $request->name);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'users_code' => $arr[0] . rand(1, 9000000)
        ]);
        $user->save();
        return ResponeHelper::CreteorUpdateBerhasil($user, 'Berhasil Create');
    }

    public function Login(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cek = User::where('email', $request->email)->first();
        if (!$cek) {
            return ResponeHelper::worngdata('Email Anda Belum Terdaftar');
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return ResponeHelper::worngdata('Password Anda Salah');
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function UpdateProfile(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            // 'users_id'  => 'required|string',
            'nomor_hp'  => 'required|string',
            'jenis_kelamin'  => 'required|string',
            'tanggal_lahir'  => 'required|string',
            'nama_toko'  => 'required|string',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cre = Profile::updateOrCreate([
            'users_id' => Auth::user()->id
        ], [
            'users_id' => Auth::user()->id,
            'nomor_hp' => $request->nomor_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nama_toko' => $request->nama_toko,
        ]);

        if ($cre) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Beerhasil Update Profile');
        }
        return ResponeHelper::badRequest('gagal');
    }

    public function UpdateFoto(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'foto'  => 'required|file',
        ]);
        if ($validator->fails()) {
            return ResponeHelper::ResponValidator($validator);
        }

        $cekprofile = Profile::where('users_id', Auth::user()->id)->first()->foto;
        if ($cekprofile != null) {
            File::delete(public_path() . $cekprofile);
        }
        $upload = ResponeHelper::uploadImg($request->foto, 'Profile');
        $cre = Profile::updateOrCreate([
            'users_id' => Auth::user()->id
        ], [
            'users_id' => Auth::user()->id,
            'foto' => $upload,
        ]);

        if ($cre) {
            return ResponeHelper::CreteorUpdateBerhasil(null, 'Berhasil Update Foto');
        }
        return ResponeHelper::badRequest('gagal');
    }

    public function GetProfile()
    {
        # code...
        return ResponeHelper::GetDataBerhasil(new UserResource(Auth::user()));
    }
}
