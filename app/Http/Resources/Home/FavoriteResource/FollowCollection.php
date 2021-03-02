<?php

namespace App\Http\Resources\Home\FavoriteResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowCollection extends ResourceCollection
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
                    'out_id'                =>  $item->out_id,
                    'store_name'            =>  $item->store->store_name??__('stores.store_not_defined'),
                    'store_logo'            =>  $item->store->store_logo??'',
                ];
            }),
            // 'data'=>$this->collection,
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
