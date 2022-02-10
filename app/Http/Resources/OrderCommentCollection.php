<?php

namespace App\Http\Resources;

use App\Qingwuit\Services\ToolService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tool = new ToolService;
        return [
            'data'=>$this->collection->map(function ($item) use ($tool) {
                return [
                    'id'                            =>  $item->id,
                    'nickname'                      =>  $item->user->nickname,
                    'avatar'                        =>  $item->user->avatar,
                    'goods_master_image'            =>  $tool->thumb($item->goods->goods_master_image),
                    'goods_name'                    =>  $item->goods->goods_name,
                    'score'                         =>  $item->score,
                    'agree'                         =>  $item->agree,
                    'service'                       =>  $item->service,
                    'speed'                         =>  $item->speed,
                    'content'                       =>  $item->content,
                    'image'                         =>  empty($item->image)?[]:explode(',', $item->image),
                    'reply'                         =>  $item->reply,
                    'reply_time'                    =>  $item->reply_time,
                    'created_at'                    =>  $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'=>$this->total(), // 数据总数
            'last_page'=>$this->lastPage(), // 最后页面
            'per_page'=>$this->perPage(), // 每页数量
            'current_page'=>$this->currentPage(), // 当前页码
        ];
    }
}
