<?php
namespace App\Services;

use App\Http\Resources\Home\FavoriteResource\FavoriteCollection;
use App\Http\Resources\Home\FavoriteResource\FollowCollection;
use App\Models\Favorite;

class FavoriteService extends BaseService{

    public function getFav(){
        $type = request()->is_type;
        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $fav_model = new Favorite();
        $fav_model = $fav_model->where(['user_id'=>$user_info['id'],'is_type'=>$type]);
        if($type==0){
            $fav_model = $fav_model->with(['goods'=>function($q){
                return $q->select('id','goods_master_image','goods_price','goods_name','goods_subname')->with('goods_sku');
            }]);
            $fav_list = $fav_model->paginate(request()->per_page??30);
            return $this->format(new FavoriteCollection($fav_list));
        }else{
            $fav_model = $fav_model->with(['store'=>function($q){
                return $q->select('id','store_name','store_logo');
            }]);
            $fav_list = $fav_model->paginate(request()->per_page??30);
            return $this->format(new FollowCollection($fav_list));
        }
        
        
    }

    // 添加收藏和关注
    public function addFav($out_id){
        if(!in_array(request()->is_type,[0,1])){
            return $this->format_error(__('base.failed'));
        }

        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();
        
        $fav_model = new Favorite();
        $fav_info = $fav_model->where(['user_id'=>$user_info['id'],'out_id'=>$out_id,'is_type'=>request()->is_type])->first();
        if(!empty($fav_info)){
            $fav_model->where(['user_id'=>$user_info['id'],'out_id'=>$out_id,'is_type'=>request()->is_type])->delete();
            return $this->format([],__('base.success'));
        }

        $fav_model->user_id = $user_info->id;
        $fav_model->out_id = $out_id;
        $fav_model->is_type = request()->is_type;
        $fav_model->save();
        return $this->format([],__('base.success'));

    }

    // 删除
    public function delFav($out_id){
        if(!in_array(request()->is_type,[0,1])){
            return $this->format_error(__('base.failed'));
        }

        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $fav_model = new Favorite();
        $fav_model->whereIn('out_id',$out_id)->where(['user_id'=>$user_info['id'],'is_type'=>request()->is_type])->delete();
        return $this->format([],__('base.success'));
    }

    // 判断是否有收藏
    public function isFav($out_id){
        if(!in_array(request()->is_type,[0,1])){
            return $this->format_error(__('base.failed'));
        }

        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();

        $fav_model = new Favorite();

        if(empty($user_info)){
            $user_info['id'] = 0;
        }

        $fav_info = $fav_model->where(['user_id'=>$user_info['id'],'out_id'=>$out_id,'is_type'=>request()->is_type])->first();
        if(empty($fav_info)){
            return $this->format_error();
        }
        return $this->format($fav_info);
    }
    
}
