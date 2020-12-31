<?php
namespace App\Services;

use App\Http\Resources\Home\FullReductionResource\FullReductionCollection;
use App\Models\FullReduction;

class FullReductionService extends BaseService{
    // 根据店铺ID 获取满减 列表
    public function getFullReductionByStoreId($store_id){
        $fr_model = new FullReduction();
        $list = $fr_model->where('store_id',$store_id)->where('start_time','<=',now())->where('end_time','>=',now())->get();

        if($list->isEmpty()){
            return $this->format_error(__('markets.full_reduction_empty'));
        }

        return $this->format(new FullReductionCollection($list));
    }

    // 根据店铺ID 获取满减 详情
    public function getFullReductionInfoByStoreId($store_id,$total_price){
        $fr_model = new FullReduction();
        $info = $fr_model->where('store_id',$store_id)->where('start_time','<',now())->where('end_time','>',now())->where('use_money','<=',$total_price)->orderBy('use_money','asc')->first();

        if(!$info){
            return $this->format_error(__('markets.full_reduction_empty'));
        }

        return $this->format($info);
    }
}
