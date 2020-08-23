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
                    'specs'                 =>  $item->specs->map(function($item2){
                                                    return [
                                                        'id'        =>  $item2->id,
                                                        'name'      =>  $item2->name,
                                                    ];
                                                }),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
