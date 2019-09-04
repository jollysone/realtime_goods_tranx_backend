<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $res           = parent::toArray($request);
        $res['buyer']  = new UserProfileResource($this->buyer);
        $res['seller'] = new UserProfileResource($this->seller);
        $res['goods']  = new GoodsListResource($this->goods);
        return $res;
    }
}
