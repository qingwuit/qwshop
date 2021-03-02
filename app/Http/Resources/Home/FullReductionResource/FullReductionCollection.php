<?php

namespace App\Http\Resources\Home\FullReductionResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FullReductionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
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
            ];
        });
    }
}
