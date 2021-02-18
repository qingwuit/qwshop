<?php

namespace App\Http\Resources\Home\DistributionResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DistributionUserCollection extends ResourceCollection
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
                    'nickname'              =>  $item->nickname,
                    'avatar'                =>  $item->avatar,
                    'login_time'            =>  $item->login_time,
                    'created_at'            =>  $item->created_at->format('Y-m-d H:i:s'),
                    'created_at_app'        =>  $item->created_at->format('Y-m-d'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
