<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderCommentResource\OrderCommentCollection;
use App\Http\Resources\Admin\OrderCommentResource\OrderCommentResource;
use App\Models\OrderComment;
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
        $oc_model = new OrderComment();
        $list = $oc_model->paginate($request->per_page??30);
        return $this->success(new OrderCommentCollection($list));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OrderComment $oc_model,$id)
    {
        $info = $oc_model->find($id);
        return $this->success(new OrderCommentResource($info));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderComment $oc_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
   
        $oc_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
