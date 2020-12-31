<?php

namespace App\Http\Resources\Seller\CollectiveLogResourcee;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectiveLogCollection extends ResourceCollection
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
                    'id'                        =>  $item->id,
                    'goods_name'                =>  $item->goods->goods_name,
                    'goods_masteer_image'       =>  $this->thumb($item->goods->goods_masteer_image,300),
                    'discount'                  =>  $item->discount,
                    'need'                      =>  $item->need,
                    'orders_count'              =>  $item->orders_count,
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
