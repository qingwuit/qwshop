<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowHomeCollection extends ResourceCollection
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
                    'id'                    =>  $item->id,
                    'out_id'                =>  $item->out_id,
                    'is_type'               =>  $item->is_type,
                    'store_name'            =>  $item->store->store_name??'-',
                    'store_logo'            =>  $item->store->store_logo??'',
                    'area_info'             =>  $item->store->area_info??'-',
                    'created_at'            =>  empty($this->created_at)?now()->format('Y-m-d H:i:s'):$this->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            // 'data'=>$this->collection,
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
