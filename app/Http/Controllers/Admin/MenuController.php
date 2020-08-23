<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Services\MenuService;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu_service = new MenuService;
        $list = $menu_service->getMenus()['data'];
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Menu $menu_model)
    {
        $menu_model->pid = $request->pid??0;
        $menu_model->name = $request->name;
        $menu_model->ename = $request->ename??'';
        $menu_model->icon = $request->icon??'';
        $menu_model->link = $request->link??'';
        $menu_model->is_type = $request->is_type??0;
        $menu_model->save();
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu_model,$id)
    {
        $info = $menu_model->find($id);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Menu $menu_model, $id)
    {
        $menu_model = $menu_model->find($id);
        $menu_model->pid = $request->pid??0;
        $menu_model->name = $request->name;
        $menu_model->ename = $request->ename??'';
        $menu_model->icon = $request->icon??'';
        $menu_model->link = $request->link??'';
        $menu_model->is_type = $request->is_type??0;
        $menu_model->is_sort = $request->is_sort??0;
        $menu_model->save();
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $menu_model->destroy($idArray);
        $this->clear_cache(); // 修改则清空缓存
        return $this->success([],__('base.success'));
    }

    // 清除菜单缓存
    public function clear_cache(){
        $menu_service = new MenuService;
        $menu_service->clearCache();
        return $this->success([],__('base.success'));
    }
}
