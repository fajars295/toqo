<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryProductResource extends JsonResource
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
            'users' => User::find($this->users_id)->name,
            'stock' => $this->stock,
            'type' => $this->type,
            'keterangan' => $this->keterangan,
        ];
    }
}
