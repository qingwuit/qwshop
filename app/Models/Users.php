<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\MoneyLog;

class Users extends Authenticatable implements JWTSubject
{
    protected $table = "users"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    protected $fillable = ['username','password','add_time'];

     use Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // 关联到角色表
    public function roles(){
    	return $this->belongsToMany('App\Models\Roles','user_link_role','user_id','role_id');
    }

    // 资金变更
    /**
     * money_change function
     *
     * @param [type] $name  // 类型  如：订单支付  资金冻结
     * @param [type] $type  // 修改字段 如：money  freeze_money  integral
     * @param [type] $money  // 金额 10   -10
     * @param array $user_info  // 用户信息
     * @param string $info  // 描述
     * @param string $pay_type // 支付类型  余额支付
     * @return void
     * @Description  用户资金变更
     * @author hg <www.qingwuit.com>
     */
    public function money_change($name,$type,$money,$user_info=[],$info='',$pay_type='余额支付'){
        $money_log_model = new MoneyLog();

        // 没有传用户信息直接取 当前用户
        if(empty($user_info)){
            $user_info = auth()->user();
        }

        $make_rand = make_rand();

        // 资金变更日志
        $log_data = [
            'log_no'        =>  $make_rand,
            'user_id'       =>  $user_info['id'],
            'nickname'      =>  $user_info['nickname'],
            'name'          =>  $name,
            $type           =>  $money,
            'pay_type'      =>  $pay_type,
            'info'          =>  $info,
        ];

        if($money>0){
            $rs = $this->where('id',$user_info['id'])->increment($type,abs($money));
        }else{
            $rs = $this->where('id',$user_info['id'])->decrement($type,abs($money));
        }

        if($rs){
            $money_log_model->addLog($log_data); // 插入日志
            return true;
        }else{
            return false;
        }


    }

    public function register($data){
        return $this->insert($data);
    }

}
