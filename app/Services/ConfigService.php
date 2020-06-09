<?php
namespace App\Services;

use App\Models\Config;

class ConfigService extends BaseService{
    
    // 获取格式化配置
    public function get_format_config($name=''){
        $config_model = new Config;
        $list = $config_model->get();
        $data = [];
        foreach($list as $v){
            $data[$v['name']] = $v['val'];
        }

        if(empty($name)){
            return $data;
        }
        return $data[$name];
    }
}