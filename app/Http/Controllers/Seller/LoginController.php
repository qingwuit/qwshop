<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Store;

class LoginController extends BaseController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['phone', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => '账号或密码错误'], 401);
        }
        
        $user_info = auth()->user();
        $store_model = new Store;
        
        $store_info = $store_model->select('user_id','store_status','store_verify')->where('user_id',$user_info['id'])->first();

        if(empty($store_info)){
            auth()->logout();
            return $this->error_msg('非商家账号不能登入商家后台！');
        }

        if($store_info['store_status'] != 1){
            auth()->logout();
            return $this->error_msg('店铺已经关闭，请联系工作人员');
        }

        if($store_info['store_verify'] != 1){
            auth()->logout();
            return $this->error_msg('店铺正在审核，或审核失败，请联系工作人员');
        }

        unset($user_info['password']); // 去掉存储的密码

        return response()->json([
            'code'=>200,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user_info' => $user_info,
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['code'=>200,'msg' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'code'=>200,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    // 检测是否已经登录
    public function check_user_login(Request $req){
        if(auth()->parser()->setRequest($req)->hasToken()){
            return $this->success_msg();
        }else{
            return $this->error_msg('Token Not Provided Or Other Error');
        }
    }

    
}
