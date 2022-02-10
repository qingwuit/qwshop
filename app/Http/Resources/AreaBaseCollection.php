<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AreaBaseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id'                    =>  $item->id,
                'code'                  =>  $item->code,
                'pid'                   =>  $item->pid,
                'deep'                  =>  $item->deep,
                'name'                  =>  $item->name,
                'leaf'                  =>  (isset($item->hasChildren) && !empty($item->hasChildren))?false:true,
            ];
        });
    }
}
