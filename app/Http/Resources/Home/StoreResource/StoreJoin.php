<?php

namespace App\Http\Resources\Home\StoreResource;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreJoin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                        =>  $this->id,
            'store_verify'              =>  $this->store_verify,
            'store_refuse_info'         =>  $this->store_refuse_info,
        ];
    }
}
