<?php
namespace App\Services;

use App\Models\Freight;

class FreightService extends BaseService{

    public function edit(){
        $list = request()->info;
        if(empty($list)){
            return $this->format_error(__('base.error'));
        }
        $freight_model = new Freight();
        $store_id = $this->get_store(true);
        foreach($list as $k=>$v){
            if($v['id'] != 0){ // 如果是已经存在的数据
                $freight_model = $freight_model->find($v['id']);
            }
            if($k==0){ // 默认运费编辑
                if($v['id'] != 0){ // 如果是已经存在的数据
                    // $freight_model->id=$v['id'];
                    $freight_model = $freight_model->find($v['id']);
                }
                $freight_model->is_type=0;
                $freight_model->store_id=$store_id;
                $freight_model->name='默认运费';
                $freight_model->f_weight=$v['f_weight'];
                $freight_model->f_price=$v['f_price'];
                $freight_model->o_weight=$v['o_weight'];
                $freight_model->o_price=$v['o_price'];
            }else{
                $freight_model->is_type=1;
                $freight_model->store_id=$store_id;
                $freight_model->name=$v['name'];
                $freight_model->f_weight=$v['f_weight'];
                $freight_model->f_price=$v['f_price'];
                $freight_model->o_weight=$v['o_weight'];
                $freight_model->o_price=$v['o_price'];
                $freight_model->area_id=empty($v['area_id'])?'':implode(',',$v['area_id']);
            }
            $freight_model->save();
            $freight_model = new Freight();
            
        }
        return $this->format([],__('base.success'));
    }

}
