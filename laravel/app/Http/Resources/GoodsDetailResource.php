<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->user->hidePhone = true;

        $res                       = parent::toArray($request);
        $res['pic_url']            = $this->pic_url;
        $res['category_name']      = $this->category_name;
        $res['full_category_name'] = $this->full_category_name;
        $res['full_category_id']   = $this->full_category_id;
        $res['user']               = new UserProfileResource($this->user);
        $res['deleted_at']         = $this->when($this->deleted_at, $this->deleted_at);

        return $res;
    }
}
