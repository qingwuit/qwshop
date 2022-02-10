<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    protected $modelName = 'UserRole';
    protected $auth = 'users';

    // 添加角色
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $model = $this->getService($this->modelName, true)->where($this->belongName, $this->getBelongId())->create(['name'=>$request->name??'',$this->belongName=>$this->getUserId()]);
            $model->menus()->sync($request->menu_id??[]);
            $model->permissions()->sync($request->permission_id??[]);
            DB::commit();
            return $this->success([], __('tip.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 显示
    public function show($id)
    {
        $rs = $this->getService($this->modelName, true)->where($this->belongName, $this->getBelongId())->with(['menus','permissions'])->find($id);
        return $this->success($rs, __('tip.success'));
    }

    // 修改
    public function update(Request $request, $id)
    {
        try {
            $belongName = $this->belongName;
            $model = $this->getService($this->modelName, true)->find($id);
            $model->name = $request->name;
            $model->$belongName = $this->getBelongId($this->auth);
            $model->save();
            $model->menus()->sync($request->menu_id??[]);
            $model->permissions()->sync($request->permission_id??[]);
            DB::commit();
            return $this->success([], __('tip.success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 删除
    public function destroy($id)
    {
        $idArray = array_filter(explode(',', $id), function ($item) {
            return (is_numeric($item)); // 超级管理员也不允许删除
        });
        foreach ($idArray as $v) {
            $model = $this->getService($this->modelName, true)->find($v);
            $model->menus()->detach();
            $model->permissions()->detach();
            $model->refresh();
        }

        $model->whereIn('id', $idArray)->where($this->belongName, $this->getBelongId($this->auth))->delete();
        return $this->success([], __('tip.success'));
    }
}
