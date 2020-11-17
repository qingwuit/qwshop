<?php

namespace App\Http\Resources\Seller\SeckillResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeckillCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    use HelperTrait;
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item){
                return [
                    'id'                    =>  $item->id,
                    'discount'              =>  $item->discount,
                    'goods_id'              =>  $item->goods_id,
                    'status'                =>  now()->gt($item->end_time)?0:(now()->between($item->start_time,$item->end_time)?1:2),
                    'start_time'            =>  $item->start_time->format('Y-m-d H:i:s'),
                    'end_time'              =>  $item->end_time->format('Y-m-d H:i:s'),
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                    'goods'                 =>  [
                                                    'goods_name'                =>  $item->goods->goods_name,
                                                    'goods_master_image'        =>  $this->thumb($item->goods->goods_master_image,150),
                                                ],
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
