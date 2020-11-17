<?php

namespace App\Http\Resources\Seller\CollectiveResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectiveGoodsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    use HelperTrait;
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item){
                return [
                    'id'                    =>  $item->id,
                    'goods_name'            =>  $item->goods_name,
                    'goods_master_image'    =>  $this->thumb($item->goods_master_image,150),
                    'is_use'                =>  !empty($item->collective->goods_id)?true:false,
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
