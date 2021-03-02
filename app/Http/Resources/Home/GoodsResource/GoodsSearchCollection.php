<?php

namespace App\Http\Resources\Home\GoodsResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsSearchCollection extends ResourceCollection
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

                // 判断是否存在sku
                if(isset($item->goods_sku)){
                    $goods_price = $item->goods_sku['goods_price'];
                }

                if(!empty($item->collective)){
                    $goods_price = round($goods_price*(1-$item->collective->discount/100),2);
                }

                return [
                    'id'                    =>  $item->id,
                    'goods_name'            =>  $item->goods_name,
                    'goods_price'           =>  $goods_price,
                    'goods_sale'            =>  $item->goods_sale,
                    'store_name'            =>  $item->store->store_name,
                    'goods_master_image'    =>  $this->thumb($item->goods_master_image,300),
                    'order_comment_count'   =>  $item->order_comment_count,
                    'need'                  =>  empty($item->collective)?0:$item->collective->need,
                    // 'goods_no'              =>  $item->goods_no,
             
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
