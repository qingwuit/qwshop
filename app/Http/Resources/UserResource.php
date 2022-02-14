<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                =>  $this->id,
            'nickname'          =>  $this->nickname,
            'username'          =>  $this->username,
            'avatar'            =>  $this->avatar,
            'sex'               =>  $this->sex,
            'phone'             =>  $this->phone,
            'money'             =>  $this->money,
            'frozen_money'      =>  $this->frozen_money,
            'integral'          =>  $this->integral,
            'inviter_id'        =>  $this->inviter_id,
            'user_check'        =>  $this->user_check,
            'wechat_check'      =>  $this->wechat_check,
        ];
    }
}
