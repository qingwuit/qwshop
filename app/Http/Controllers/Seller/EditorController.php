<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\UploadService;

class EditorController extends Controller
{
    public function editor(){
        $upload_service = new UploadService();
        $rs = $upload_service->editer(1);
        if($rs['status']){
            $rs['errno'] = 0;
            if(!empty($rs['data'])){
                $imgData = [];
                foreach($rs['data'] as $v){
                    $imgData[] = ['url'=>$v,'alt'=>'','href'=>''];
                }
                $rs['data'] = $imgData;
            }
            return $rs;
        }else{
            return $this->error($rs['msg']);
        }
    }
}
