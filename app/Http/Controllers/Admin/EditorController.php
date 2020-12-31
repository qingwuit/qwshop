<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UploadService;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function editor(){
        $upload_service = new UploadService();
        $rs = $upload_service->editer(1);
        if($rs['status']){
            $rs['errno'] = 0;
            return $rs;
        }else{
            return $this->error($rs['msg']);
        }
    }
}
