<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IntegralOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function ($item) {
                return [
                    'id'                    =>  $item->id,
                    'order_no'              =>  $item->order_no,
                    'order_name'            =>  $item->order_name,
                    'order_image'           =>  $item->order_image,
                    'total_price'           =>  $item->total_price,
                    'buyer_name'            =>  $item->user->nickname??'',
                    'order_status_cn'       =>  $item->delivery_code==''?__('tip.waitSend'):__('tip.orderCompletion'),
                    'pay_time'              =>  $item->pay_time,
                    'delivery_time'         =>  $item->delivery_time,
                    'delivery_no'           =>  $item->delivery_no,
                    'delivery_code'         =>  $item->delivery_code,
                    'receipt_time'          =>  $item->receipt_time,
                    'comment_time'          =>  $item->comment_time,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'last_page'=>$this->lastPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
