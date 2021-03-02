<?php

namespace App\Http\Resources\Admin\AdvResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AdvCollection extends ResourceCollection
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
                    'id'                    =>  $item->id,
                    'ap_id'                 =>  $item->ap_id,
                    'name'                  =>  $item->name,
                    'url'                   =>  $item->url,
                    'image_url'             =>  $item->image_url,
                    'adv_start'             =>  $item->adv_start,
                    'adv_end'               =>  $item->adv_end,
                    'adv_sort'              =>  $item->adv_sort,
                    'adv_type'              =>  $item->adv_type,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            =>  $item->updated_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
