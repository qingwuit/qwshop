<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderAfterHomeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id'                    =>  $item->id,
                'order_no'              =>  $item->order_no,
                'order_price'           =>  $item->order_price,
                'total_price'           =>  $item->total_price,
                'freight_money'         =>  $item->freight_money,
                'coupon_money'          =>  $item->coupon_money,
                'receive_area'          =>  $item->receive_area,
                'receive_address'       =>  $item->receive_address,
                'receive_tel'           =>  $item->receive_tel,
                'receive_name'          =>  $item->receive_name,
                'delivery_no'           =>  $item->delivery_no,
                'delivery_code'         =>  $item->delivery_code,
                'refund'                =>  empty($item->refund)?[]:$item->refund,
                'remark'                =>  $item->remark,

                'order_goods'           =>  $item->order_goods->map(function ($v) {
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
