<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $arr           = parent::toArray($request);
        $arr['credit'] = $this->credit;
        unset($arr['password']);

        if ($this->hidePhone) {
            $arr['phone'] = substr_replace($arr['phone'], '****', 3, 4);
        }
        return $arr;
    }
}
