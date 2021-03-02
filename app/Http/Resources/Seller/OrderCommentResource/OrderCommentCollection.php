<?php

namespace App\Http\Resources\Seller\OrderCommentResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    use HelperTrait;
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function($item) {
                return [
                    'id'                            =>  $item->id,
                    'score'                         =>  $item->score,
                    'agree'                         =>  $item->agree,
                    'service'                       =>  $item->service,
                    'speed'                         =>  $item->speed,
                    'goods'                         =>  [
                                                            'id'                    =>  $item->goods->id??0,
                                                            'goods_name'            =>  $item->goods->goods_name??__('goods.goods_not_found'),
                                                            'goods_master_image'    =>  empty($item->goods)?'':$this->thumb($item->goods->goods_master_image,150),
                                                        ],
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
