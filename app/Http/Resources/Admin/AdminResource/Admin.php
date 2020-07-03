<?php

namespace App\Http\Resources\Admin\AdminResource;

use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource
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
            'id'                    =>  $this->id,
            'username'              =>  $this->username,
            'nickname'              =>  $this->nickname,
            'avatar'                =>  $this->avatar,
            'role_id'               =>  $this->roles->map(function($roleItem){
                                        return $roleItem->id;
                                    }),
            // 'ip'                    =>  $this->ip,
            // 'login_time'            =>  $this->login_time,
            // 'last_login_time'       =>  $this->last_login_time,
            // 'created_at'            =>  $this->created_at->format('Y-m-d H:i:s'),
            // 'updated_at'            =>  $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
