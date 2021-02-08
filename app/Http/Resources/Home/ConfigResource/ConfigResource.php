<?php

namespace App\Http\Resources\Home\ConfigResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
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
            'web_name'                      =>  $this['web_name'],
            'title'                         =>  $this['title'],
            'keywords'                      =>  $this['keywords'],
            'description'                   =>  $this['description'],
            'logo'                          =>  $this['logo'],
            'icon'                          =>  $this['icon'],
            'mobile'                        =>  $this['mobile'],
            'email'                         =>  $this['email'],
            'icp'                           =>  $this['icp'],
            'web_status'                    =>  $this['web_status'],
            'web_close_info'                =>  $this['web_close_info'],
    
        ];
    }
}
