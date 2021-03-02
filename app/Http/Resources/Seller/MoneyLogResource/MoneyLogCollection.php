<?php

namespace App\Http\Resources\Seller\MoneyLogResource;

use App\Traits\ResourceTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MoneyLogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    use ResourceTrait;
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item) {
                return [
                    'id'                    =>  $item->id,
                    'name'                  =>  $item->name,
                    'money'                 =>  $item->money,
                    'is_type'               =>  $item->is_type,
                    'info'                  =>  $item->info,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            // 'data'=>$this->collection,
            'store_info'=>$this->get_store(false,'store_money,store_frozen_money'),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
