<?php

namespace App\Http\Resources;

use App\Qingwuit\Services\OrderService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderHomeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orderService = new OrderService;
        return [
            'data'=>$this->collection->map(function ($item) use ($orderService) {
                return [
                    'id'                    =>  $item->id,
                    'order_no'              =>  $item->order_no,
                    'order_price'           =>  $item->order_price,
                    'total_price'           =>  $item->total_price,
                    'order_status'          =>  $item->order_status,
                    'order_status_cn'       =>  $orderService->getOrderStatusCn($item),
                    'store'                 =>  $item->store,
                    'refund_status'         =>  $item->refund_status,
                    'user'                  =>  $item->user,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i'),
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
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'last_page'=>$this->lastPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
