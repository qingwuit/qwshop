<?php

namespace App\Http\Resources\Chat\ChatMsgResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SellerChatMsgCollection extends ResourceCollection
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
                    'content'                   =>  $item->content,
                    'type'                      =>  $item->type,
                    'send_type'                 =>  $item->send_type,
                    'store_read'                =>  $item->store_read,
                    'user_read'                 =>  $item->user_read,
                    'nickname'                  =>  $item->user->nickname,
                    'avatar'                    =>  $item->user->avatar,
                    'store_name'                =>  $item->store->store_name,
                    'store_logo'                =>  $item->store->store_logo,
                    'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
