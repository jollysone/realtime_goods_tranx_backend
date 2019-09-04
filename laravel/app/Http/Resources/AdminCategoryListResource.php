<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryListResource extends JsonResource
{
    public static $isWithChildren = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $arr = parent::toArray($request);

        $arr['children'] = $this->when(self::$isWithChildren && $this->level < 2, AdminCategoryListResource::collection($this->children));

        return $arr;
    }
}
