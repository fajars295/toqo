<?php

namespace App\Http\Resources;

use App\Model\Profile\TokoDetail;
use App\Model\User\Profile;
use Illuminate\Http\Resources\Json\JsonResource;

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
        ];
    }
}
