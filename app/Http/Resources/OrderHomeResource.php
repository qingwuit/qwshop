<?php

namespace App\Http\Resources;

use App\Qingwuit\Services\KuaiBaoService;
use App\Qingwuit\Services\OrderService;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orderService = new OrderService();
        $kb = new KuaiBaoService();
        return [
            'id'                    =>  $this->id,
            'order_no'              =>  $this->order_no,
            'receive_name'          =>  $this->receive_name,
            'receive_tel'           =>  $this->receive_tel,
            'receive_area'          =>  $this->receive_area,
            'receive_address'       =>  $this->receive_address,
            'payment_name'          =>  $this->payment_name,
            'payment_name_cn'       =>  $orderService->getOrderPayMentCn($this->payment_name),
            'delivery_no'           =>  $this->delivery_no,
            'delivery_code'         =>  $this->delivery_code,
            'total_price'           =>  $this->total_price,
            'freight_money'         =>  $this->freight_money,
            'coupon_money'          =>  $this->coupon_money,
            'remark'                =>  $this->remark,
            'pay_time'              =>  $this->pay_time->format('Y-m-d H:i:s'),
            'created_at'            =>  $this->created_at->format('Y-m-d H:i:s'),
            'order_status'          =>  $this->order_status,
            'order_status_cn'       =>  $orderService->getOrderStatusCn($this),
            'delivery_list'         =>  empty($this->delivery_no) ? [] : $kb->getExpressInfo($this->delivery_no, $this->delivery_code, $this->receive_tel)['data'],
            'order_goods'           =>  $this->order_goods->map(function ($q) {
                return [
                    'goods_id' => $q->goods_id,
                    'goods_image' => $q->goods_image,
                    'goods_name' => $q->goods_name,
                    'goods_price' => $q->goods_price,
                    'sku_name' => $q->sku_name,
                    'buy_num' => $q->buy_num,
                ];
            }),
        ];
    }
}
