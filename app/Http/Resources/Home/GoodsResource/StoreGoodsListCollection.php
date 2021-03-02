<?php

namespace App\Http\Resources\Home\GoodsResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreGoodsListCollection extends ResourceCollection
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
                
                $goods_price = $item->goods_price;
                $goods_market_price = $item->goods_market_price;

                // 判断是否存在sku
                if(isset($item->goods_sku)){
                    $goods_price = $item->goods_sku['goods_price'];
                    $goods_market_price = $item->goods_sku['goods_market_price'];
                }
                return [
                    'id'                    =>  $item->id,
                    'goods_name'            =>  $item->goods_name,
                    'goods_subname'         =>  $item->goods_subname,
                    'goods_price'           =>  $goods_price,
                    'goods_market_price'    =>  $goods_market_price,
                    'goods_sale'            =>  $item->goods_sale,
                    'goods_master_image'    =>  $this->thumb($item->goods_master_image,300),
             
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
