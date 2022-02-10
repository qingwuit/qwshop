<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressHomeCollection extends ResourceCollection
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
                    'receive_name'          =>  $item->receive_name,
                    'receive_tel'           =>  $item->receive_tel,
                    'area_info'             =>  $item->area_info,
                    'address'               =>  $item->address,
                    'province_id'           =>  $item->province_id,
                    'city_id'               =>  $item->city_id,
                    'region_id'             =>  $item->region_id,
                    'is_default'            =>  $item->is_default==1?true:false,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
