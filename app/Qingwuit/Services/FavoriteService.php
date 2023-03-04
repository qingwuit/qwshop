<?php
namespace App\Qingwuit\Services;

use App\Http\Resources\FavoriteHomeCollection;
use App\Http\Resources\FollowHomeCollection;

class FavoriteService extends BaseService
{
    public function fav()
    {
        $type = request()->is_type??0;
        $userId = $this->getUserId('users');

        $model = $this->getService('Favorite', true)->where(['user_id'=>$userId,'is_type'=>$type]);
        if ($type==0) {
            $model = $model->with(['goods'=>function ($q) {
                return $q->select('id', 'goods_master_image', 'goods_price', 'goods_name', 'goods_subname')->with('goods_skus');
            }]);
            $fav_list = $model->paginate(request()->per_page??30);
            return $this->format(new FavoriteHomeCollection($fav_list));
        } else {
            $model = $model->with(['store'=>function ($q) {
                return $q->select('id', 'store_name', 'store_logo','area_info');
            }]);
            $fav_list = $model->paginate(request()->per_page??30);
            return $this->format(new FollowHomeCollection($fav_list));
        }
    }

    // 添加收藏和关注
    public function add()
    {
        $out_id = request()->out_id??0;
        if (!in_array(request()->is_type, [0,1])) {
            return $this->formatError(__('tip.error'));
        }

        $userId = $this->getUserId('users');
        
        $fav_info = $this->getService('Favorite', true)->where(['user_id'=>$userId,'out_id'=>$out_id,'is_type'=>request()->is_type])->first();
        if (!empty($fav_info)) {
            $this->getService('Favorite', true)->where(['user_id'=>$userId,'out_id'=>$out_id,'is_type'=>request()->is_type])->delete();
            if(request('is_type') == 0) $this->getService('Goods',true)->where('id',$out_id)->decrement('goods_collect');
            return $this->format([], __('tip.success'));
        }
        $model = $this->getService('Favorite', true);
        $model->user_id = $userId;
        $model->out_id = $out_id;
        $model->is_type = request()->is_type;
        $model->save();
        if(request('is_type') == 0) $this->getService('Goods',true)->where('id',$out_id)->increment('goods_collect');
        return $this->format([], __('tip.success'));
    }

    // 判断是否有收藏
    public function isFav()
    {
        $out_id = request()->out_id??0;
        if (!in_array(request()->is_type, [0,1])) {
            return $this->formatError(__('tip.error'));
        }

        $userId = 0;
        try {
            $userId = $this->getUserId('users');
        } catch (\Exception $e) {
            return $this->format(false, $e->getMessage());
        }
        
        $fav_info = $this->getService('Favorite', true)->where(['user_id'=>$userId,'out_id'=>$out_id,'is_type'=>request()->is_type])->exists();
        if (empty($fav_info)) {
            return $this->format(false);
        }
        return $this->format(true);
    }
}
