<?php

namespace App\Http\Resources\Home\OrderCommentResource;

use App\Services\ToolService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsInfoCommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tool_service = new ToolService;
        return [
            'data'=>$this->collection->map(function($item) use($tool_service){
                return [
                    'id'                            =>  $item->id,
                    'user'                          =>  [
                                                            'nickname'=>$tool_service->formatTrueName2($item->user->nickname),
                                                            'avatar'=>$item->user->avatar,
                                                        ],
                    'score'                         =>  $item->score,
                    'agree'                         =>  $item->agree,
                    'service'                       =>  $item->service,
                    'speed'                         =>  $item->speed,
                    'content'                       =>  $item->content,
                    'image'                         =>  empty($item->image)?[]:explode(',',$item->image),
                    'reply'                         =>  $item->reply,
                    'reply_time'                    =>  $item->reply_time,
                    'created_at'                    =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            // 'data'=>$this->collection,
            'total'=>$this->total(), // 数据总数
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
