<?php
namespace App\Services;

use App\Models\IntegralGoods;

class IntegralGoodsService extends BaseService{

    public function search(){
        $ig_model = new IntegralGoods();
        $params = request()->params??'';
        try{
            if(!empty($params)){
                $params_array = json_decode(base64_decode($params),true);
    
                // 栏目
                if(isset($params_array['cid']) && !empty($params_array['cid'])){
                    $ig_model = $ig_model->where('cid',$params_array['cid']);
                }

                // 排序
                if(isset($params_array['sort_type']) && !empty($params_array['sort_type'])){
                    $ig_model = $ig_model->orderBy($params_array['sort_type'],$params_array['sort_order']);
                }else{
                    $ig_model = $ig_model->orderBy('id','desc')->orderBy('goods_sale','desc');
                }
            }
    
            $list = $ig_model->where('goods_status',1)
                        ->paginate(request()->per_page??30);
        }catch(\Exception $e){
            return $this->format_error(__('goods.search_error'));
        }
        return $this->format($list);
        // return $this->format(new GoodsSearchCollection($list));
    }
}
