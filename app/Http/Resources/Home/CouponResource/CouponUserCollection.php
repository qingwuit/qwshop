<?php

namespace App\Http\Resources\Home\CouponResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CouponUserCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item){
                return [
                    'id'                        =>  $item->id,
                    'name'                      =>  $item->name,
                    'money'                     =>  $item->money,
                    'use_money'                 =>  $item->use_money,
                    'nickname'                  =>  $item->store->store_name,
                    'status'                    =>  $item->status,
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
