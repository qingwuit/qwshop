<?php

namespace App\Http\Resources\Home\UserResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCheckLogin extends JsonResource
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
            'id'            =>  $this->id,
            'nickname'      =>  $this->nickname,
            'money'         =>  $this->money,
            'avatar'        =>  $this->avatar,
            'frozen_money'  =>  $this->frozen_money,
            'integral'      =>  $this->integral,
        ];
    }
}
