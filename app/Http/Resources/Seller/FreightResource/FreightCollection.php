<?php

namespace App\Http\Resources\Seller\FreightResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FreightCollection extends ResourceCollection
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
                'name'                      =>  $item->name,
                'f_weight'                  =>  floatval($item->f_weight),
                'f_price'                   =>  floatval($item->f_price),
                'o_weight'                  =>  floatval($item->o_weight),
                'o_price'                   =>  floatval($item->o_price),
                'area_id'                   =>  empty($item->area_id)?[]:explode(',',$item->area_id),
                'area_show'                 =>  false,
            ];
        });
    }
}
