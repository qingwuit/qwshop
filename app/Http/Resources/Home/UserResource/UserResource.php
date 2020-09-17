<?php

namespace App\Http\Resources\Home\UserResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'nickname'          =>  $this->nickname,
            'username'          =>  $this->username,
            'avatar'            =>  $this->avatar,
            'sex'               =>  $this->sex,
            'phone'             =>  $this->phone,
            'money'             =>  $this->money,
            'frozen_money'      =>  $this->frozen_money,
            'integral'          =>  $this->integral,
            'inviter_id'        =>  $this->inviter_id,
            'completion'        =>  $this->completion,
            'user_check'        =>  $this->user_check,
            'wechat_check'      =>  $this->wechat_check,
        ];
    }
}
