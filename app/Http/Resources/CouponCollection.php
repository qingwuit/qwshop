<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CouponCollection extends ResourceCollection
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
                    'money'                     =>  $item->money,
                    'use_money'                 =>  $item->use_money,
                    'stock'                     =>  $item->stock,
                    'content'                   =>  $item->content,
                    'status'                    =>  strtotime($item->start_time)>time()?0:((strtotime($item->start_time)<=time() && strtotime($item->end_time)>=time())?1:2),
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                    'start_time'                =>  $item->start_time->format('Y-m-d H:i:s'),
                    'end_time'                  =>  $item->end_time->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
