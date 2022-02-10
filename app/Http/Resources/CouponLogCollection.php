<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CouponLogCollection extends ResourceCollection
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
                    'id'                        =>  $item->id,
                    'name'                      =>  $item->name,
                    'nickname'                  =>  $item->user->nickname,
                    'store_id'                  =>  $item->store_id,
                    'user_id'                   =>  $item->user_id,
                    'order_id'                  =>  $item->order_id,
                    'coupon_id'                 =>  $item->coupon_id,
                    'money'                     =>  $item->money,
                    'use_money'                 =>  $item->use_money,
                    'stock'                     =>  $item->stock,
                    'content'                   =>  $item->content,
                    'start_time'                =>  $item->start_time,
                    'end_time'                  =>  $item->end_time,
                    'status'                    =>  $item->status,
                    'start_time'                =>  $item->start_time->format('Y-m-d H:i:s'),
                    'end_time'                  =>  $item->end_time->format('Y-m-d H:i:s'),
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
