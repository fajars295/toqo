<?php

namespace App\Http\Resources;

use App\Model\User\Profile;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentContenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $profile = Profile::where('users_id', $this->users_id)->first();

        return [
            'id' => $this->id,
            'name' => User::find($this->users_id)->name,
            'url' => $profile == null ? url('logo/account.jpg') : url($profile->foto),
            'deskripsi' => $this->deskripsi
        ];
    }
}
