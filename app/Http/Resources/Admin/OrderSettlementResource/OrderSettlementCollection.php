<?php

namespace App\Http\Resources\Admin\OrderSettlementResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderSettlementCollection extends ResourceCollection
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
                    'settlement_no'     =>  $item->settlement_no,
                    'settlement_no'     =>  $item->settlement_no,
                    'total_price'       =>  $item->total,
                    'settlement_price'  =>  $item->settlement,
                    'status'            =>  $item->status,
                    'created_at'        =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
