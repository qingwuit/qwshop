<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    protected $modelName = 'AdminPermission';

    // 获取所有路由权限接口
    public function loadPermission()
    {
        $routes = $this->getService('base')->getRoutes(); // 'api/Admin' | api/Seller
        return $this->handle($routes);
    }
}
