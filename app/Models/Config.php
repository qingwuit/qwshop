<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = "config"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function get_format_config(){
        $config_list = $this->get();
        $config_format = get_format_config($config_list);
        return $config_format;
    }
}
