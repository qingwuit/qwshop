<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\OrderCommentResource\OrderCommentCollection;
use App\Http\Resources\Home\OrderCommentResource\OrderCommentResource;
use App\Models\OrderComment;
use App\Services\OrderCommentService;
use App\Services\UploadService;
use App\Services\UserService;
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
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        $oc_model = new OrderComment();
        $list = $oc_model->with(['goods'=>function($q){
            return $q->select('id','goods_name','goods_master_image');
        }])->where('user_id',$user_info['id'])->paginate($request->per_page??30);
        return $this->success(new OrderCommentCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $oc_service = new OrderCommentService();
        $rs = $oc_service->add();
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        $oc_model = new OrderComment();
        $list = $oc_model->with(['goods'=>function($q){
            return $q->select('id','goods_name','goods_master_image');
        }])->where('user_id',$user_info['id'])->where('id',$id)->first();
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
        $rs = $oc_service->edit($id);
        return $rs['status']?$this->success($rs['data'],$rs['msg']):$this->error($rs['msg']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // 图片上传
    public function comment_upload(UploadService $upload_service){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        $rs = $upload_service->comment($user_info['id']);
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
