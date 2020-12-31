<?php

namespace App\Http\Resources\Admin\OrderSettlementResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderSettlementInfoCollection extends ResourceCollection
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
            'data'=>$this->collection->map(function($item) {
                return [
                    'order_no'          =>  $item->order->order_no,
                    'total_price'       =>  $item->total_price,
                    'settlement_price'  =>  $item->settlement_price,
                    'status'            =>  $item->status,
                    'info'              =>  $item->info,
                    'created_at'        =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
