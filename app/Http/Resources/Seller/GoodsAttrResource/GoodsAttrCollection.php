<?php

namespace App\Http\Resources\Seller\GoodsAttrResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsAttrCollection extends ResourceCollection
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
            'data'=>$this->collection->map(function($item){
                return [
                    'id'                    =>  $item->id,
                    'name'                  =>  $item->name,
                ];
            }),
            'tatal'=>$this->total(), // 总页码
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
