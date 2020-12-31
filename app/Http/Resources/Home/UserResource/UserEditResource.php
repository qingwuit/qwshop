<?php

namespace App\Http\Resources\Home\UserResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserEditResource extends JsonResource
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
            'nickname'  =>  $this->nickname,
            'sex'       =>  $this->sex,
            'avatar'    =>  $this->avatar,
        ];
    }
}
