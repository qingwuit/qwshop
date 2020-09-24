<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\OrderCommentResource\OrderCommentCollection;
use App\Http\Resources\Seller\OrderCommentResource\OrderCommentResource;
use App\Models\OrderComment;
use App\Services\OrderCommentService;
use Illuminate\Http\Request;

class OrderCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $store_id = $this->get_store(true);
        $oc_model = new OrderComment();
        $list = $oc_model->where('store_id',$store_id)->paginate($request->per_page??30);
        return $this->success(new OrderCommentCollection($list));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $oc_model = new OrderComment();
        $list = $oc_model->with(['goods'=>function($q){
            return $q->select('id','goods_name','goods_master_image');
        }])->where('store_id',$this->get_store(true))->where('id',$id)->first();
        return $this->success(new OrderCommentResource($list));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $oc_service = new OrderCommentService();
        $rs = $oc_service->edit($id,'seller');
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }


}
