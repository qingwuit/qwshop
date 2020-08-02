<?php

namespace App\Http\Resources\Home\GoodsResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsListCollection extends ResourceCollection
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
         
        return $this->collection->map(function($item){
            $goods_price = $item->goods_price;
            $goods_market_price = $item->goods_market_price;
            $goods_stock = $item->goods_stock;

            // 判断是否存在sku
            if(isset($item->goods_skus) && count($item->goods_skus)>0){
                $goods_stock = 0;
                foreach($item->goods_skus as $v){
                    $goods_stock += $v['goods_stock'];
                }
                
                $goods_price = $item->goods_skus[0]['goods_price'];
                $goods_market_price = $item->goods_skus[0]['goods_market_price'];
            }
            return [
                'id'                    =>  $item->id,
                'goods_name'            =>  $item->goods_name,
                'goods_subname'         =>  $item->goods_subname,
                'goods_price'           =>  $goods_price,
                'goods_market_price'    =>  $goods_market_price,
                'goods_stock'           =>  $goods_stock,
                'goods_sale'            =>  $item->goods_sale,
                'goods_master_image'    =>  $this->thumb($item->goods_master_image),
            ];
        });
        
    }
}
