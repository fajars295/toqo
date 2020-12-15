<?php

namespace App\Http\Resources;

use App\Model\Content\CommentContent;
use App\Model\Content\LikeContent;
use App\Model\User\Profile;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ContendetaisResource extends JsonResource
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
        $like = LikeContent::where('users_id', Auth::user()->id)->where('contents_id', $this->id)->first();
        return [
            'id' => $this->id,
            'name' => User::find($this->users_id)->name,
            'foto' => $profile == null ? url('logo/account.jpg') : url($profile->foto),
            'url' => url($this->foto),
            'deskripsi' => $this->deskripsi,
            'like' => LikeContent::where('contents_id', $this->id)->count(),
            'comment'  => CommentContent::where('contents_id', $this->id)->count(),
            'show' => $this->lihat,
            'status_like' => $like == null ? false : true,
            'list_comment' => CommentContenResource::collection(collect(CommentContent::where('contents_id', $this->id)->get())),
        ];
    }
}
