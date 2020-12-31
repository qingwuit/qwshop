<?php

namespace App\Http\Resources\Seller\GoodsSpecResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SpecCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    =>  $this->id,
            'name'                  =>  $this->name,
        ];
    }
}
