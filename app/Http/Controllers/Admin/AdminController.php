<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Admin $admin_model)
    {
        $list = $admin_model->with('roles')->paginate($request->per_page??30);
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Admin $admin_model)
    {
        $admin_model->username = $request->username;
        $admin_model->password = Hash::make($request->password??'123456');
        $admin_model->nickname = $request->nickname??'神秘人';
        $admin_model->avatar = $request->avatar;
        $admin_model->save();
        $admin_model->rolse()->sync($request->role_id??[]);
        return $this->success(__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin_model,$id)
    {
        $info = $admin_model->with('roles')->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Admin $admin_model, $id)
    {
        $admin_model->username = $request->username;
        if(!empty($request->password)){
            $admin_model->password = Hash::make($request->password??'123456');
        }
        $admin_model->nickname = $request->nickname??'神秘人';
        $admin_model->avatar = $request->avatar;
        $admin_model->save();
        $admin_model->rolse()->sync($request->role_id??[]);
        return $this->success(__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        foreach($idArray as $v){
            $admin_model->find($v);
            $admin_model->roles()->detach();
            $admin_model->refresh();
        }
        $admin_model->destroy($idArray);
        return $this->success(__('base.success'));
    }
}
