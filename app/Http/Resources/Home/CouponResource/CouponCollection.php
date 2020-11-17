<?php

namespace App\Http\Resources\Home\CouponResource;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->map(function($item){
            return [
                'id'                        =>  $item->id,
                'money'                     =>  intval($item->money),
                'use_money'                 =>  intval($item->use_money),
                'name'                      =>  $item->name,
                'content'                   =>  $item->content,
            ];
        });
    }
}
