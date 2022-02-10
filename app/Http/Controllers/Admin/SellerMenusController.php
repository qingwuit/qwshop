<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerMenusController extends Controller
{
    protected $modelName = 'UserMenu';
    protected $auth = 'admins';
    protected $isSuper = false;
    
    // 获取前端菜单
    public function loadMenu(Request $request)
    {
        $treeType = $request->type??'getChildren';
        $deep = $request->deep??-1;
        // if(!auth($this->auth)->check()) return $this->auto(405,'No Login.',[]);

        // 配置是否超级管理员
        $this->isSuper = $this->getSuper($this->auth);
        $data = [];
        if ($this->isSuper) {
            $data = $this->getService('UserMenu', true)->select('id', 'name', 'ename', 'apis', 'view', 'pid', 'is_open', 'icon')->orderBy('is_sort', 'asc')->get();
        } else {
            $roles = $this->getRoles($this->auth, ['menus']);
            if (isset($data['menus']) && !empty($data['menus'])) {
                $data = $roles['menus'];
            }
        }
        
        try {
            $tree = $this->getService('Tool')->$treeType($data, 0, 0, $deep);
        } catch (\Exception $e) {
            return $this->error(__('tip.failed'));
        }
        return $this->success($tree);
    }
}
