<?php

namespace App\Http\Resources\Home\CashResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CashCollection extends ResourceCollection
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
                    'user_id'                   =>  $item->user_id,
                    'store_id'                  =>  $item->store_id,
                    'money'                     =>  $item->money,
                    'refuse_info'               =>  empty($item->refuse_info)?'-':$item->refuse_info,
                    'name'                      =>  $item->name,
                    'cash_status'               =>  $item->cash_status,
                    'card_no'                   =>  $item->card_no,
                    'bank_name'                 =>  $item->bank_name,
                    'remark'                    =>  empty($item->remark)?'-':$item->remark,
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
