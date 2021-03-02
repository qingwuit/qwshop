<?php

namespace App\Http\Resources\Seller\SeckillResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class SeckillResource extends JsonResource
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
            'id'                    =>  $this->id,
            'goods_id'              =>  $this->goods_id,
            'discount'              =>  $this->discount,
            'start_time'            =>  $this->start_time->format('Y-m-d H:i:s'),
            'end_time'              =>  $this->end_time->format('Y-m-d H:i:s'),
            'goods_info'            =>  [
                                            'id'                        =>  $this->goods->id,
                                            'goods_name'                =>  $this->goods->goods_name,
                                            'goods_master_image'        =>  $this->thumb($this->goods->goods_master_image,150),
                                        ],
        ];
    }
}
