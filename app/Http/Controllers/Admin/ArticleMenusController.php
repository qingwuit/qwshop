<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleMenusController extends Controller
{
    protected $modelName = 'ArticleMenu';
    protected $setUser = true;

    // 获取前端菜单
    public function loadMenu(Request $request)
    {
        $treeType = $request->type??'getChildren';
        $deep = $request->deep??-1;
        // if(!auth($this->auth)->check()) return $this->auto(405,'No Login.',[]);

        // 配置是否超级管理员
        $this->isSuper = $this->getSuper($this->auth);
        $data = [];
        $data = $this->getService('ArticleMenu', true)->select('id', 'name', 'pid')->orderBy('is_sort', 'asc')->get();
  
        
        try {
            $tree = $this->getService('Tool')->$treeType($data, 0, 0, $deep);
        } catch (\Exception $e) {
            return $this->error(__('tip.failed'));
        }
        return $this->success($tree);
    }
}
