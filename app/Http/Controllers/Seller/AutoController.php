<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Tools\Uploads;

class AutoController extends BaseController
{
    // 添加商品或者其他商品富文本编辑器上传接口
    public function auto_upload(){
        $uploads = new Uploads();
        $data = [
            'filepath'=>'editor/',
        ];
        $rs = $uploads->editor_upload($data);
        if($rs['status']){
            return ['data'=>$rs['path'],'errno'=>0];
        }else{
            return ['data'=>[],'errno'=>1];
        }
    }
}
