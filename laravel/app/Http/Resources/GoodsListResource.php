<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $res                       = parent::toArray($request);
        $res['pic_url']            = $this->pic_url;
        $res['category_name']      = $this->category_name;
        $res['full_category_name'] = $this->full_category_name;

        unset($res['description']);
        return $res;
    }
}
