<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

config(['auth.defaults.guard' => 'admin']);

class RefreshTokenAdmin extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 检查此次请求中是否带有 token，如果没有则抛出异常。 
        try{
            $this->checkForToken($request);
        } catch(UnauthorizedHttpException $e){
            return response()->json(['code'=>401,'msg'=>__('auth.no_token')]);
        }
        
        // 如果Token不能解析的情况下
        try{
            // 使用 try 包裹，以捕捉 token 过期所抛出的 TokenExpiredException  异常
            try {
                // 检测用户的登录状态，如果正常则通过
                // dd($this->auth->parseToken()->authenticate());
                if ($userInfo = $this->auth->parseToken()->authenticate()) {
                    // 是否有权限返回 
                    if(!$this->getPermission($userInfo)){
                        return response()->json(['code'=>402,'msg'=>__('auth.no_permission')]);
                    }
                    return $next($request);
                }
                return response()->json(['code'=>401,'msg'=>__('auth.no_token')]);
                // throw new UnauthorizedHttpException('jwt-auth', '未登录');
            } catch (TokenExpiredException $exception) {
            // 此处捕获到了 token 过期所抛出的 TokenExpiredException 异常，我们在这里需要做的是刷新该用户的 token 并将它添加到响应头中
                try {
                    // 刷新用户的 token
                    $token = $this->auth->refresh();
                // 使用一次性登录以保证此次请求的成功
                    Auth::guard('admin')->onceUsingId($this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);
                } catch (JWTException $exception) {
                // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                    return response()->json(['code'=>401,'msg'=>__('auth.no_token')]);
                    // throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
                }
            }
        }catch(TokenInvalidException $e){
            return response()->json(['code'=>401,'msg'=>__('auth.error_token')]);
        }
 

        // 在响应头中返回新的 token
        return $this->setAuthenticationHeader($next($request), $token);
        // return $next($request);
    }


    // 判断是否有权限
    protected function getPermission($userInfo){
        $id = $userInfo->id;
        $as = request()->route()->getAction()['as']??'not_name';
        if(env('APP_DEBUG') == true){
            return true;
        }
        $admin_model = new Admin();
        $role_model = new Role();

        try{
            $roleList = $admin_model->with('roles')->find($id)->toArray()['roles'];
            $roleId = [];
            foreach($roleList as $v){
                $roleId[] = $v['id'];
            }
            $roleList = $role_model->with('permissions')->whereIn('id',$roleId)->get();
            foreach($roleList as $v){
                foreach($v['permissions'] as $vo){
                    if($as == $vo['apis']){
                        return true;
                    }
                }
            }
        }catch(\Exception $e){
            return false;
        }
        return false;
    }
}
