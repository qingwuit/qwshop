<?php

namespace App\Http\Resources\Home\FavoriteResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FavoriteCollection extends ResourceCollection
{
    use HelperTrait;
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
                    'out_id'                =>  $item->out_id,
                    'goods_name'            =>  $item->goods->goods_name,
                    'goods_master_image'    =>  $this->thumb($item->goods->goods_master_image),
                    'goods_price'           =>  empty($item->goods->sku)?$item->goods->goods_price:$item->goods->sku->goods_price,
                ];
            }),
            // 'data'=>$this->collection,
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
