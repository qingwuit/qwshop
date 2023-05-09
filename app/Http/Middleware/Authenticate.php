<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Qingwuit\Traits\ResourceTrait;

class Authenticate extends Middleware
{
    use ResourceTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    // 重新定义未认证用户返回信息
    protected function unauthenticated($request, array $guards)
    {
        throw new \Exception(__('tip.loginInv'));
    }

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);
        } catch (\Exception $th) {
            return response()->json(['code' => 401, 'msg' => $th->getMessage(), 'data' => []]);
        }

        // 判断如果有配置.ENV 文件 可以关掉接口权限 设置为false
        if(!env('PERMISSION',true)) return $next($request);

        // 判断是否有权限访问
        try {
            $thrMsg = ['code' => 403, 'msg' => __('tip.pmsThr'), 'data' => []];
            $hasPermission = false;
            $act = $request->route()->getAction();
            $auth = explode(':', $act['middleware'][1])[1];

            if ($auth == 'users') return $next($request);
            if ($this->getService('base')->getSuper($auth)) $hasPermission = true; // 超级管理员拥有所有权限

            $roles = $this->getService('base')->getRoles($auth, ['permissions']);
            if (empty($roles['roles']) && !$hasPermission) {
                return response()->json($thrMsg);
            }
            if (isset($roles['roles']['permissions'])) {
                foreach ($roles['roles']['permissions'] as $v) {
                    if ($v['apis'] === $act['as']) $hasPermission = true;
                }
            }
            if (!$hasPermission) return response()->json($thrMsg);
        } catch (\Exception $th) {
            return response()->json($thrMsg);
        }

        return $next($request);
    }
}
