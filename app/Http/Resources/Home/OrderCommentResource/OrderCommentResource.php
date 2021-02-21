<?php

namespace App\Http\Resources\Home\OrderCommentResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    use HelperTrait;
    public function toArray($request)
    {
        return [
            'id'                            =>  $this->id,
            'score'                         =>  $this->score,
            'agree'                         =>  $this->agree,
            'service'                       =>  $this->service,
            'speed'                         =>  $this->speed,
            'goods'                         =>  [
                                                    'id'                    =>  $this->goods->id,
                                                    'goods_name'            =>  $this->goods->goods_name??__('goods.goods_not_found'),
                                                    'goods_master_image'    =>  empty($this->goods)?'':$this->thumb($this->goods->goods_master_image,150),
                                                ],
            'content'                       =>  $this->content,
            'image'                         =>  empty($this->image)?[]:explode(',',$this->image),
            'reply'                         =>  $this->reply,
            'reply_time'                    =>  $this->reply_time,
            'created_at'                    =>  $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
