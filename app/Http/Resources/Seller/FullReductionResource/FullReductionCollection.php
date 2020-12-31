<?php

namespace App\Http\Resources\Seller\FullReductionResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FullReductionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
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
                    'money'                     =>  $item->money,
                    'use_money'                 =>  $item->use_money,
                    'name'                      =>  $item->name,
                    'status'                    =>  now()->gt($item->end_time)?0:(now()->between($item->start_time,$item->end_time)?1:2),
                    'start_time'                =>  $item->start_time->format('Y-m-d H:i:s'),
                    'end_time'                  =>  $item->end_time->format('Y-m-d H:i:s'),
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
