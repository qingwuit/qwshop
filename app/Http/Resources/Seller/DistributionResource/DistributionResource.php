<?php

namespace App\Http\Resources\Seller\DistributionResource;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributionResource extends JsonResource
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
            'lev_1'                 =>  $this->lev_1,
            'lev_2'                 =>  $this->lev_2,
            'lev_3'                 =>  $this->lev_3,
            'goods_id'              =>  $this->goods_id,
            'goods_info'            =>  [
                                            'id'                        =>  $this->goods->id,
                                            'goods_name'                =>  $this->goods->goods_name,
                                            'goods_master_image'        =>  $this->thumb($this->goods->goods_master_image,150),
                                        ],
        ];
    }
}
