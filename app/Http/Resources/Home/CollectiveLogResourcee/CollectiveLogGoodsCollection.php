<?php

namespace App\Http\Resources\Home\CollectiveLogResourcee;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectiveLogGoodsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item){
            return [
                'id'                        =>  $item->id,
                'need'                      =>  $item->need,
                'nickname'                  =>  $item->user->nickname,
                'orders_count'              =>  $item->orders_count,
            ];
        });
    }
}
