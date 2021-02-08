<?php

namespace App\Http\Resources\Home\OrderResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CreateOrderAfterCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item){
            return [
                'id'                    =>  $item->id,
                'order_no'              =>  $item->order_no,
                'order_price'           =>  $item->order_price,
                'total_price'           =>  $item->total_price,
                'freight_money'         =>  $item->freight_money,
                'coupon_money'          =>  $item->coupon_money,
                'remark'                =>  $item->remark,

                'order_goods'           =>  $item->order_goods->map(function($v){
                                            return [
                                                'goods_image'=>$v->goods_image,
                                                'goods_name'=>$v->goods_name,
                                                'goods_price'=>$v->goods_price,
                                                'sku_name'=>$v->sku_name,
                                                'buy_num'=>$v->buy_num,
                                                'total_price'=>$v->total_price,
                                            ];
                }),
            ];
        });
    }
}
