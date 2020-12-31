<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;

class PermissionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,PermissionGroup $permission_group_model)
    {
        $list = $permission_group_model->with(['permissions'=>function($q){
            return $q->select('id','pid','name','apis');
        }])->orderBy('id','desc')->paginate($request->per_page??30);
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,PermissionGroup $permission_group_model)
    {
        $permission_group_model->name = $request->name??'';;
        $permission_group_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PermissionGroup $permission_group_model,$id)
    {
        $info = $permission_group_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,PermissionGroup $permission_group_model, $id)
    {
        $permission_group_model = $permission_group_model->find($id);
        $permission_group_model->name = $request->name??'';
        $permission_group_model->save();
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermissionGroup $permission_group_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $permission_group_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }
}
