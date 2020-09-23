<?php

namespace App\Http\Resources\Seller\OrderCommentResource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
