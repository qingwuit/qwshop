<?php

namespace App\Http\Resources\Chat\ChatFriendResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatFriendCollection extends ResourceCollection
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
                    'user_id'                   =>  $item->user->id,
                    'avatar'                    =>  $item->user->avatar,
                    'nickname'                  =>  $item->user->nickname,
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
