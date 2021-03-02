<?php

namespace App\Http\Resources\Home\AdvResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AdvCollection extends ResourceCollection
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
                        'id'                    =>  $item->id,
                        'name'                  =>  $item->name,
                        'url'                   =>  $item->url,
                        'image_url'             =>  $item->image_url,
                        'adv_type'              =>  $item->adv_type,
                    ];
                });
    
    }
}
