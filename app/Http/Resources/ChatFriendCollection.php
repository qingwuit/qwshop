<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class ChatFriendCollection extends ResourceCollection
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
                'id'                        =>  $item->id,
                'sid'                       =>  $item->sid,
                'stype'                     =>  $item->stype,
                'sinfo'                     =>  $item->stype != 'anonymous' ? DB::table($item->stype)->select('nickname','avatar')->find($item->sid) : ['nickname'=>'anonymous'],
                'rid'                       =>  $item->rid,
                'rtype'                     =>  $item->rtype,
                'rinfo'                     =>  $item->rtype != 'anonymous' ? DB::table($item->rtype)->select('nickname','avatar')->find($item->rid) : ['nickname'=>'anonymous'],
                'lastMsg'                   =>  DB::table('chat_contents')->select('content','s_read','r_read','content_type')->orWhere(function ($q) use ($item) {
                                                    return $q->where('sid', $item->sid)->where('stype', $item->stype)->where('rid', $item->rid)->where('rtype', $item->rtype);
                                                })->orWhere(function ($q) use ($item) {
                                                    return $q->where('rid', $item->sid)->where('rtype', $item->stype)->where('rid', $item->sid)->where('rtype', $item->stype);
                                                })->orderBy('created_at', 'desc')->first(),
                'created_at'                =>  $item->created_at->format('Y-m-d H:i:s'),
                'updated_at'                =>  $item->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
