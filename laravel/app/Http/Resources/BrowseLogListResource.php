<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrowseLogListResource extends JsonResource
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
            'id'        => $this->id,
            'stay_time' => $this->stay_time,
            'source'    => $this->source,
            'goods'     => new GoodsListResource($this->goods)
        ];
    }
}
