<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req,Permission $permission_model)
    {
        if(!empty($req->name)){
            $permission_model = $permission_model->where('name','like','%'.$req->name.'%');
        }
        if(!empty($req->apis)){
            $permission_model = $permission_model->where('apis','like','%'.$req->apis.'%');
        }
        if(!empty($req->pid)){
            $permission_model = $permission_model->where('pid',$req->pid);
        }
        $list = $permission_model->With('permission_group')->orderBy('id','desc')->paginate($req->per_page??30);
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Permission $permission_model)
    {
        $permission_model->pid = $request->pid;
        $permission_model->name = $request->name;
        $permission_model->apis = $request->apis;
        $permission_model->content = $request->content??'';
        $permission_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission_model,$id)
    {
        $info = $permission_model->find($id);
        return $this->success($info);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Permission $permission_model, $id)
    {
        $permission_model = $permission_model->find($id);
        $permission_model->pid = $request->pid;
        $permission_model->name = $request->name;
        $permission_model->apis = $request->apis;
        $permission_model->content = $request->content??'';
        $permission_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $permission_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
