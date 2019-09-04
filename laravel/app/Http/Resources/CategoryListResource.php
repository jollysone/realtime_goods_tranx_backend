<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'level'      => $this->level,
            'name'       => $this->name,
            'icon_class' => $this->icon_class,
            'amount'     => $this->amount,
            'children'   => $this->when($this->level < 2, CategoryListResource::collection($this->children))
        ];
    }
}
