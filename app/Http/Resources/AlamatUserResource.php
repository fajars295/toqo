<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AlamatUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'judul_alamat' => $this->judul_alamat,
            'nama_penerima' => $this->nama_penerima,
            'alamat' => $this->alamat,
            'provinsi' => DB::table('provinsi')->find($this->provinsi)->name_provinsi,
            'kota' => DB::table('kota_kabupaten')->find($this->kota)->nama_kota_kabupaten,
            'kecamatan' => DB::table('kecamatan')->find($this->kecamatan)->nama_kecamatan,
            'kelurahan' => DB::table('kelurahan')->find($this->kelurahan)->nama_kelurahan,
            'kode_pos' => $this->kode_pos,
            'patokan' => $this->patokan,
            'nomor_hp' => $this->nomor_hp
        ];
    }
}
