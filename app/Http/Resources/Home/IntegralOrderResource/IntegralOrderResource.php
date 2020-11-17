<?php

namespace App\Http\Resources\Home\IntegralOrderResource;

use App\Services\KuaibaoService;
use App\Services\OrderService;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegralOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order_service = new OrderService();
        $kb_service = new KuaibaoService();
        return [
            'id'                    =>  $this->id,
            'order_no'              =>  $this->order_no,
            'receive_name'          =>  $this->receive_name,
            'receive_tel'           =>  $this->receive_tel,
            'receive_area'          =>  $this->receive_area,
            'receive_address'       =>  $this->receive_address,
            'delivery_no'           =>  $this->delivery_no,
            'delivery_code'         =>  $this->delivery_code,
            'total_price'           =>  $this->total_price,
            'remark'                =>  $this->remark,
            'created_at'            =>  $this->created_at->format('Y-m-d H:i:s'),
            'order_status'          =>  $this->order_status,
            'order_status_cn'       =>  $order_service->getOrderStatusCn($this),
            'delivery_list'         =>  empty($this->delivery_no)?[]:$kb_service->getExpressInfo($this->delivery_no,$this->delivery_code,$this->receive_tel),
            'order_goods'           =>  $this->order_goods->map(function($q){
                                        return [
                                            'goods_id'=>$q->goods_id,
                                            'goods_image'=>$q->goods_image,
                                            'goods_name'=>$q->goods_name,
                                            'goods_price'=>$q->goods_price,
                                            'buy_num'=>$q->buy_num,
                                        ];
            }),
        ];
    }
}
