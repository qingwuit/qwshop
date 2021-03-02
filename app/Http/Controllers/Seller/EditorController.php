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
            return $this->success($rs['data']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
