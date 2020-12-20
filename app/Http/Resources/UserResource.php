<?php

namespace App\Http\Resources;

use App\Model\Product\ProductRating;
use App\Model\Profile\TokoDetail;
use App\Model\User\Loyalty;
use App\Model\User\Profile;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $profile = Profile::where('users_id', $this->id)->first();
        $toko = TokoDetail::where('users_id', $this->id)->first();
        $rating = ProductRating::where('penjual_id', $this->id);
        $loyalty = Loyalty::where('users_id', $this->id)->where('status', 1)->get()->sum('koin');
        $loyaltyminus = Loyalty::where('users_id', $this->id)->where('status', 0)->get()->sum('koin');


        if ($toko) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'users_code' => $this->users_code,
                'foto' => $profile == null ? url('logo/account.jpg') : url($profile->foto),
                'nomor_hp' => $profile == null ? null : $profile->nomor_hp,
                'nama_toko' => $profile == null ? null : $profile->nama_toko,
                'alamat_toko' => $toko->alamat_toko,
                'alamat_pemilik_toko' => $toko->alamat_pemilik_toko,
                'nomor_toko' => $toko->nomor_toko,
                'logo' => url($toko->logo),
                'toko' => true,
                'rating' => $rating->count() == 0 ? 0 :  $rating->get()->avg('rating'),
                'point_loyalty' => $loyalty - $loyaltyminus
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'users_code' => $this->users_code,
            'foto' => $profile == null ? url('logo/account.jpg') : url($profile->foto),
            'nomor_hp' => $profile == null ? null : $profile->nomor_hp,
            'nama_toko' => $profile == null ? null : $profile->nama_toko,
            'toko' => false,
            'rating' => $rating->count() == 0 ? 0 :  $rating->get()->avg('rating'),
            'point_loyalty' => $loyalty - $loyaltyminus

        ];
    }
}
