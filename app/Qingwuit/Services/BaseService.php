<?php

namespace App\Qingwuit\Services;

use Illuminate\Http\Request;
use App\Qingwuit\Traits\ResourceTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class BaseService
{
    use ResourceTrait;
    public $setUser = false; // 是否加上所属条件
    public $auth = 'admins';
    public $belongName = 'belong_id';
    public $whereExcept = ['per_page', 'total', 'page', 'last_page', 'current_page', 'count', 'isAll', 'isResource', 'isChildren', 'isWith']; // 非条件查询字段

    /**
     * 获取分页数据
     *
     * @param [type] $tableModel 模型名
     * @param array $where 条件
     * @param string $orderBy 排序
     * @return void
     * @Description
     * @Author hg <bishashiwo@gmail.com>
     * @Time 2021-11-03
     */
    public function getPageData($tableModelName = null, $where = [], $orderBy = 'id desc')
    {
        $pageSize = intval(request('per_page') ?? 30); // 默认页码数量
        $isAll = boolval(request('isAll') ?? false); // 是否获取所有数据
        if (!$tableModelName) return $this->formatError('select Model.');
        $tableModel = $this->getService($tableModelName, true);

        if ($this->setUser) $tableModel = $tableModel->where($this->belongName, $this->getBelongId($this->auth)); // 是否根据所属ID来
        if ($where) $tableModel = $tableModel->where($where);
        if ($orderBy) $tableModel = $tableModel->orderByRaw($orderBy);

        // 条件查询
        $requestAll = request()->all();
        if (!empty($requestAll)) {
            foreach ($requestAll as $k => $v) {
                if (!in_array($k, $this->whereExcept)) {
                    if (is_array($v)) { // 如果是数组默认区间查询
                        $tableModel = $tableModel->where($k, '>=', $v[0])->where($k, '<', $v[1]);
                        continue;
                    }
                    $vArr = explode('|', $v); // 判断是否其他查询
                    if (count($vArr) >= 2) {
                        if ($vArr[1] == 'like') {
                            $tableModel = $tableModel->where($k, 'like', '%' . $vArr[0] . '%');
                        }
                        if ($vArr[1] == 'likeRight') {
                            $tableModel = $tableModel->where($k, 'like', $vArr[0] . '%');
                        }
                        if ($vArr[1] == 'likeLeft') {
                            $tableModel = $tableModel->where($k, 'like', '%' . $vArr[0]);
                        }
                        if ($vArr[1] == 'gt') {
                            $tableModel = $tableModel->where($k, '>', $vArr[0]);
                        }
                        if ($vArr[1] == 'lt') {
                            $tableModel = $tableModel->where($k, '<', $vArr[0]);
                        }
                        if ($vArr[1] == 'ngt') {
                            $tableModel = $tableModel->where($k, '>=', $vArr[0]);
                        }
                        if ($vArr[1] == 'nlt') {
                            $tableModel = $tableModel->where($k, '<=', $vArr[0]);
                        }
                        if ($vArr[1] == 'in') {
                            $tableModel = $tableModel->whereIn($k, explode(',', $vArr[0]));
                        }
                        if ($vArr[1] == 'between') {
                            $tableModel = $tableModel->whereBetween($k, explode(',', $vArr[0]));
                        }
                        if ($vArr[1] == 'whereHas') {
                            // 如果有whereHas则删掉 isWith
                            $isWith = request('isWith') ?? false;
                            if (!empty($isWith)) {
                                $withArr = explode(',', $isWith);
                                foreach ($withArr as $withKey => $withItem) {
                                    if ($withItem == $vArr[2]) {
                                        unset($withArr[$withKey]);
                                        break;
                                    }
                                }
                                request()->merge(['isWith' => implode(',', $withArr)]);
                            }
                            $tableModel = $tableModel->whereHas($vArr[2], function ($q) use ($k, $vArr) {
                                if (!isset($vArr[3])) return $q->where($k, 'like', '%' . $vArr[0] . '%');
                                $q->where($k, $vArr[3], $vArr[0]);
                            });
                        }
                    } else {
                        $tableModel = $tableModel->where($k, $v);
                    }
                }
            }
        }

        // 判断是否是获取子数据
        $isChildren = boolval(request('isChildren') ?? false);
        if ($isChildren) {
            $isAll = true;
            $belong_id = request('pid') ?? 0;
            $tableModel = $tableModel->where('pid', $belong_id)->with('hasChildren');
        }

        // 是否携带关联
        $isWith = request('isWith') ?? false;
        if (!empty($isWith)) {
            $withArr = explode(',', $isWith);
            $tableModel = $tableModel->with($withArr);
        }

        // 获取分页 | 获取全部
        if (!$isAll) {
            $pageData = $tableModel->paginate($pageSize);
        } else {
            $pageData = $tableModel->get();
        }

        // 判断是否存在资源文件
        $data = $pageData;
        if (!empty(request('isResource'))) $tableModelName .=  request('isResource'); // 自定义资源文件
        if ($isAll) $tableModelName .= 'All';
        $hasResource = $this->hasResource($pageData, $tableModelName);
        if ($hasResource) $data = $hasResource;

        return $this->format($data);
    }

    /**
     * storeData function
     *
     * @param [type] $tableModel 模型名
     * @param array $column post 字段
     * @return void
     * @Description
     * @Author hg <bishashiwo@gmail.com>
     * @Time 2021-11-04
     */
    public function addDat($tableModelName = null, $column = [])
    {
        $data = empty($column) ? request()->all() : request()->only($column);
        if (!$tableModelName) {
            return $this->formatError('select Model.');
        }
        $tableModel = $this->getService($tableModelName, true);

        if ($this->setUser) {
            $data[$this->belongName] = $this->getBelongId($this->auth);
        } // 是否根据所属ID来

        // 如果是密码之类的一律使用hash加密存储
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if (isset($data['pay_password']) && !empty($data['pay_password'])) {
            $data['pay_password'] = Hash::make($data['pay_password']);
        }

        $rs = $tableModel->create($data);
        if (!$rs) {
            return $this->formatError('insert data error.');
        }
        return $this->format($rs);
    }

    /**
     * storeData function
     *
     * @param [type] $tableModel 模型名
     * @param array $column post 字段
     * @return void
     * @Description
     * @Author hg <bishashiwo@gmail.com>
     * @Time 2021-11-04
     */
    public function editDat($tableModelName = null, $id = 0, $column = [], $where = [], $keyName = 'id')
    {
        $data = empty($column) ? request()->all() : request()->only($column);
        if (!$tableModelName) {
            return $this->formatError('select Model.');
        }
        $tableModel = $this->getService($tableModelName, true);
        if (!empty($where)) {
            $tableModel = $tableModel->where($where);
        }

        if ($this->setUser) {
            $data[$this->belongName] = $this->getBelongId($this->auth);
        } // 是否根据所属ID来

        // 如果是密码之类的一律使用hash加密存储
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if (isset($data['pay_password']) && !empty($data['pay_password'])) {
            $data['pay_password'] = Hash::make($data['pay_password']);
        }
        unset($data['created_at'], $data['updated_at'], $data['deleted_at']);

        $rs = $tableModel->where($keyName, intval($id))->update($data);
        if (!$rs) {
            return $this->formatError('edit data error.');
        }
        return $this->format($rs);
    }

    public function findDat($tableModelName = null, $id = 0, $where = [], $keyName = 'id')
    {
        if (!$tableModelName) {
            return $this->formatError('select Model.');
        }
        $tableModel = $this->getService($tableModelName, true);

        if ($this->setUser) {
            $tableModel = $tableModel->where($this->belongName, $this->getBelongId($this->auth));
        } // 是否根据所属ID来

        if (!empty($where)) {
            $tableModel = $tableModel->where($where);
        }

        // 是否携带关联
        // $isWith = request('isWith')??false;
        // if(!empty($isWith)){
        //     $withArr = explode(',',$isWith);
        //     $tableModel = $tableModel->with($withArr);
        // }

        $rs = $tableModel->where($keyName, intval($id))->first();
        if (!$rs) {
            return $this->formatError('find data error.');
        }

        // 判断是否存在资源文件
        $data = $rs;
        if (!empty(request('isResource'))) {
            $tableModelName .=  request('isResource');
        }
        $hasResource = $this->hasResource($rs, $tableModelName, 1);
        if ($hasResource) {
            $data = $hasResource;
        }
        return $this->format($data);
    }

    public function delDat($tableModelName = null, $id = 0, $where = [], $keyName = 'id')
    {
        if (!$tableModelName) {
            return $this->formatError('select Model.');
        }
        $tableModel = $this->getService($tableModelName, true);
        if (!empty($where)) {
            $tableModel = $tableModel->where($where);
        }
        $idArray = array_filter(explode(',', $id), function ($item) {
            return is_numeric($item);
        });

        if ($this->setUser) {
            $tableModel = $tableModel->where($this->belongName, $this->getBelongId($this->auth));
        } // 是否根据所属ID来

        $rs = $tableModel->whereIn($keyName, $idArray)->delete();
        if (!$rs) {
            return $this->formatError('find data error.');
        }
        return $this->format($rs);
    }

    // 配置属性
    public function setUserConfig($data = ['setUser' => false, 'auth' => 'admins', 'belongName' => 'belong_id'])
    {
        if (isset($data['setUser'])) {
            $this->setUser = $data['setUser'];
        }
        if (isset($data['auth'])) {
            $this->auth = $data['auth'];
        }
        if (isset($data['belongName'])) {
            $this->belongName = $data['belongName'];
        }
        return $this;
    }

    // 获取路由信息
    public function getRoutes($auth = "")
    {
        if (empty($auth)) {
            $uris = explode('/', request()->route()->uri);
            $auth = $uris[0] . '/' . $uris[1];
        }
        $routeList = Route::getRoutes();
        $routeListFormat = [];
        $routeApis = [];
        foreach ($routeList as $v) {
            if ($v->action['prefix'] != $auth || !isset($v->action['as'])) {
                continue;
            }
            $items = [];
            $items['as'] = $v->action['as'];
            $items['controller'] = $v->action['controller'];
            $items['uri'] = $v->uri;
            $asArr = explode('.', $items['as']);
            $InterfaceName = $asArr[0] . '-';
            switch ($asArr[1]) {
                case 'index':
                    $InterfaceName = $asArr[0] . '-' . __('tip.permission.index');
                    break;
                case 'store':
                    $InterfaceName = $asArr[0] . '-' . __('tip.permission.store');
                    break;
                case 'show':
                    $InterfaceName = $asArr[0] . '-' . __('tip.permission.view');
                    break;
                case 'update':
                    $InterfaceName = $asArr[0] . '-' . __('tip.permission.update');
                    break;
                case 'destroy':
                    $InterfaceName = $asArr[0] . '-' . __('tip.permission.destroy');
                    break;
            }
            $items['Interface_name'] = $InterfaceName;
            $routeApis[] = $items['as'];
            $routeListFormat[] = $items;
        }
        // return $this->format(['routeApis'=>$routeApis,'routeListFormat'=>$routeListFormat]);
        return $this->format($routeApis);
    }

    // 获取用户信息
    public function getUser($auth = 'admins')
    {
        try {
            $info = auth($auth)->user();
            if ($auth == 'users') {
                try {
                    $info['check'] = $this->getService('UserCheck', true)->where('user_id', $info->id)->exists();
                } catch (\Exception $e) {
                }
            }
            return $this->format($info);
        } catch (\Exception $e) {
            throw new \Exception(__('tip.userThr'));
        }
    }

    // 获取用户ID
    public function getUserId($auth = 'admins')
    {
        try {
            $id = auth($auth)->id();
            if ($id === null) {
                throw new \Exception(__('tip.userThr'));
            }
            return $id;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // 获取belong_id 即所属ID
    public function getBelongId($auth = 'admins')
    {
        try {
            $info = auth($auth)->user();
            return empty($info['belong_id']) ? $info['id'] : $info['belong_id'];
        } catch (\Exception $th) {
            throw new \Exception(__('tip.userThr'));
        }
    }

    // 是否超级管理员
    public function getSuper($auth = 'admins')
    {
        try {
            $info = auth($auth)->user();
            if ($auth != 'admins') {
                return !empty($info['belong_id']) ? false : true;
            }
            return empty($info) || empty($info['is_super']) ? false : true;
        } catch (\Exception $th) {
            throw new \Exception(__('tip.userThr'));
        }
    }

    // 获取当前用户的角色信息
    public function getRoles($auth = 'admins', $with)
    {
        try {
            $info = auth($auth)->user();
            $modelName = 'Admin';
            if ($auth == 'admins') {
                $modelName = 'Admin';
            }
            if ($auth == 'users') {
                $modelName = 'User';
            }
            $user = $this->getService($modelName, true)->with('roles')->find($info['id']);
            $roleId = [];
            if (isset($user['roles']) && !empty($user['roles'])) {
                foreach ($user['roles'] as $v) {
                    $roleId[] = $v['id'];
                }
            }
            $roles = $this->getService($modelName . 'Role', true)->with($with)->whereIn('id', empty($roleId) ? [0] : $roleId)->get()->toArray();
            // 去重
            $rolesData = ['menus' => [], 'permissions' => []];
            if ($roles) {
                foreach ($roles as $v) {
                    if (isset($v['menus']) && !empty($v['menus'])) {
                        $rolesData['menus'] = array_merge($rolesData['menus'], $v['menus']);
                    }
                    if (isset($v['permissions']) && !empty($v['permissions'])) {
                        $rolesData['permissions'] = array_merge($rolesData['permissions'], $v['permissions']);
                    }
                    $rolesData['roleName'][] = $v['name'];
                }

                if (isset($rolesData['menus']) && !empty($rolesData['menus'])) {
                    foreach ($rolesData['menus'] as $k => $v) {
                        $rolesData['menus'][$k] = json_encode($v);
                    }
                    $rolesData['menus'] = array_unique($rolesData['menus']);
                    foreach ($rolesData['menus'] as $k => $v) {
                        $rolesData['menus'][$k] = json_decode($v, true);
                    }
                }
                if (isset($rolesData['permissions']) && !empty($rolesData['permissions'])) {
                    foreach ($rolesData['permissions'] as $k => $v) {
                        $rolesData['permissions'][$k] = json_encode($v);
                    }
                    $rolesData['permissions'] = array_unique($rolesData['permissions']);
                    foreach ($rolesData['permissions'] as $k => $v) {
                        $rolesData['permissions'][$k] = json_decode($v, true);
                    }
                }
            }
            return ['roleId' => $roleId, 'roles' => $rolesData];
        } catch (\Exception $th) {
            throw new \Exception(__('tip.userThr') . $th->getMessage());
        }
    }
}
