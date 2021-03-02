<?php

namespace App\Http\Resources\Home\ArticleResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'id'                            =>  $this->id,
            'name'                          =>  $this->name,
            'ename'                         =>  $this->ename,
            'content'                       =>  $this->content,
            'click_num'                     =>  $this->click_num,
            'updated_at'                    =>  $this->updated_at->format('Y-m-d H:i:s'),
            // 'created_at'                    =>  $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
