<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadBuktiResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->status == 0) {
            $status = 'Tolak';
        } elseif ($this->status == 1) {
            # code...
            $status = 'Pending';
        } else {
            $status = 'Berhasil';
        }
        return [
            'id' => $this->id,
            'nama_pemilik_rekening' => $this->nama_pemilik_rekening,
            'nomor_rekening' => $this->nomor_rekening,
            'nomor_invoice' => $this->invoice_id,
            'nama_bank' => $this->nama_bank,
            'url' => url($this->foto),
            'status' => $status,
        ];
    }
}
