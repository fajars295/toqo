<?php

namespace App\Http\Resources;

use App\Model\Product\TypeCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'typeCategory' => TypeCategory::find($this->type_categories_id)->name,
            'name' => $this->name,
            'url' => url($this->logo),
        ];;
    }
}
